<!DOCTYPE html><html class="light" lang="id" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Riwayat Transaksi - Mataram Stay</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700&amp;family=Manrope:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container": "#f2ece4",
                        "on-tertiary-fixed-variant": "#6e3030",
                        "on-surface-variant": "#605850",
                        "surface-dim": "#dcd6cc",
                        "surface-container-high": "#ece6dc",
                        "surface-bright": "#faf5ee",
                        "on-secondary": "#ffffff",
                        "on-secondary-container": "#605850",
                        "secondary-container": "#eae2da",
                        "primary": "#c2652a",
                        "on-primary-fixed-variant": "#8a4518",
                        "surface-container-low": "#f6f0e8",
                        "on-primary": "#ffffff",
                        "on-background": "#3a302a",
                        "inverse-surface": "#3a302a",
                        "surface-container-highest": "#e6e0d6",
                        "on-surface": "#3a302a",
                        "surface": "#faf5ee",
                        "outline": "#9a9088",
                        "outline-variant": "#d8d0c8",
                        "background": "#faf5ee",
                        "surface-tint": "#c2652a",
                        "secondary": "#78706a",
                        "primary-container": "#e08850",
                        "on-primary-container": "#fbe8d8"
                    },
                    "fontFamily": {
                        "headline": ["EB Garamond", "serif"],
                        "display": ["EB Garamond", "serif"],
                        "body": ["Manrope", "sans-serif"],
                        "label": ["Manrope", "sans-serif"]
                    }
                }
            }
        }
    </script>
<style>
        body { font-family: 'Manrope', sans-serif; background-color: #faf5ee; }
        .font-headline { font-family: 'EB Garamond', serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .shadow-soft { box-shadow: 0 2px 16px rgba(58, 48, 42, 0.04); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #faf5ee; }
        ::-webkit-scrollbar-thumb { background: #d8d0c8; border-radius: 10px; }
    </style>
</head>
<body class="text-on-surface antialiased flex min-h-screen">

<!-- SideNavBar (Desktop Only) -->
<aside class="fixed inset-y-0 left-0 z-50 bg-surface-container-low border-r border-outline-variant/20 w-64 flex flex-col py-8 px-4 h-screen hidden md:flex">
<!-- Brand Header -->
<div class="px-4 mb-10"><a href="/"><h1 class="font-headline text-2xl font-semibold text-primary mb-1">Mataram Stay</h1></a></div>
<!-- Navigation Tabs -->
<nav class="flex-1 space-y-1">
    @if(auth()->user()->isOwner())
    <a class="group flex items-center px-4 py-3 text-secondary hover:text-primary hover:bg-secondary-container/30 transition-all rounded-lg mb-2" href="{{ route('dashboard.owner') }}">
        <span class="material-symbols-outlined mr-3">grid_view</span>
        <span class="text-label-lg font-medium">Dashboard</span>
    </a>
    <a class="group flex items-center px-4 py-3 text-primary font-bold border-r-4 border-primary bg-primary-container/10 transition-all rounded-l-lg -mr-4 mb-2" href="{{ route('transactions.owner') }}">
        <span class="material-symbols-outlined mr-3" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
        <span class="text-label-lg">Transaksi</span>
    </a>
    @else
    <a class="group flex items-center px-4 py-3 text-secondary hover:text-primary hover:bg-secondary-container/30 transition-all rounded-lg mb-2" href="{{ route('dashboard.seeker') }}">
        <span class="material-symbols-outlined mr-3">grid_view</span>
        <span class="text-label-lg font-medium">Dashboard</span>
    </a>
    <a class="group flex items-center px-4 py-3 text-primary font-bold border-r-4 border-primary bg-primary-container/10 transition-all rounded-l-lg -mr-4 mb-2" href="{{ route('transactions.seeker') }}">
        <span class="material-symbols-outlined mr-3" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
        <span class="text-label-lg">Transaksi</span>
        @if($hasPendingTransaction ?? false)
            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse ml-1"></span>
        @endif
    </a>
    @endif
</nav>
<!-- Footer Actions -->
<div class="mt-auto pt-6 border-t border-outline-variant/30 space-y-1">
    <a class="group flex items-center px-4 py-3 text-secondary hover:text-primary hover:bg-secondary-container/30 transition-all rounded-lg" href="#">
        <span class="material-symbols-outlined mr-3">help</span>
        <span class="text-label-lg font-medium">Support</span>
    </a>
    <form action="/logout" method="POST" class="w-full">
        @csrf
        <button type="submit" class="group w-full flex items-center px-4 py-3 text-secondary hover:text-primary hover:bg-secondary-container/30 transition-all rounded-lg text-left">
            <span class="material-symbols-outlined mr-3">logout</span>
            <span class="text-label-lg font-medium">Logout</span>
        </button>
    </form>
</div>
</aside>

<!-- Main Content Canvas -->
<main class="flex-1 md:ml-64 min-h-screen bg-background flex flex-col">
<div class="p-6 lg:p-12 flex-1">
    
<!-- TopAppBar Contextual (Profile Header) -->
<header class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
    <div>
        <h2 class="font-headline text-4xl font-bold text-on-surface tracking-tight leading-tight">Riwayat Transaksi</h2>
        <p class="text-secondary mt-2 max-w-xl text-lg font-light">Monitor arus kas, konfirmasi pembayaran, dan analisis okupansi hunian Anda secara real-time.</p>
    </div>
    <div class="flex items-center gap-6">
        <div class="relative">
            <button class="material-symbols-outlined text-secondary hover:text-primary transition-colors flex items-center">
            notifications
            <span class="absolute top-0 right-0 w-2 h-2 bg-red-700 rounded-full border border-surface"></span>
            </button>
        </div>
        <!-- PROFILE DINAMIS -->
        <a href="/profile" class="flex items-center gap-4 bg-surface-container-low/50 hover:bg-surface-container py-1.5 px-4 rounded-full transition-colors">
            <div class="text-right hidden sm:block">
                <p class="font-bold text-on-surface text-sm leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-secondary uppercase tracking-widest font-bold">{{ auth()->user()->isOwner() ? 'Pemilik Kos' : 'Pencari Kos' }}</p>
            </div>
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </a>
    </div>
</header>

@if($role === 'seeker' && $hasPendingTransaction)
    <div class="bg-orange-50 border border-orange-200 text-orange-800 p-4 rounded-xl flex items-start gap-3 shadow-sm mb-6 animate-in fade-in slide-in-from-top-4 duration-300">
        <span class="text-xl shrink-0">⚠️</span>
        <p class="font-body text-sm leading-relaxed">
            Pengingat: Anda memiliki transaksi yang belum diselesaikan (Pending). Silakan cek detail pesanan dan segera lakukan pembayaran agar pengajuan sewa Anda tidak dibatalkan otomatis.
        </p>
    </div>
@endif

@if($role === 'owner')
<!-- Financial Summary Cards -->
@php
    $totalRevenue = $bookings->where('payment_status', 'Paid')->sum('total_price');
    $successCount = $bookings->where('payment_status', 'Paid')->count();
    $pendingCount = $bookings->whereIn('status', ['Pending'])->count();
@endphp
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <div class="bg-surface-container-lowest p-8 rounded-xl shadow-soft border border-outline-variant/30 group hover:border-primary/40 transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-primary/10 rounded-lg text-primary">
                <span class="material-symbols-outlined">payments</span>
            </div>
        </div>
        <h3 class="text-secondary text-sm font-semibold uppercase tracking-wider mb-1">Total Pendapatan</h3>
        <p class="font-headline text-3xl font-bold text-on-surface">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    
    <div class="bg-surface-container-lowest p-8 rounded-xl shadow-soft border border-outline-variant/30 group hover:border-primary/40 transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-secondary-container/50 rounded-lg text-secondary">
                <span class="material-symbols-outlined">verified</span>
            </div>
        </div>
        <h3 class="text-secondary text-sm font-semibold uppercase tracking-wider mb-1">Transaksi Berhasil</h3>
        <p class="font-headline text-3xl font-bold text-on-surface">{{ $successCount }}</p>
    </div>
    
    <div class="bg-surface-container-lowest p-8 rounded-xl shadow-soft border border-outline-variant/30 group hover:border-primary/40 transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-orange-100 rounded-lg text-orange-700">
                <span class="material-symbols-outlined">hourglass_empty</span>
            </div>
        </div>
        <h3 class="text-secondary text-sm font-semibold uppercase tracking-wider mb-1">Menunggu Konfirmasi</h3>
        <p class="font-headline text-3xl font-bold text-on-surface">{{ $pendingCount }} <span class="text-sm font-body font-normal text-secondary">Pesanan</span></p>
        @if($pendingCount > 0)
        <p class="text-xs text-orange-600 mt-2 font-medium">Perlu tindakan segera</p>
        @endif
    </div>
</div>
@endif

<!-- Transaction Table/List -->
<div class="bg-surface-container-lowest rounded-xl shadow-soft border border-outline-variant/20 overflow-hidden">
<div class="px-8 py-6 border-b border-outline-variant/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
<h3 class="font-headline text-2xl font-semibold">Daftar Transaksi</h3>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left whitespace-nowrap">
<thead>
<tr class="bg-surface-container-low">
<th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">{{ $role === 'owner' ? 'Pelanggan' : 'Properti' }}</th>
<th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">{{ $role === 'owner' ? 'Properti' : 'Tipe Kamar' }}</th>
<th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Tanggal</th>
<th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Durasi</th>
<th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Total</th>
<th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary text-center">Status</th>
<th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/20">
@forelse($bookings as $booking)
@php
    $prop = $booking->roomType->property ?? null;
    $statusColors = [
        'Pending' => 'bg-orange-100 text-orange-700',
        'Active' => 'bg-green-100 text-green-700',
        'Completed' => 'bg-blue-100 text-blue-700',
        'Cancelled' => 'bg-red-100 text-red-700',
    ];
@endphp
<tr class="hover:bg-surface-container-low/30 transition-colors">
<td class="px-8 py-6">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
            {{ strtoupper(substr($role === 'owner' ? $booking->user->name : ($prop->name ?? '-'), 0, 2)) }}
        </div>
        <div>
            <p class="font-bold text-on-surface">{{ $role === 'owner' ? $booking->user->name : ($prop->name ?? '-') }}</p>
            <p class="text-xs text-secondary">{{ $role === 'owner' ? $booking->user->email : ($prop->area ?? '-') }}</p>
        </div>
    </div>
</td>
<td class="px-8 py-6 font-medium text-secondary">{{ $role === 'owner' ? ($prop->name ?? '-') : $booking->roomType->name }}</td>
<td class="px-8 py-6 text-sm">{{ $booking->check_in_date->format('d M Y') }}</td>
<td class="px-8 py-6 text-sm">{{ $booking->duration_months }} Bulan</td>
<td class="px-8 py-6 font-bold text-on-surface">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
<td class="px-8 py-6 text-center">
    <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">{{ $booking->status }}</span>
</td>
<td class="px-8 py-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('booking.show', $booking) }}" class="text-primary hover:underline text-xs font-bold uppercase">Detail</a>
        @if($booking->status === 'Completed' && $booking->payment_status === 'Paid' && !$booking->review)
            <a href="{{ route('booking.show', $booking) }}#review-section" class="bg-primary text-on-primary px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase hover:bg-primary-container transition-all">
                Beri Ulasan
            </a>
        @endif
    </div>
</td>
</tr>
@empty
<tr>
    <td colspan="7" class="px-8 py-16 text-center text-secondary">
        <span class="material-symbols-outlined text-4xl text-outline mb-2 block">receipt_long</span>
        <p class="font-bold">Belum ada transaksi.</p>
    </td>
</tr>
@endforelse
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="px-8 py-4 border-t border-outline-variant/30">
    {{ $bookings->links() }}
</div>
</div>
</div> <!-- End of Padding Container -->

<!-- Empty Space / Atmospheric Detail -->
<x-footer />
    <!-- Spasi tambahan untuk layar mobile agar konten tidak tertutup navigasi bawah -->
    <div class="h-20 md:hidden w-full"></div>
</main>

<!-- MOBILE BOTTOM NAVIGATION -->
<nav class="md:hidden fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 py-3 pb-safe bg-surface border-t border-outline-variant/60 shadow-[0_-2px_16px_rgba(58,48,42,0.06)]">
    @if(auth()->user()->isOwner())
    <a class="flex flex-col items-center justify-center text-on-secondary-fixed-variant hover:text-primary transition-colors" href="{{ route('dashboard.owner') }}">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="font-body text-[10px] uppercase tracking-wider mt-1">Home</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-secondary-fixed-variant hover:text-primary transition-colors" href="{{ route('property.create') }}">
        <span class="material-symbols-outlined">add_circle</span>
        <span class="font-body text-[10px] uppercase tracking-wider mt-1">Add</span>
    </a>
    <a class="flex flex-col items-center justify-center text-primary" href="{{ route('transactions.owner') }}">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
        <span class="font-body text-[10px] uppercase tracking-wider mt-1 font-bold">Transaksi</span>
    </a>
    @else
    <a class="flex flex-col items-center justify-center text-on-secondary-fixed-variant hover:text-primary transition-colors" href="{{ route('dashboard.seeker') }}">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="font-body text-[10px] uppercase tracking-wider mt-1">Home</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-secondary-fixed-variant hover:text-primary transition-colors" href="{{ route('search') }}">
        <span class="material-symbols-outlined">explore</span>
        <span class="font-body text-[10px] uppercase tracking-wider mt-1">Explore</span>
    </a>
    <a class="flex flex-col items-center justify-center text-primary relative" href="{{ route('transactions.seeker') }}">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
        <span class="font-body text-[10px] uppercase tracking-wider mt-1 font-bold">Transaksi</span>
        @if($hasPendingTransaction ?? false)
            <span class="absolute top-2 right-4 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
        @endif
    </a>
    @endif
    <a class="flex flex-col items-center justify-center text-on-secondary-fixed-variant hover:text-primary transition-colors" href="/profile">
        <span class="material-symbols-outlined">person</span>
        <span class="font-body text-[10px] uppercase tracking-wider mt-1">Profile</span>
    </a>
</nav>

</body></html>