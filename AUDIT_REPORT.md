# Audit & Analisis Arsitektur Sistem Serta UI/UX (Standar OTA Enterprise)

Laporan audit ini disusun untuk menyelaraskan kualitas arsitektur kode, skema database, kinerja, keamanan, dan alur UI/UX aplikasi **Mataram Stay** agar setara dengan standar agen perjalanan online (OTA) modern di Indonesia (seperti Traveloka, Tiket.com, atau Mamikos).

---

## Executive Summary
Mataram Stay telah mengimplementasikan fitur-fitur krusial seperti sistem pembayaran otomatis via Midtrans, pencairan otomatis ke mitra pemilik kos (escrow payout), pemetaan geografis kos terdekat dengan Leaflet, serta komunikasi real-time chat. Namun, hasil audit mendalam menunjukkan adanya sisa-sisa kode usang (*dead code*) dari alur autentikasi lama, celah keamanan pada endpoint backend, duplikasi konfigurasi antarmuka, ketidaklengkapan indeks database, serta potensi hambatan interaksi pengguna (*UX friction points*). 

Penyelesaian poin-poin dalam audit ini akan meningkatkan stabilitas, performa beban kueri, keamanan transaksi, serta kemudahan adopsi pengguna secara signifikan.

---

## 1. Temuan Kritis (High Priority)

### A. Celah Keamanan: Endpoint Autentikasi Manual Masih Aktif di Backend
* **Lokasi**: `routes/web.php`, `app/Http/Controllers/AuthController.php`
* **Temuan**: Meskipun form input email dan kata sandi telah dibuang dari antarmuka login/register di frontend demi transisi penuh ke Google SSO, rute backend `POST /login` dan `POST /register` beserta fungsi pengolahnya (`authenticate()` dan `store()`) masih aktif dan menerima input manual.
* **Dampak**: Pihak tidak bertanggung jawab dapat mengirimkan permintaan HTTP POST langsung menggunakan tool API (cURL, Postman) ke endpoint tersebut untuk memotong kebijakan Google SSO murni, melakukan registrasi paksa, atau mencoba melakukan serangan brute-force password.
* **Rekomendasi**: 
  1. Hapus rute `Route::post('/login')` dan `Route::post('/register')` dari `routes/web.php`.
  2. Hapus method `authenticate` dan `store` di `AuthController.php`, menyisakan method `logout` saja.

### B. Bug Integrasi: Hardcoded URL Midtrans Snap Ke Lingkungan Sandbox
* **Lokasi**: `resources/views/bookings/show.blade.php` (Line 441)
* **Temuan**: Script library Midtrans Snap dimuat menggunakan alamat sandbox secara permanen: `<script src="https://app.sandbox.midtrans.com/snap/snap.js" ...>`
* **Dampak**: Saat aplikasi diubah ke mode produksi (`MIDTRANS_IS_PRODUCTION=true`), pustaka Snap.js yang dimuat tetap versi sandbox. Hal ini akan menyebabkan kegagalan pemanggilan modal pembayaran instan untuk transaksi riil.
* **Rekomendasi**: Gunakan kondisional Blade untuk memuat berkas JS berdasarkan status lingkungan:
  ```html
  @if(config('services.midtrans.is_production'))
      <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
  @else
      <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
  @endif
  ```

### C. Integritas Relasi: Tidak Ada Composite Unique Key Pada Tabel Pivot `facility_property`
* **Lokasi**: `database/migrations/2026_06_04_122525_create_facilities_table.php` (Line 23)
* **Temuan**: Tabel pivot `facility_property` yang menghubungkan properti kos dan fasilitas hanya menggunakan foreign key tunggal tanpa batasan unik gabungan. Kolom ID auto-increment tambahan di sini juga tidak memiliki peran fungsional.
* **Dampak**: Relasi fasilitas yang sama dapat terduplikasi berkali-kali untuk properti yang sama di database. Ini merusak integritas data dan menyebabkan ikon fasilitas yang sama dirender ganda di halaman detail properti kos.
* **Rekomendasi**: Ubah struktur migrasi tabel pivot agar menggunakan primary key komposit dan hapus kolom `$table->id()`:
  ```php
  Schema::create('facility_property', function (Blueprint $table) {
      $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
      $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
      $table->primary(['property_id', 'facility_id']);
  });
  ```

---

## 2. Temuan Menengah (Medium Priority)

### A. Dead Code: Controller dan Antarmuka Reset Password
* **Lokasi**: `app/Http/Controllers/ForgotPasswordController.php`, `app/Http/Controllers/ResetPasswordController.php`, direktori `resources/views/auth/`
* **Temuan**: Controller, rute, dan berkas view untuk pemulihan sandi (`forgot-password` & `reset-password`) masih tersimpan di dalam struktur proyek.
* **Dampak**: Fitur ini menjadi mubazir dan menambah beban pemeliharaan kode karena aplikasi telah mewajibkan Google SSO dan password pengguna diset sebagai `nullable` (tidak ada login sandi lokal).
* **Rekomendasi**: Hapus berkas `ForgotPasswordController.php` dan `ResetPasswordController.php`, rute terkait di `web.php`, serta folder `resources/views/auth/` yang berisi template reset password.

### B. Pelanggaran MVC: Query Eloquent di Dalam File Blade (View)
* **Lokasi**: `resources/views/dashboard/transactions.blade.php` (Line 63)
* **Temuan**: Pengecekan ada tidaknya transaksi berstatus pending dilakukan langsung di dalam template Blade menggunakan kueri database model: `\App\Models\Booking::where(...)->exists()`.
* **Dampak**: Melanggar pola arsitektur MVC (Model-View-Controller). Menyebabkan kueri database tersebar di view yang menyulitkan proses debugging, optimasi query, dan penulisan unit testing.
* **Rekomendasi**: Pindahkan logika pengecekan transaksi pending ke `TransactionController` dan kirim hasilnya ke view melalui variabel konteks (misalnya `$hasPendingTransaction`).

### C. Potensi N+1 Query & Memory Overhead Pada Dasbor
* **Lokasi**: `app/Http/Controllers/DashboardController.php` (Method `owner` dan `admin`)
* **Temuan**:
  1. Pada method `owner()` (Line 104), percakapan di-eager load beserta *seluruh* pesan terkait (`messages`) hanya untuk mencari waktu pembuatan pesan terakhir guna pengurutan chat di memori PHP (`sortByDesc`).
  2. Pada method `admin()` (Line 150), semua baris data booking yang lunas dipanggil ke dalam memori (`Booking::where('payment_status', 'Paid')->get()`) hanya untuk dijumlahkan nilainya menggunakan fungsi pembantu koleksi Laravel (`sum()`).
* **Dampak**: 
  1. Jika riwayat chat memiliki ribuan pesan, proses ini akan memuat seluruh pesan ke memori server dan memicu crash *Memory Limit Exceeded*.
  2. Penjumlahan nominal uang dari ribuan transaksi di memori PHP sangat lambat dan memakan RAM besar dibanding kalkulasi agregat langsung di server database MySQL.
* **Rekomendasi**:
  1. Definisikan relasi `latestMessage` baru di model `Conversation` menggunakan subquery untuk eager-load satu pesan terakhir saja.
  2. Ganti kalkulasi koleksi dengan agregasi SQL langsung pada controller admin:
     ```php
     $totalAdminFeesCollected = Booking::where('payment_status', 'Paid')->sum('admin_fee');
     $totalCommissionsCollected = Booking::where('payment_status', 'Paid')->sum('commission_fee');
     ```

### D. Redundansi Skema & Duplikasi Kolom Profil Pengguna
* **Lokasi**: `database/migrations/2026_06_14_000001_make_password_nullable_in_users_table.php`, `database/migrations/2026_06_20_180000_add_google_id_and_avatar_to_users_table.php`
* **Temuan**: Kolom data sosial terduplikasi dalam tabel `users`. Terdapat kolom `social_id` & `auth_provider` dari migrasi lama, serta `google_id` & `avatar` dari migrasi terbaru. Selain itu, ada kolom `profile_photo` yang bertabrakan fungsi dengan `avatar`.
* **Dampak**: Kebingungan struktur data dan tidak efisiennya penggunaan penyimpanan database untuk data yang sama.
* **Rekomendasi**: Unifikasi kolom sosial menjadi satu skema terpadu (cukup pertahankan `google_id` dan `avatar`). Jika `profile_photo` kosong, sistem otomatis mengarah ke `avatar` milik Google SSO di level model (Accessor fallback).

### E. Duplikasi Konfigurasi Layout & CSS Global
* **Lokasi**: `resources/views/login.blade.php`, `resources/views/register.blade.php`, `resources/views/dashboard/transactions.blade.php`
* **Temuan**: File-file ini tidak memakai komponen layout terpusat `<x-layout>`. Mereka mendefinisikan ulang elemen `<head>`, CDN Tailwind CSS, pemanggilan Google Fonts, dan menduplikasi konfigurasi warna primer secara inline.
* **Dampak**: Peningkatan ukuran file HTML dan ketidakkonsistenan visual jika warna tema utama aplikasi diubah di masa mendatang.
* **Rekomendasi**: Bungkus halaman-halaman tersebut menggunakan komponen `<x-layout>` agar seragam dengan halaman utama dan halaman cari kos.

---

## 3. Peningkatan UI/UX (Low/Cosmetic Priority)

### A. UX Friction Point: Registrasi SSO Tanpa Pilihan Peran Terintegrasi
* **Friction**: Pilihan peran (*Pencari Kos* / *Pemilik Kos*) hanya terpasang di halaman Register. Jika pengguna baru langsung mengklik "Lanjutkan dengan Google" di halaman Login, backend akan langsung mendaftarkan mereka sebagai `seeker` (Pencari Kos) secara default. Pemilik kos baru tidak memiliki cara mudah untuk mendaftar lewat login page.
* **Solusi**: Jika backend mendeteksi user baru yang masuk lewat Google SSO tanpa data peran yang disimpan sebelumnya, arahkan mereka ke halaman perantara interaktif untuk memilih peran mereka sebelum masuk ke dashboard.

### B. UX Friction Point: Ketiadaan Validasi Kelengkapan Profil Pasca SSO (Nomor WhatsApp & Rekening Bank)
* **Friction**: API Google tidak menyediakan nomor telepon/WhatsApp. Akibatnya, pengguna baru yang masuk via SSO memiliki kolom `no_whatsapp` yang kosong. Padahal, nomor WhatsApp sangat krusial untuk notifikasi transaksi dan verifikasi OTP.
* **Solusi**: Terapkan middleware pelengkap data profil. Pengguna baru yang mendaftar via Google SSO wajib mengisi nomor WhatsApp aktif (dan rekening bank bagi owner) sebelum diizinkan mengakses halaman booking kos.

### C. UX Refactoring: File View Monolitik Terlalu Tebal
* **Lokasi**: `resources/views/profile.blade.php` (1.734 baris), `resources/views/owner_portal.blade.php` (1.500+ baris)
* **Friction**: Semua tampilan tab menu (profil, transaksi, pesan masuk, setting rekening, formulir verifikasi KTP, list kos) digabung di dalam satu file. Ini menyulitkan pemeliharaan, memperlambat proses render, dan rawan menimbulkan konflik kode saat pengembangan tim.
* **Solusi**: Pecah isi tab-tab tersebut menjadi potongan view independen (*Blade partials*) di dalam folder terpisah, misalnya: `resources/views/profile/partials/chat_tab.blade.php`, `resources/views/profile/partials/settings_tab.blade.php`, dll.

### D. Konsistensi UI: Inkonsistensi Parameter Desain Slider Rentang Harga
* **Friction**: Filter pencarian harga saat ini menggunakan input angka minimal dan maksimal manual. Standar OTA terkemuka (Traveloka, Mamikos) menggunakan interaksi Range Slider interaktif dengan visual statistik distribusi harga kos untuk mempermudah navigasi pencarian budget.
* **Solusi**: Ganti input teks harga minimal dan maksimal dengan range slider JavaScript (seperti noUiSlider) yang memiliki visualisasi grafik rentang harga kos yang populer di daerah Mataram.

---

## 4. Roadmap Perbaikan

Berikut adalah checklist langkah perbaikan terstruktur berdasarkan skala prioritas:

### Tahap 1: Keamanan & Integritas Data (High Priority)
- [x] Nonaktifkan rute `POST /login` dan `POST /register` manual untuk menutup celah akses langsung di backend.
- [x] Ubah pemanggilan JS library Midtrans Snap agar dinamis mendeteksi environment (Sandbox vs Production).
- [x] Refaktor tabel pivot `facility_property` dengan primary key komposit `(property_id, facility_id)` untuk mencegah redundansi fasilitas.
- [x] Tambahkan database indeks untuk kolom foreign key yang sering dipanggil dalam kueri relational (`properties.user_id`, `room_types.property_id`, `bookings.room_type_id`, `reviews.property_id`).

### Tahap 2: Pembersihan Struktur & Optimasi Query (Medium Priority)
- [x] Hapus file controller reset kata sandi (`ForgotPasswordController`, `ResetPasswordController`) beserta rute dan view terkait.
- [x] Hilangkan query database langsung di dalam berkas Blade `transactions.blade.php` dan pindahkan logikanya ke Controller.
- [x] Optimalkan kueri data pesan di `DashboardController@owner` dengan membatasi eager load hanya untuk pesan terbaru saja.
- [x] Gunakan kueri agregasi SQL `sum()` langsung di database untuk menghitung total pendapatan platform di `DashboardController@admin`.
- [x] Bersihkan redundansi kolom sosial pengguna (`social_id` dan `auth_provider` digabung dengan `google_id` dan `avatar`).

### Tahap 3: Restrukturisasi Antarmuka & UX (Low/Cosmetic Priority)
- [x] Refaktor `login.blade.php`, `register.blade.php`, dan `transactions.blade.php` agar menggunakan layout global `<x-layout>`.
- [x] Buat alur perantara (*onboarding step*) bagi pengguna baru Google SSO untuk memilih peran (seeker vs owner) jika mendaftar langsung dari halaman login.
- [x] Terapkan middleware pengisian data wajib profil (WhatsApp) setelah user berhasil terdaftar via SSO sebelum bisa booking.
- [x] Modularisasi berkas monolitik `profile.blade.php` dan `owner_portal.blade.php` menjadi file-file Blade partials yang teratur.
- [x] Tingkatkan filter harga kos di halaman pencarian menggunakan visual Range Slider interaktif bergaya OTA modern.
