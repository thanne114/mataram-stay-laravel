<x-layout>
    <main class="flex-grow max-w-5xl w-full mx-auto px-4 md:px-8 py-16">
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-16 animate-in fade-in slide-in-from-top-4 duration-500">
            <h1 class="text-4xl md:text-5xl font-headline font-bold text-on-surface tracking-tight">Pusat Bantuan & FAQ</h1>
            <p class="text-secondary font-body mt-4 text-sm md:text-base leading-relaxed">
                Temukan panduan cepat dan jawaban atas pertanyaan umum seputar pencarian, penyewaan, serta pengelolaan properti kos di platform Mataram Stay.
            </p>
        </div>

        <!-- Two Columns Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 mb-16">
            
            <!-- Column 1: Untuk Pencari Kos -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 border-b border-outline-variant/60 pb-3 mb-6">
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-xl">person_search</span>
                    <h2 class="text-xl font-headline font-bold text-on-surface">Untuk Pencari Kos (Seeker)</h2>
                </div>

                <div class="space-y-4">
                    <details class="group bg-white rounded-2xl border border-outline-variant/30 overflow-hidden transition-all duration-300 shadow-sm">
                        <summary class="flex justify-between items-center p-5 font-bold text-on-surface hover:text-[#c2652a] cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                            <span class="font-body text-sm md:text-base">Bagaimana cara memesan kos di Mataram Stay?</span>
                            <span class="material-symbols-outlined transform group-open:rotate-180 transition-transform duration-300 text-secondary group-hover:text-primary">expand_more</span>
                        </summary>
                        <div class="px-5 pb-5 pt-1 text-sm text-secondary font-body leading-relaxed border-t border-outline-variant/10">
                            Sangat mudah! Anda cukup mencari kos berdasarkan lokasi kampus terdekat, pilih tipe kamar yang sesuai, dan tentukan durasi sewa (berapa bulan). Setelah menekan tombol pesan, sistem akan mengarahkan Anda ke halaman tagihan untuk menyelesaikan pembayaran.
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl border border-outline-variant/30 overflow-hidden transition-all duration-300 shadow-sm">
                        <summary class="flex justify-between items-center p-5 font-bold text-on-surface hover:text-[#c2652a] cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                            <span class="font-body text-sm md:text-base">Apakah saya harus memverifikasi KTP sebelum memesan?</span>
                            <span class="material-symbols-outlined transform group-open:rotate-180 transition-transform duration-300 text-secondary group-hover:text-primary">expand_more</span>
                        </summary>
                        <div class="px-5 pb-5 pt-1 text-sm text-secondary font-body leading-relaxed border-t border-outline-variant/10">
                            Ya, demi keamanan dan kenyamanan bersama, Mataram Stay mewajibkan pencari kos untuk memverifikasi identitas. Anda perlu mengunggah foto KTP dan swafoto (selfie). Jangan khawatir, dokumen identitas Anda akan dienkripsi secara aman di dalam server privat kami dan tidak akan disalahgunakan.
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl border border-outline-variant/30 overflow-hidden transition-all duration-300 shadow-sm">
                        <summary class="flex justify-between items-center p-5 font-bold text-on-surface hover:text-[#c2652a] cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                            <span class="font-body text-sm md:text-base">Bagaimana metode pembayaran sewa kos di platform ini?</span>
                            <span class="material-symbols-outlined transform group-open:rotate-180 transition-transform duration-300 text-secondary group-hover:text-primary">expand_more</span>
                        </summary>
                        <div class="px-5 pb-5 pt-1 text-sm text-secondary font-body leading-relaxed border-t border-outline-variant/10">
                            Kami menggunakan gerbang pembayaran otomatis dari Midtrans. Anda dapat membayar tagihan dengan instan dan aman melalui QRIS, e-Wallet (Gopay, ShopeePay), Transfer Bank (Virtual Account), atau Kartu Kredit. Sistem kami juga menyediakan opsi unggah bukti transfer manual jika dibutuhkan.
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl border border-outline-variant/30 overflow-hidden transition-all duration-300 shadow-sm">
                        <summary class="flex justify-between items-center p-5 font-bold text-on-surface hover:text-[#c2652a] cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                            <span class="font-body text-sm md:text-base">Apakah uang sewa saya aman jika pesanan ditolak pemilik?</span>
                            <span class="material-symbols-outlined transform group-open:rotate-180 transition-transform duration-300 text-secondary group-hover:text-primary">expand_more</span>
                        </summary>
                        <div class="px-5 pb-5 pt-1 text-sm text-secondary font-body leading-relaxed border-t border-outline-variant/10">
                            Sangat aman. Jika kamar ternyata sudah penuh (overbooked) sesaat setelah Anda membayar, atau jika pemilik kos menolak pesanan, sistem akan segera mengirimkan notifikasi ke email Anda. Tim Admin Mataram Stay akan memproses pengembalian dana (refund) 100% ke rekening Anda tanpa potongan.
                        </div>
                    </details>
                </div>
            </div>

            <!-- Column 2: Untuk Pemilik Kos -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 border-b border-outline-variant/60 pb-3 mb-6">
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-xl">storefront</span>
                    <h2 class="text-xl font-headline font-bold text-on-surface">Untuk Pemilik Kos (Owner)</h2>
                </div>

                <div class="space-y-4">
                    <details class="group bg-white rounded-2xl border border-outline-variant/30 overflow-hidden transition-all duration-300 shadow-sm">
                        <summary class="flex justify-between items-center p-5 font-bold text-on-surface hover:text-[#c2652a] cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                            <span class="font-body text-sm md:text-base">Bagaimana cara mendaftarkan properti kos saya agar tayang?</span>
                            <span class="material-symbols-outlined transform group-open:rotate-180 transition-transform duration-300 text-secondary group-hover:text-primary">expand_more</span>
                        </summary>
                        <div class="px-5 pb-5 pt-1 text-sm text-secondary font-body leading-relaxed border-t border-outline-variant/10">
                            Silakan mendaftar dan masuk sebagai Pemilik Kos. Di Dasbor Anda, klik 'Tambah Properti', lalu lengkapi detail lokasi, fasilitas, foto bangunan, serta tipe kamar dan harganya. Setelah disimpan, kos Anda akan masuk status draf untuk ditinjau oleh Admin. Jika disetujui, kos Anda akan langsung tayang secara publik.
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl border border-outline-variant/30 overflow-hidden transition-all duration-300 shadow-sm">
                        <summary class="flex justify-between items-center p-5 font-bold text-on-surface hover:text-[#c2652a] cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                            <span class="font-body text-sm md:text-base">Berapa biaya komisi platform yang dipotong untuk pemilik kos?</span>
                            <span class="material-symbols-outlined transform group-open:rotate-180 transition-transform duration-300 text-secondary group-hover:text-primary">expand_more</span>
                        </summary>
                        <div class="px-5 pb-5 pt-1 text-sm text-secondary font-body leading-relaxed border-t border-outline-variant/10">
                            Mataram Stay menerapkan sistem bagi hasil yang adil dan transparan berupa pemotongan persentase (saat ini {{ $commissionRate }}%) dari subtotal harga sewa kamar. Anda bisa melihat tarif komisi dan hitungan 'Pendapatan Bersih' secara otomatis dan detail untuk setiap transaksi di Dasbor Anda.
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl border border-outline-variant/30 overflow-hidden transition-all duration-300 shadow-sm">
                        <summary class="flex justify-between items-center p-5 font-bold text-on-surface hover:text-[#c2652a] cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                            <span class="font-body text-sm md:text-base">Bagaimana proses verifikasi pembayaran dari penyewa?</span>
                            <span class="material-symbols-outlined transform group-open:rotate-180 transition-transform duration-300 text-secondary group-hover:text-primary">expand_more</span>
                        </summary>
                        <div class="px-5 pb-5 pt-1 text-sm text-secondary font-body leading-relaxed border-t border-outline-variant/10">
                            Jika penyewa membayar menggunakan sistem otomatis kami (QRIS/Virtual Account), status pesanan akan berubah menjadi 'Aktif/Lunas' secara seketika dan Anda akan menerima email notifikasi pesanan masuk. Jika penyewa memilih bayar manual di luar sistem, Anda harus mengecek bukti transfer yang mereka unggah dan mengklik tombol verifikasi di Dasbor Anda.
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl border border-outline-variant/30 overflow-hidden transition-all duration-300 shadow-sm">
                        <summary class="flex justify-between items-center p-5 font-bold text-on-surface hover:text-[#c2652a] cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                            <span class="font-body text-sm md:text-base">Berapa lama dana sewa akan cair ke rekening saya?</span>
                            <span class="material-symbols-outlined transform group-open:rotate-180 transition-transform duration-300 text-secondary group-hover:text-primary">expand_more</span>
                        </summary>
                        <div class="px-5 pb-5 pt-1 text-sm text-secondary font-body leading-relaxed border-t border-outline-variant/10">
                            Dana sewa yang telah dipotong komisi platform akan tercatat secara aman di sistem kami. Dana tersebut akan diteruskan secara berkala ke rekening bank pribadi Anda sesuai dengan siklus pencairan dana yang ditetapkan oleh platform (misalnya setiap penyewa sukses check-in atau pada akhir bulan).
                        </div>
                    </details>
                </div>
            </div>

        </div>

        <!-- Contact Section -->
        <div class="bg-primary/5 rounded-3xl border border-primary/20 p-8 text-center max-w-2xl mx-auto space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500 delay-150">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mx-auto">
                <span class="material-symbols-outlined text-3xl">chat</span>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-headline font-bold text-on-surface">Masih Butuh Bantuan Lain?</h3>
                <p class="text-secondary font-body text-xs md:text-sm">
                    Jika Anda memiliki pertanyaan spesifik yang tidak tercakup di atas, tim dukungan pelanggan kami siap membantu Anda secara langsung via WhatsApp.
                </p>
            </div>
            <div>
                <a href="https://wa.me/6281337594955?text=Halo%20Admin%20Mataram%20Stay,%20saya%20butuh%20bantuan." target="_blank" class="inline-flex items-center gap-2 bg-[#25d366] hover:bg-[#20ba5a] text-white px-8 py-3.5 rounded-2xl font-label font-bold text-sm shadow-sm transition-all active:scale-[0.98]">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.472 14.382c-.022-.08-.115-.188-.417-.34-.3-.149-1.777-.878-2.052-.978-.276-.1-.476-.149-.674.15-.198.298-.769.978-.943 1.177-.173.2-.347.224-.647.075-.3-.15-1.266-.467-2.41-1.485-.89-.792-1.492-1.77-1.666-2.07-.174-.3-.019-.462.13-.61.135-.133.3-.348.45-.52.15-.173.2-.3.3-.5.1-.2.05-.375-.025-.524-.075-.15-.675-1.624-.925-2.225-.244-.589-.493-.508-.674-.517-.181-.008-.389-.009-.595-.009-.206 0-.54.078-.823.386-.283.308-1.08 1.055-1.08 2.57 0 1.517 1.01 2.977 1.15 3.177.14.2 1.983 3.027 4.806 4.242.673.29 1.2.463 1.61.593.675.215 1.288.185 1.772.113.539-.08 1.62-.663 1.848-1.27.228-.607.228-1.127.16-1.226-.068-.1-.247-.149-.549-.3zM12 2c-5.52 0-10 4.48-10 10 0 1.91.54 3.7 1.56 5.23L2.03 22l4.9-1.28C8.38 21.49 10.13 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm0 18c-1.66 0-3.23-.46-4.57-1.27l-.33-.2-2.9.76.77-2.82-.22-.35C3.89 14.88 3.4 13.5 3.4 12c0-4.74 3.86-8.6 8.6-8.6 4.74 0 8.6 3.86 8.6 8.6S16.74 20 12 20z"/>
                    </svg>
                    <span>Hubungi WhatsApp Admin</span>
                </a>
            </div>
        </div>
    </main>
</x-layout>
