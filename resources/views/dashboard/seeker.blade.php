<!DOCTYPE html><html lang="en" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Mataram Stay - Temukan Kos Ternyaman</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "outline-variant": "#d8d0c8",
                        "on-secondary-fixed": "#2a2420",
                        "on-tertiary-fixed-variant": "#6e3030",
                        "background": "#faf5ee",
                        "surface-container-high": "#ece6dc",
                        "on-error-container": "#7a1a10",
                        "on-background": "#3a302a",
                        "on-tertiary": "#ffffff",
                        "on-secondary-container": "#605850",
                        "secondary": "#78706a",
                        "on-primary-fixed": "#401a08",
                        "secondary-fixed-dim": "#cec6be",
                        "surface-dim": "#dcd6cc",
                        "surface": "#faf5ee",
                        "on-secondary-fixed-variant": "#504840",
                        "on-primary": "#ffffff",
                        "on-error": "#ffffff",
                        "tertiary-fixed": "#fce0e0",
                        "tertiary-container": "#d47070",
                        "surface-tint": "#c2652a",
                        "on-primary-fixed-variant": "#8a4518",
                        "inverse-primary": "#f0a878",
                        "surface-variant": "#ece6dc",
                        "inverse-surface": "#3a302a",
                        "surface-container-highest": "#e6e0d6",
                        "tertiary": "#8c3c3c",
                        "on-surface-variant": "#605850",
                        "error-container": "#fce4e0",
                        "error": "#c0392b",
                        "secondary-fixed": "#eae2da",
                        "inverse-on-surface": "#faf5ee",
                        "on-tertiary-container": "#3a2020",
                        "secondary-container": "#eae2da",
                        "primary-fixed-dim": "#f0a878",
                        "primary-fixed": "#fbe8d8",
                        "surface-container": "#f2ece4",
                        "primary-container": "#e08850",
                        "on-surface": "#3a302a",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed": "#2e1515",
                        "surface-bright": "#faf5ee",
                        "tertiary-fixed-dim": "#e8a0a0",
                        "outline": "#9a9088",
                        "on-secondary": "#ffffff",
                        "primary": "#c2652a",
                        "on-primary-container": "#fbe8d8",
                        "surface-container-low": "#f6f0e8"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {},
                    "fontFamily": {
                        "headline": ["EB Garamond", "serif"],
                        "display": ["EB Garamond", "serif"],
                        "body": ["Manrope", "sans-serif"],
                        "label": ["Manrope", "sans-serif"]
                    }
                },
            },
        }
    </script>
<style>
        .font-headline { font-family: 'EB Garamond', serif; }
        .font-body { font-family: 'Manrope', sans-serif; }
        .font-label { font-family: 'Manrope', sans-serif; }
        .pb-safe-bottom { padding-bottom: env(safe-area-inset-bottom); }
    </style>
</head>
<body class="bg-background text-on-surface font-body antialiased min-h-screen flex flex-col selection:bg-primary-fixed selection:text-on-primary-fixed">
<x-navbar />
<main class="flex-grow flex flex-col items-center">
<section class="w-full relative min-h-[70vh] flex flex-col justify-center items-center px-4 md:px-8 py-20 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCuEH_caEyLh7FPtwWwEdkwMSgypMgLClq82i4r7GOA0oFiIPiNj7F9KbpB_30V2AjsjKqjl50UMl5a30hQRO74eMgtz5NDb6LFV4DJiNwxUR9XZ5yQYqKpgabLbYuEWHxwtDoVOGiXgyZVh97ZQI3ovMU6hv7C6flItc2AOHKcjxOJfblx8StERcbiQ-7jk1-ru8RK0U1s4u8AopAcvkrIp9V9bVIgfGnF12VhIIScd0AoZsHLZuVYdFAJijIaDoZpYlSVoCl81QE');">
<div class="absolute inset-0 bg-on-surface/40"></div>
<div class="relative z-10 max-w-4xl w-full text-center mb-12">
<h1 class="font-headline text-5xl md:text-7xl font-bold text-surface-bright mb-6 leading-tight drop-shadow-md">Halo, {{ auth()->user()->name }}! Siap menemukan hunian baru hari ini?</h1>
<p class="font-body text-xl text-surface-variant max-w-2xl mx-auto drop-shadow">Pilihan akomodasi terbaik dengan desain hangat dan fasilitas lengkap untuk kenyamanan Anda.</p>
</div>

<div class="relative z-10 w-full max-w-5xl bg-surface/85 backdrop-blur-xl p-2 md:p-3 rounded-2xl shadow-2xl border border-white/20">
<form action="/search" method="GET" class="flex flex-col md:flex-row gap-2">
<div class="flex-1 flex flex-col md:flex-row">
<div class="flex-1 px-4 py-3 md:py-4 border-b md:border-b-0 md:border-r border-outline-variant/30 flex flex-col gap-1">
<span class="text-[10px] uppercase tracking-widest font-bold text-secondary px-1">Lokasi</span>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-xl">location_on</span>
<select name="lokasi" class="w-full bg-transparent border-none focus:ring-0 text-on-surface font-body text-base outline-none cursor-pointer p-0 appearance-none">
<option value="">Semua Kecamatan</option>
<option value="Selaparang">Selaparang</option>
<option value="Mataram">Mataram</option>
<option value="Cakranegara">Cakranegara</option>
<option value="Ampenan">Ampenan</option>
<option value="Sekarbela">Sekarbela</option>
<option value="Sandubaya">Sandubaya</option>
</select>
</div>
</div>
<div class="flex-1 px-4 py-3 md:py-4 border-b md:border-b-0 md:border-r border-outline-variant/30 flex flex-col gap-1">
<span class="text-[10px] uppercase tracking-widest font-bold text-secondary px-1">Tipe Kos</span>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-xl">group</span>
<select name="tipe_kos" class="w-full bg-transparent border-none focus:ring-0 text-on-surface font-body text-base outline-none cursor-pointer p-0 appearance-none">
<option value="">Semua Tipe</option>
<option value="Putra">Putra</option>
<option value="Putri">Putri</option>
<option value="Campur">Campur</option>
</select>
</div>
</div>
<div class="flex-1 px-4 py-3 md:py-4 flex flex-col gap-1">
<span class="text-[10px] uppercase tracking-widest font-bold text-secondary px-1">Harga Maksimal</span>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-xl">payments</span>
<select name="harga_maksimal" class="w-full bg-transparent border-none focus:ring-0 text-on-surface font-body text-base outline-none cursor-pointer p-0 appearance-none">
<option value="">Tanpa Batas</option>
<option value="1000000">&lt; Rp 1.000.000</option>
<option value="1500000">&lt; Rp 1.500.000</option>
<option value="2000000">&lt; Rp 2.000.000</option>
</select>
</div>
</div>
</div>
<button class="bg-primary text-on-primary px-10 py-4 md:py-0 rounded-xl font-label font-bold hover:bg-primary-container transition-all shadow-lg flex items-center justify-center gap-3 group active:scale-95" type="submit">
<span class="material-symbols-outlined group-hover:scale-110 transition-transform">search</span>
                        Cari Sekarang
                    </button>
</form>
</div>
@if(session('success'))
<div class="relative z-20 max-w-5xl w-full mx-auto mt-4 px-2">
    <div class="p-4 rounded-xl bg-primary/5 border border-primary/20 text-primary flex items-center gap-3 shadow-lg">
        <span class="material-symbols-outlined">check_circle</span>
        <p class="font-bold text-sm">{{ session('success') }}</p>
    </div>
</div>
@endif

</section>

@if(isset($activeBooking) && $activeBooking)
<section class="w-full max-w-7xl mx-auto px-4 md:px-8 py-12 -mt-8 relative z-20">
<div class="bg-surface-container-low rounded-2xl border border-outline-variant/60 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-md">
<div class="flex flex-col md:flex-row items-center gap-6">
<div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/40">
@if($activeBooking->roomType->property->main_image)
    <img alt="{{ $activeBooking->roomType->property->name }}" class="w-full h-full object-cover" src="{{ asset('storage/' . $activeBooking->roomType->property->main_image) }}">
@else
    <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
        <span class="material-symbols-outlined text-4xl text-outline">apartment</span>
    </div>
@endif
</div>
<div class="flex flex-col gap-1 text-center md:text-left">
<span class="font-label text-xs font-bold text-primary uppercase tracking-widest">Pemesanan Aktif</span>
<h2 class="font-headline text-2xl font-bold text-on-surface">{{ $activeBooking->roomType->property->name }}</h2>
<div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-1">
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold 
    @if($activeBooking->status == 'Pending') bg-yellow-50 text-yellow-700 border border-yellow-100
    @elseif($activeBooking->status == 'Active') bg-green-50 text-green-700 border border-green-100
    @elseif($activeBooking->status == 'Completed') bg-blue-50 text-blue-700 border border-blue-100
    @else bg-red-50 text-red-700 border border-red-100 @endif">
    {{ $activeBooking->status }}
</span>
<span class="text-secondary text-sm font-label">• Check-in: {{ \Carbon\Carbon::parse($activeBooking->check_in_date)->format('d M Y') }}</span>
</div>
</div>
</div>
<a href="{{ route('booking.show', $activeBooking->id) }}" class="w-full md:w-auto bg-primary text-on-primary px-8 py-3.5 rounded-lg font-label font-bold hover:bg-primary-fixed-dim transition-all shadow-sm flex items-center justify-center gap-2 group">
    Lihat Detail Status
</a>
</div>
</section>
@endif
<section class="w-full max-w-7xl mx-auto px-6 md:px-8 py-24 flex flex-col gap-12">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
<div class="flex flex-col gap-3">
<h2 class="font-headline text-4xl md:text-5xl font-medium text-on-surface">Pilihan Terpopuler</h2>
<p class="font-body text-secondary text-lg max-w-xl">Kurasi hunian terbaik di Mataram dengan fasilitas premium dan kenyamanan maksimal.</p>
</div>

</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
@forelse($popularProperties as $property)
<article class="group bg-white rounded-3xl border border-outline-variant/30 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col">
<div class="relative aspect-[4/5] overflow-hidden bg-surface-container-low">
@if($property->main_image)
    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="{{ asset('storage/' . $property->main_image) }}" alt="{{ $property->name }}">
@else
    <div class="w-full h-full flex items-center justify-center text-outline">
        <span class="material-symbols-outlined text-5xl">apartment</span>
    </div>
@endif
<div class="absolute top-4 left-4 bg-on-background text-surface-bright px-3 py-1 rounded-full text-[10px] font-label font-bold tracking-widest uppercase">
    {{ $property->type }}
</div>
</div>
<div class="p-5 flex flex-col gap-3 flex-grow justify-between">
    <div>
        <div class="flex justify-between items-start gap-1">
            <h3 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight">
                <a href="{{ route('property.show', $property->slug) }}" class="hover:text-primary transition-colors">{{ $property->name }}</a>
            </h3>
            <div class="flex items-center gap-0.5 shrink-0 pt-1">
                <span class="material-symbols-outlined text-primary text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                <span class="font-label text-sm font-bold text-on-surface">{{ $property->average_rating ?? 'Baru' }}</span>
            </div>
        </div>
        <div class="flex items-center text-secondary gap-1 mt-1">
            <span class="material-symbols-outlined text-sm">location_on</span>
            <span class="font-label text-xs">{{ $property->area }}, Mataram</span>
        </div>
    </div>
    <div class="flex flex-col gap-2 mt-2">
        <div class="flex items-baseline gap-1">
            <span class="font-label font-bold text-xl text-primary">Rp {{ number_format($property->lowest_price, 0, ',', '.') }}</span>
            <span class="font-label text-xs text-secondary">/ bln</span>
        </div>
        <div class="inline-flex {{ $property->available_rooms > 0 ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }} px-3 py-1 rounded-full border self-start">
            <span class="font-label text-[10px] font-bold uppercase tracking-tight">{{ $property->available_rooms }} Kamar Tersedia</span>
        </div>
    </div>
</div>
</article>
@empty
<div class="col-span-full text-center py-12 text-secondary">
    <span class="material-symbols-outlined text-5xl mb-3">apartment</span>
    <p class="font-body text-base">Belum ada properti terpopuler saat ini.</p>
</div>
@endforelse
</div>
<div class="w-full flex justify-center mt-12">
    <a href="{{ route('search') }}" class="inline-flex items-center gap-3 border-2 border-primary text-primary px-10 py-4 rounded-xl font-label font-bold hover:bg-primary hover:text-on-primary transition-all duration-300 group shadow-sm hover:shadow-lg">
        Lihat Semua Properti
    </a>
</div>
</section>
</main>

<x-footer />
<div class="h-20 md:hidden w-full"></div>

<!-- MOBILE BOTTOM NAVIGATION -->
<nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center pt-3 pb-safe-bottom bg-surface/95 backdrop-blur-lg border-t border-outline-variant/60 shadow-[0_-4px_24px_rgba(58,48,42,0.1)] z-50">
<a class="flex flex-col items-center justify-center text-primary active:scale-95 transition-all px-4 py-2" href="{{ route('dashboard.seeker') }}">
<span class="material-symbols-outlined mb-1" style="font-variation-settings: 'FILL' 1;">home</span>
<span class="font-label text-[10px] font-bold uppercase tracking-widest">Home</span>
</a>
<a class="flex flex-col items-center justify-center text-secondary hover:text-primary transition-all px-4 py-2" href="{{ route('search') }}">
<span class="material-symbols-outlined mb-1">explore</span>
<span class="font-label text-[10px] font-bold uppercase tracking-widest">Explore</span>
</a>
<a class="flex flex-col items-center justify-center text-secondary hover:text-primary transition-all px-4 py-2" href="{{ route('transactions.seeker') }}">
<span class="material-symbols-outlined mb-1">receipt_long</span>
<span class="font-label text-[10px] font-bold uppercase tracking-widest">Transaksi</span>
</a>

<a class="flex flex-col items-center justify-center text-secondary hover:text-primary transition-all px-4 py-2" href="/profile">
<span class="material-symbols-outlined mb-1">person</span>
<span class="font-label text-[10px] font-bold uppercase tracking-widest">Profile</span>
</a>

</nav>

</body></html>
