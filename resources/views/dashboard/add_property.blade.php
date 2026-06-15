<!DOCTYPE html><html class="light" lang="id" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Mulai Listing Baru - Mataram Stay</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Eb+Garamond:wght@400;500;600;700;800&amp;family=Manrope:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">

<!-- TAMBAHAN LIBRARY LEAFLET.JS UNTUK PETA -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary-fixed": "#fbe8d8",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-fixed": "#401a08",
                        "on-tertiary-fixed-variant": "#6e3030",
                        "surface-bright": "#faf5ee",
                        "secondary-fixed": "#eae2da",
                        "background": "#faf5ee",
                        "error-container": "#fce4e0",
                        "secondary-fixed-dim": "#cec6be",
                        "on-primary-fixed-variant": "#8a4518",
                        "outline": "#9a9088",
                        "on-tertiary": "#ffffff",
                        "tertiary-fixed-dim": "#e8a0a0",
                        "tertiary-container": "#d47070",
                        "on-secondary-container": "#605850",
                        "on-secondary-fixed-variant": "#504840",
                        "primary-container": "#e08850",
                        "inverse-surface": "#3a302a",
                        "on-error-container": "#7a1a10",
                        "on-secondary-fixed": "#2a2420",
                        "on-primary": "#ffffff",
                        "surface-dim": "#dcd6cc",
                        "primary-fixed-dim": "#f0a878",
                        "on-primary-container": "#fbe8d8",
                        "on-surface": "#3a302a",
                        "on-secondary": "#ffffff",
                        "on-background": "#3a302a",
                        "inverse-primary": "#f0a878",
                        "surface": "#faf5ee",
                        "secondary": "#78706a",
                        "tertiary": "#8c3c3c",
                        "surface-container-highest": "#e6e0d6",
                        "outline-variant": "#d8d0c8",
                        "on-surface-variant": "#605850",
                        "on-error": "#ffffff",
                        "inverse-on-surface": "#faf5ee",
                        "surface-container-low": "#f6f0e8",
                        "surface-container": "#f2ece4",
                        "surface-container-high": "#ece6dc",
                        "surface-variant": "#ece6dc",
                        "error": "#c0392b",
                        "primary": "#c2652a",
                        "secondary-container": "#eae2da",
                        "on-tertiary-fixed": "#2e1515",
                        "surface-tint": "#c2652a",
                        "tertiary-fixed": "#fce0e0",
                        "on-tertiary-container": "#3a2020"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Eb Garamond"],
                        "display": ["Eb Garamond"],
                        "body": ["Manrope"],
                        "label": ["Manrope"]
                    }
                }
            }
        }
    </script>
<style>
        body { font-family: 'Manrope', sans-serif; background-color: #faf5ee; color: #3a302a; }
        h1, h2, h3 { font-family: 'Eb Garamond', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .custom-shadow { box-shadow: 0 4px 20px rgba(58, 48, 42, 0.06); }
        .input-focus:focus { outline: none; border-color: #c2652a; ring: 1px; ring-color: #c2652a; }
        /* Style untuk container Peta */
        #map { z-index: 10; }
    </style>
</head>
<body class="bg-background text-on-surface">
<x-navbar />

<div class="flex pt-16 min-h-screen">
<!-- Main Content -->
<main class="flex-1 p-6 md:p-10 lg:p-12 pb-32 bg-background">
<div class="max-w-4xl mx-auto">
<header class="mb-10">
<a href="/dashboard-owner" class="inline-flex items-center gap-2 px-4 py-2 mb-4 text-sm font-bold text-primary border border-primary rounded-xl hover:bg-primary-fixed/10 transition-all active:scale-[0.98] font-body w-fit"><span class="material-symbols-outlined text-sm">arrow_back</span>Kembali ke Dashboard</a><h1 class="text-3xl md:text-4xl font-bold text-on-surface mb-2">Mulai Listing Baru</h1>
<p class="text-on-surface-variant font-body text-sm md:text-base">Lengkapi detail properti Anda untuk mulai menarik penyewa di Mataram Stay.</p>
</header>

<form action="{{ route('property.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
@csrf

@if($errors->any())
<div class="bg-red-50 border border-red-200 rounded-xl p-4">
    <p class="text-red-700 font-bold text-sm mb-2">Terdapat kesalahan:</p>
    <ul class="list-disc list-inside text-red-600 text-xs space-y-1">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
    <span class="material-symbols-outlined text-green-600">check_circle</span>
    <p class="text-green-700 font-bold text-sm">{{ session('success') }}</p>
</div>
@endif

<!-- Section 1: Basic Info & MAP -->
<section class="bg-surface-container-lowest rounded-xl p-6 md:p-8 custom-shadow border border-outline-variant/30">
<div class="flex items-center gap-3 mb-6">
<span class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center">
<span class="material-symbols-outlined text-[18px]">info</span>
</span>
<h2 class="text-xl font-bold text-on-surface">Informasi Dasar</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
<div class="space-y-1.5">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Kos</label>
<input name="name" value="{{ old('name') }}" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline/60" placeholder="Contoh: Kos Srikandi Mataram" type="text" required>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tipe Kos</label>
<select name="type" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all" required>
<option value="Putra" {{ old('type') == 'Putra' ? 'selected' : '' }}>Putra</option>
<option value="Putri" {{ old('type') == 'Putri' ? 'selected' : '' }}>Putri</option>
<option value="Campur" {{ old('type') == 'Campur' ? 'selected' : '' }}>Campur</option>
</select>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kecamatan</label>
<select name="area" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all" required>
@foreach(['Ampenan','Mataram','Cakranegara','Sekarbela','Selaparang','Sandubaya'] as $area)
<option value="{{ $area }}" {{ old('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
@endforeach
</select>
</div>
<div class="md:col-span-2 space-y-1.5">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Alamat Lengkap</label>
<textarea name="address" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline/60" placeholder="Jl. Merdeka No. 12, Kelurahan..." rows="2" required>{{ old('address') }}</textarea>
</div>

<!-- CONTAINER MAP INTERAKTIF -->
<div class="md:col-span-2 space-y-3 mt-2">
    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Lokasi Properti (Peta Interaktif)</label>
    <p class="text-[10px] text-secondary mt-0">Klik pada peta, <b>ATAU</b> paste Link URL Google Maps pada kolom di bawah.</p>
    
    <!-- Div id="map" ini yang dipanggil oleh Javascript -->
    <div id="map" class="w-full h-[300px] bg-surface-container-high rounded-lg border border-outline-variant/40"></div>
    
    <!-- Input yang sudah dibuka agar bisa diketik / dipaste -->
    <div class="space-y-1.5 mt-3">
        <label class="text-[10px] font-bold text-outline uppercase tracking-wider">Koordinat / Link Google Maps</label>
        <input name="koordinat_input" id="koordinat_input" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline/60" placeholder="Contoh: Paste URL dari Google Maps di sini..." type="text">
    </div>
</div>

</div>
</section>

<!-- Section 2: Price & Units -->
<section class="bg-surface-container-lowest rounded-xl p-6 md:p-8 custom-shadow border border-outline-variant/30">
<div class="flex items-center gap-3 mb-6">
<span class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center">
<span class="material-symbols-outlined text-[18px]">payments</span>
</span>
<h2 class="text-xl font-bold text-on-surface">Harga &amp; Unit</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
<div class="space-y-1.5 md:col-span-2">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Tipe Kamar</label>
<input name="room_name" value="{{ old('room_name', 'Kamar Standar') }}" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all" placeholder="Contoh: Kamar Standar" type="text" required>
</div>
<div class="space-y-1.5 md:col-span-2">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Harga per Bulan (IDR)</label>
<div class="relative">
<span class="absolute left-4 top-3 text-sm text-on-surface-variant font-medium">Rp</span>
<input name="price_per_month" value="{{ old('price_per_month') }}" class="w-full bg-surface-bright border border-outline-variant rounded-lg pl-11 pr-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all" placeholder="1200000" type="number" min="100000" required>
</div>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Total Kamar</label>
<input name="total_rooms" value="{{ old('total_rooms') }}" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all" placeholder="10" type="number" min="1" required>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kamar Tersedia</label>
<input name="available_rooms" value="{{ old('available_rooms') }}" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all" placeholder="5" type="number" min="0" required>
</div>
</div>
</section>

<!-- Section 3: Amenities -->
<section class="bg-surface-container-lowest rounded-xl p-6 md:p-8 custom-shadow border border-outline-variant/30">
<div class="flex items-center gap-3 mb-6">
<span class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center">
<span class="material-symbols-outlined text-[18px]">check_circle</span>
</span>
<h2 class="text-xl font-bold text-on-surface">Fasilitas Tersedia</h2>
</div>
<div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
@foreach($facilities as $facility)
<label class="flex items-center gap-3 p-3 rounded-lg bg-surface border border-outline-variant/40 cursor-pointer hover:border-primary/50 transition-all group">
<input name="facilities[]" value="{{ $facility->id }}" class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant" type="checkbox" {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }}>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant group-hover:text-primary transition-colors">{{ $facility->icon ?? 'check_circle' }}</span>
<span class="text-sm font-medium text-on-surface">{{ $facility->name }}</span>
</div>
</label>
@endforeach
</div>
</section>

<!-- Section 4: Spesifikasi & Aturan Kos -->
<section class="bg-surface-container-lowest rounded-xl p-6 md:p-8 custom-shadow border border-outline-variant/30">
    <div class="flex items-center gap-3 mb-6">
        <span class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-[18px]">rule</span>
        </span>
        <h2 class="text-xl font-bold text-on-surface">Spesifikasi & Aturan Kos</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="space-y-1.5">
            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Ukuran Kamar</label>
            <input class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline/60" placeholder="Contoh: 3x3 meter" type="text">
        </div>

        <div class="space-y-1.5">
            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Biaya Listrik</label>
            <select class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                <option>Sudah Termasuk (Free)</option>
                <option>Token Masing-masing</option>
                <option>Tagihan Terpisah per Bulan</option>
            </select>
        </div>

        <div class="space-y-1.5">
            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Aturan Jam Malam</label>
            <select class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                <option>Bebas 24 Jam (Bawa Kunci Sendiri)</option>
                <option>Ada Jam Malam (Tutup Pukul 22.00)</option>
                <option>Ada Jam Malam (Tutup Pukul 23.00)</option>
            </select>
        </div>

        <div class="space-y-1.5">
            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tamu Lawan Jenis</label>
            <select class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                <option>Hanya di Ruang Tamu / Area Luar</option>
                <option>Dilarang Keras Masuk</option>
                <option>Bebas Bertamu ke Kamar</option>
            </select>
        </div>

        <div class="space-y-1.5">
            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Hewan Peliharaan</label>
            <select class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                <option>Tidak Diperbolehkan</option>
                <option>Boleh (Hewan Kecil dalam Kandang)</option>
                <option>Bebas</option>
            </select>
        </div>

        <div class="space-y-1.5">
            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Syarat Pasutri</label>
            <select class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                <option>Tidak Menerima Pasutri (Hanya Lajang)</option>
                <option>Boleh (Wajib Bawa Buku Nikah)</option>
                <option>Boleh (Tanpa Syarat Khusus)</option>
            </select>
        </div>

        <div class="md:col-span-2 space-y-1.5">
            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Aturan Tambahan & Deposit</label>
            <textarea class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline/60" placeholder="Contoh: Wajib membayar deposit Rp 500.000 di awal, biaya parkir mobil Rp 100.000/bulan, dilarang merokok di dalam kamar..." rows="2"></textarea>
        </div>
    </div>
</section>

<!-- Section 5: Photo Gallery -->
<section class="bg-surface-container-lowest rounded-xl p-6 md:p-8 custom-shadow border border-outline-variant/30">
<div class="flex items-center gap-3 mb-6">
<span class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center">
<span class="material-symbols-outlined text-[18px]">image</span>
</span>
<h2 class="text-xl font-bold text-on-surface">Galeri Foto</h2>
</div>
<div class="space-y-4">
<div class="space-y-2">
<label for="main_image" class="aspect-[21/9] w-full border-2 border-dashed border-outline-variant rounded-xl flex flex-col items-center justify-center bg-surface hover:bg-primary-fixed/10 hover:border-primary/50 transition-all cursor-pointer group">
<span class="material-symbols-outlined text-3xl text-on-surface-variant group-hover:text-primary mb-3">add_a_photo</span>
<div class="text-center">
<p class="font-bold text-on-surface">Unggah Foto Utama</p>
<p class="text-xs text-on-surface-variant mt-1">Klik untuk memilih file</p>
<p class="text-[10px] text-outline mt-2">JPG, PNG, WebP (Maks 2MB)</p>
</div>
</label>
<input name="main_image" id="main_image" type="file" accept="image/*" class="text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary-fixed file:text-primary hover:file:bg-primary-fixed-dim">
</div>
<div class="space-y-2">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Foto Galeri Tambahan (Opsional)</label>
<input name="gallery[]" type="file" accept="image/*" multiple class="text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary-fixed file:text-primary hover:file:bg-primary-fixed-dim">
<p class="text-[10px] text-outline">Anda bisa memilih beberapa file sekaligus</p>
</div>
</div>
</section>

<!-- Section 6: Description -->
<section class="bg-surface-container-lowest rounded-xl p-6 md:p-8 custom-shadow border border-outline-variant/30">
<div class="flex items-center gap-3 mb-6">
<span class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center">
<span class="material-symbols-outlined text-[18px]">description</span>
</span>
<h2 class="text-xl font-bold text-on-surface">Deskripsi Properti</h2>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Detail Tambahan</label>
<textarea name="description" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline/60" placeholder="Ceritakan kelebihan kos Anda, lingkungan sekitar, akses transportasi, dan peraturan kos lainnya..." rows="6">{{ old('description') }}</textarea>
</div>
</section>

<!-- Footer Actions -->
<div class="flex flex-col sm:flex-row gap-4 pt-6">
<button class="py-4 bg-primary text-white font-bold rounded-lg shadow-md hover:bg-primary/90 active:scale-[0.98] transition-all order-1 w-full" type="submit">Publikasikan Properti</button>
</div>
</form>
</div>
</main>
</div>
<x-footer />

<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 py-3 pb-safe bg-surface border-t border-outline-variant/60 shadow-[0_-2px_16px_rgba(58,48,42,0.06)]">
<a class="flex flex-col items-center justify-center text-on-secondary-fixed-variant hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-body text-[10px] uppercase tracking-wider mt-1">Home</span>
</a>
<a class="flex flex-col items-center justify-center text-primary" href="#">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">add_circle</span>
<span class="font-body text-[10px] uppercase tracking-wider mt-1">Add</span>
</a>
<a class="flex flex-col items-center justify-center text-on-secondary-fixed-variant hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined">other_houses</span>
<span class="font-body text-[10px] uppercase tracking-wider mt-1">My List</span>
</a>
<a class="flex flex-col items-center justify-center text-on-secondary-fixed-variant hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined">person</span>
<span class="font-body text-[10px] uppercase tracking-wider mt-1">Profile</span>
</a>
</nav>

<script>
        // SCRIPT INISIALISASI PETA LEAFLET.JS
        // Set koordinat awal ke pusat kota Mataram, Lombok (-8.5833, 116.1167)
        var map = L.map('map').setView([-8.5833, 116.1167], 13);

        // Memuat visual peta dari OpenStreetMap (Gratis)
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var marker;
        var inputKoordinat = document.getElementById('koordinat_input');

        // Fungsi 1: Saat peta DIKLIK secara manual
        map.on('click', function(e) {
            var lat = e.latlng.lat.toFixed(6); // Ambil latitude
            var lng = e.latlng.lng.toFixed(6); // Ambil longitude

            // Pindahkan/buat marker
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            // Masukkan nilai latitude dan longitude ke dalam input text
            inputKoordinat.value = lat + ", " + lng;
        });

        // Fungsi 2: Saat pengguna MEM-PASTE Link Maps / Mengetik Koordinat di Input Teks
        inputKoordinat.addEventListener('input', function(e) {
            let val = e.target.value.trim();
            let newLat = null;
            let newLng = null;

            // Cek Regex 1: Mendeteksi format URL Google Maps (mengandung @lat,lng)
            // Contoh URL: https://www.google.com/maps/place/Mataram/@-8.5833,116.1167,15z
            const urlMatch = val.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
            
            // Cek Regex 2: Mendeteksi ketikan/copas koordinat mentah 
            // Contoh: -8.5833, 116.1167
            const coordMatch = val.match(/^(-?\d+\.\d+)[,\s]+(-?\d+\.\d+)$/);

            if (urlMatch) {
                newLat = parseFloat(urlMatch[1]);
                newLng = parseFloat(urlMatch[2]);
            } else if (coordMatch) {
                newLat = parseFloat(coordMatch[1]);
                newLng = parseFloat(coordMatch[2]);
            }

            // Jika berhasil menemukan titik kordinat dari text/link tersebut
            if (newLat !== null && newLng !== null) {
                const newLatLng = new L.LatLng(newLat, newLng);
                
                // Terbangkan peta ke titik baru tersebut dengan zoom level 16
                map.setView(newLatLng, 16);
                
                // Pindahkan marker
                if (marker) {
                    marker.setLatLng(newLatLng);
                } else {
                    marker = L.marker(newLatLng).addTo(map);
                }
            }
        });


        // Simple Interaction logic for buttons
        document.querySelectorAll('button, a').forEach(elem => {
            if(!elem.closest('#map')) { // Jangan ganggu interaksi klik di area peta
                elem.addEventListener('mousedown', () => {
                    elem.classList.add('scale-[0.97]');
                });
                elem.addEventListener('mouseup', () => {
                    elem.classList.remove('scale-[0.97]');
                });
                elem.addEventListener('mouseleave', () => {
                    elem.classList.remove('scale-[0.97]');
                });
            }
        });
    </script>
</body></html>