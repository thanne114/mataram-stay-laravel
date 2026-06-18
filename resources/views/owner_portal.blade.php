<!DOCTYPE html>
<html class="light" lang="id" style="">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Owner Portal | Mataram Stay</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
              "background": "#faf5ee",
              "surface": "#faf5ee",
              "primary": "#c2652a",
              "primary-container": "#e08850",
              "on-primary-container": "#fbe8d8",
              "on-primary": "#ffffff",
              "secondary": "#78706a",
              "on-surface": "#3a302a",
              "on-surface-variant": "#605850",
              "surface-container-low": "#f6f0e8",
              "surface-container": "#f2ece4",
              "surface-container-high": "#ece6dc",
              "surface-container-highest": "#e6e0d6",
              "outline": "#9a9088",
              "outline-variant": "#d8d0c8",
              "tertiary": "#8c3c3c",
              "tertiary-container": "#d47070"
            },
            "fontFamily": {
              "headline": ["Eb Garamond"],
              "display": ["Eb Garamond"],
              "body": ["Manrope"],
              "label": ["Manrope"]
            }
          },
        },
      }
    </script>
    <style>
        body { font-family: 'Manrope', sans-serif; background-color: #faf5ee; color: #3a302a; }
        h1, h2, h3, h4 { font-family: 'Eb Garamond', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .sahara-shadow { box-shadow: 0 2px 16px rgba(58, 48, 42, 0.04); }
        .active-nav-border { border-bottom: 2px solid #c2652a; }
        
        /* Custom Switch */
        .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #d8d0c8; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #c2652a; }
        input:checked + .slider:before { transform: translateX(20px); }
    </style>
</head>
<body class="bg-background text-on-surface selection:bg-primary/20 flex flex-col min-h-screen">

<x-navbar />

<main class="max-w-screen-2xl mx-auto px-6 lg:px-12 py-10 lg:py-16 flex flex-col lg:flex-row gap-12 flex-1 w-full">
    
    <aside class="w-full lg:w-64 flex flex-col gap-2 shrink-0">
        <div class="mb-6 px-4">
            <h2 class="text-xl font-display font-semibold text-on-surface">{{ auth()->user()->role === 'admin' ? 'Admin Portal' : 'Mitra Properti' }}</h2>
            <p class="text-xs text-secondary font-body">{{ auth()->user()->role === 'admin' ? 'Platform management' : 'Pusat pengelolaan Anda' }}</p>
        </div>
        <nav class="flex flex-col gap-1" id="sidebar-nav">
            <button onclick="switchTab('dashboard', this)" class="nav-item flex items-center gap-3 bg-primary-container text-on-primary-container rounded-lg px-4 py-3 font-semibold font-body text-sm shadow-sm transition-all w-full text-left">
                <span class="material-symbols-outlined text-xl" data-icon="dashboard">dashboard</span>
                Dashboard
            </button>

            <button onclick="switchTab('transaksi', this)" class="nav-item flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group w-full text-left">
                <span class="material-symbols-outlined text-xl group-hover:text-primary" data-icon="receipt_long">receipt_long</span>
                Transaksi
            </button>
            
            <button onclick="switchTab('settings', this)" class="nav-item flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group w-full text-left">
                <span class="material-symbols-outlined text-xl group-hover:text-primary" data-icon="settings">settings</span>
                Settings
            </button>
            
            <div class="mt-8 pt-4 border-t border-outline/10">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-tertiary hover:bg-tertiary/10 rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group w-full text-left">
                        <span class="material-symbols-outlined text-xl" data-icon="logout">logout</span>
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div class="flex-1 max-w-5xl">
        
        <section id="dashboard" class="tab-content space-y-12 block animate-in fade-in duration-500">
            <div class="space-y-2">
                <h2 class="font-headline text-4xl md:text-5xl text-on-surface">Selamat Datang Kembali, {{ auth()->user()->name ?? (auth()->user()->role === 'admin' ? 'Administrator' : 'Pemilik') }}!</h2>
                <p class="text-secondary max-w-2xl leading-relaxed">
                    {{ auth()->user()->role === 'admin' 
                        ? 'Berikut adalah ringkasan kinerja seluruh properti dan transaksi di platform Mataram Stay secara global.' 
                        : 'Berikut adalah ringkasan kinerja properti Anda di Mataram Stay untuk bulan ini. Semua operasional berjalan lancar.' }}
                </p>
            </div>

            <section class="grid grid-cols-1 md:grid-cols-2 mb-10 gap-8">
                <div class="bg-surface-container-lowest p-8 rounded-3xl shadow-soft border border-outline-variant/20 group hover:border-primary/30 transition-all flex flex-col justify-between h-full">
                  <div class="flex justify-between items-start mb-8">
                    <div class="flex items-center justify-center w-16 h-16 bg-primary/5 rounded-2xl text-primary group-hover:scale-110 transition-transform duration-700">
                      <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">apartment</span>
                    </div>
                  </div>
                  <div class="space-y-1">
                    <p class="text-secondary text-xs font-medium uppercase tracking-widest">Total Properti</p>
                    <h3 class="text-5xl font-headline font-bold text-on-surface">{{ $totalProperties }}</h3>
                  </div>
                </div>

                <div onclick="switchTab('transaksi', document.querySelector('button[onclick*=\'transaksi\']'))" class="block bg-surface-container-lowest p-8 rounded-3xl shadow-soft border border-outline-variant/20 group hover:border-primary/30 hover:bg-surface-container-low transition-all flex flex-col justify-between h-full cursor-pointer">
                  <div class="flex justify-between items-start mb-8">
                    <div class="flex items-center justify-center w-16 h-16 bg-primary/5 rounded-2xl text-primary group-hover:scale-110 transition-transform duration-700">
                      <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">pending_actions</span>
                    </div>
                  </div>
                  <div class="space-y-1">
                    <p class="text-secondary text-xs font-medium uppercase tracking-widest">Transaksi Aktif</p>
                    <h3 class="text-5xl font-headline font-bold text-on-surface">{{ $activeBookings }}</h3>
                  </div>
                </div>
            </section>

            <!-- Property Management Grid -->
            <section class="space-y-6">
                <div class="flex justify-between items-end">
                    <div>
                        <h4 class="font-headline text-2xl text-on-surface">{{ auth()->user()->role === 'admin' ? 'Seluruh Properti' : 'Manajemen Properti' }}</h4>
                        <p class="text-sm text-secondary">{{ auth()->user()->role === 'admin' ? 'Daftar lengkap seluruh properti terdaftar di platform.' : 'Pantau status hunian dan kelola detail kos Anda.' }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($properties as $prop)
                        @php
                            $totalRooms = $prop->roomTypes->sum('total_rooms');
                            $occupied = $totalRooms - $prop->roomTypes->sum('available_rooms');
                            $percentage = $totalRooms > 0 ? round(($occupied / $totalRooms) * 100) : 0;
                        @endphp
                        <div class="bg-surface-container-lowest rounded-3xl overflow-hidden shadow-soft border border-outline-variant/20 flex flex-col sm:flex-row group transition-all p-6 gap-6 h-full">
                          <div class="w-full sm:w-40 h-40 relative overflow-hidden shrink-0 rounded-2xl">
                            @if($prop->main_image)
                                <img alt="{{ $prop->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="{{ asset('storage/' . $prop->main_image) }}">
                            @else
                                <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-outline">apartment</span>
                                </div>
                            @endif
                          </div>
                          <div class="flex flex-col flex-1 justify-between">
                            <div>
                              <h5 class="font-headline text-2xl text-on-surface mb-2">{{ $prop->name }}</h5>
                              <div class="flex items-center gap-4 mb-3">
                                <div class="flex-1 h-1 bg-surface-container rounded-full overflow-hidden">
                                  <div class="h-full bg-primary" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-primary whitespace-nowrap">{{ $percentage == 100 ? 'FULL' : $occupied.'/'.$totalRooms }} Terisi</span>
                              </div>
                              <div class="flex items-center gap-2 mb-4">
                                <span class="material-symbols-outlined text-primary text-sm">location_on</span>
                                <span class="text-[11px] text-secondary uppercase tracking-wider">{{ $prop->area }}</span>
                                <span class="px-2 py-0.5 bg-primary-container/10 text-primary text-[9px] font-bold rounded uppercase">Kos {{ $prop->type }}</span>
                              </div>
                              <div class="flex flex-wrap gap-2 mb-6">
                                @foreach($prop->facilities->take(3) as $facility)
                                <span class="px-3 py-1 bg-surface-container-high/50 border border-outline-variant/20 rounded-full text-[10px] text-secondary">{{ $facility->name }}</span>
                                @endforeach
                              </div>
                            </div>
                            <div class="flex gap-3 mt-auto">
                              <a href="{{ route('property.show', $prop->slug) }}" class="flex-1 py-2.5 rounded-xl border border-outline text-secondary text-xs font-bold hover:bg-surface-container transition-colors text-center">Lihat Detail</a>
                              <a href="{{ route('property.edit', $prop) }}" class="flex-1 py-2.5 rounded-xl bg-surface-container-high text-on-surface text-xs font-bold hover:bg-secondary-container transition-colors text-center flex items-center justify-center">Edit</a>
                              <form action="{{ route('property.destroy', $prop) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/mengarsipkan properti ini? Semua data histori sewa akan tetap tersimpan.')" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 rounded-xl bg-tertiary/10 text-tertiary hover:bg-tertiary hover:text-white transition-colors flex items-center justify-center" title="Hapus Properti">
                                  <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                    @empty
                    @endforelse

                    <a href="{{ route('property.create') }}" class="border-2 border-dashed border-outline-variant/60 rounded-3xl flex flex-col items-center justify-center p-8 text-center cursor-pointer hover:bg-surface-container transition-all group h-full flex">
                        <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-secondary text-3xl">add_home</span>
                        </div>
                        <p class="font-headline text-lg text-secondary">Mulai Listing Baru</p>
                        <p class="text-[11px] text-secondary/60 mt-1">Daftarkan properti Anda hari ini.</p>
                    </a>
                </div>
            </section>
        </section>

        <section id="transaksi" class="tab-content space-y-8 hidden animate-in fade-in duration-500">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Manajemen Transaksi</h1>
                <p class="text-secondary font-body mt-2">{{ auth()->user()->role === 'admin' ? 'Pantau seluruh aktivitas pembayaran dan pemesanan kos secara global.' : 'Pantau seluruh aktivitas pembayaran dan pemesanan kos Anda.' }}</p>
            </div>

            <!-- Financial Summary Cards -->
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

            <!-- Transaction Table/List -->
            <div class="bg-surface-container-lowest rounded-xl shadow-soft border border-outline-variant/20 overflow-hidden">
                <div class="px-8 py-6 border-b border-outline-variant/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="font-headline text-2xl font-semibold">Daftar Transaksi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-surface-container-low">
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Pelanggan</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Properti</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Tanggal</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Durasi</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Total Bayar</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Komisi OTA</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Pendapatan Bersih</th>
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
                                                {{ strtoupper(substr($booking->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-on-surface">{{ $booking->user->name }}</p>
                                                <p class="text-xs text-secondary">{{ $booking->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 font-medium text-secondary">{{ $prop->name ?? '-' }}</td>
                                    <td class="px-8 py-6 text-sm">{{ $booking->check_in_date->format('d M Y') }}</td>
                                    <td class="px-8 py-6 text-sm">{{ $booking->duration_months }} Bulan</td>
                                    <td class="px-8 py-6 font-semibold text-on-surface">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td class="px-8 py-6 text-sm text-tertiary">
                                        - Rp {{ number_format($booking->commission_fee, 0, ',', '.') }}
                                        @php
                                            $commissionRate = $booking->room_subtotal > 0 ? round(($booking->commission_fee / $booking->room_subtotal) * 100) : \App\Models\Setting::getValue('commission_rate', 5);
                                        @endphp
                                        ({{ $commissionRate }}%)
                                    </td>
                                    <td class="px-8 py-6 font-bold text-green-700">Rp {{ number_format($booking->net_owner_amount, 0, ',', '.') }}</td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">{{ $booking->status }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <a href="{{ route('booking.show', $booking) }}" class="text-primary hover:underline text-xs font-bold uppercase">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-8 py-16 text-center text-secondary">
                                        <span class="material-symbols-outlined text-4xl text-outline mb-2 block">receipt_long</span>
                                        <p class="font-bold">Belum ada transaksi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if($bookings->hasPages())
                <div class="px-8 py-4 border-t border-outline-variant/30">
                    {{ $bookings->links() }}
                </div>
                @endif
            </div>
        </section>

        <section id="settings" class="tab-content space-y-12 hidden animate-in fade-in duration-500">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Pengaturan Akun</h1>
                <p class="text-secondary font-body mt-2">Kelola informasi pribadi dan preferensi keamanan Anda.</p>
            </div>
            
            {{-- Alert Success & Error --}}
            @if(session('success'))
            <div class="p-4 rounded-xl bg-primary/5 border border-primary/20 text-primary flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
            @endif
            @if($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
                <ul class="list-disc list-inside text-sm font-bold space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4 animate-in fade-in duration-700 accordion-item">
                    <button type="button" class="w-full flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline/5 sahara-shadow group transition-all hover:bg-surface-container-high" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary" data-icon="person">person</span>
                            <h3 class="text-2xl font-headline">Informasi Pribadi</h3>
                        </div>
                        <span class="material-symbols-outlined text-secondary transition-transform duration-300 chevron">expand_more</span>
                    </button>
                    
                    <div class="bg-surface-container-low rounded-xl p-8 sahara-shadow border border-outline/5 flex flex-col md:flex-row gap-10">
                        <div class="flex flex-col items-center gap-4">
                            <div class="relative group">
                                <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-md">
                                    <img id="profile_photo_preview" alt="Profile photo" class="w-full h-full object-cover" src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=c2652a&color=fff' }}">
                                </div>
                                <button type="button" id="upload_photo_btn" class="absolute bottom-1 right-1 bg-primary text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform active:scale-95">
                                    <span class="material-symbols-outlined text-sm leading-none" data-icon="photo_camera">photo_camera</span>
                                </button>
                                <input type="file" name="profile_photo" id="profile_photo_input" class="hidden" accept="image/*">
                            </div>
                            <p class="text-xs text-secondary font-body text-center max-w-[120px]">JPG, PNG, WEBP. Maksimal 2MB.</p>
                        </div>
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Lengkap</label>
                                <input name="name" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="text" value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Username</label>
                                <input name="username" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="text" value="{{ old('username', auth()->user()->username) }}">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nomor WhatsApp</label>
                                <input name="no_whatsapp" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="tel" value="{{ old('no_whatsapp', auth()->user()->no_whatsapp) }}" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Email</label>
                                <div class="relative">
                                    <input name="email" id="profile_email_field" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-32" type="email" value="{{ old('email', auth()->user()->email) }}" required>
                                    @if(auth()->user()->email_verified_at)
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 bg-green-50 text-green-700 text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 border border-green-200">
                                            <span class="material-symbols-outlined text-xs" style="font-variation-settings: 'FILL' 1;">verified</span>
                                            VERIFIED
                                        </span>
                                    @else
                                        <button type="button" onclick="sendEmailOtpAndOpenModal()" class="absolute right-3 top-1/2 -translate-y-1/2 bg-amber-50 text-amber-700 hover:bg-amber-100 text-[10px] font-bold px-2 py-1.5 rounded flex items-center gap-1 border border-amber-200 transition-all active:scale-95">
                                            <span class="material-symbols-outlined text-xs" style="font-variation-settings: 'FILL' 0;">mail</span>
                                            VERIFIKASI
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Additional decorative/SPA dummy fields --}}
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Jenis Kelamin</label>
                                <select class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="space-y-2" style="transition: transform 0.2s;">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Tanggal Lahir</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="date">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Pekerjaan</label>
                                <select class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" onchange="const parent = this.closest('.grid'); parent.querySelectorAll('.job-conditional').forEach(el => el.classList.add('hidden')); if(this.value === 'Mahasiswa') parent.querySelector('.job-mahasiswa').classList.remove('hidden'); if(this.value === 'Karyawan') parent.querySelector('.job-karyawan').classList.remove('hidden'); if(this.value === 'Lainnya') parent.querySelector('.job-lainnya').classList.remove('hidden');">
                                    <option value="">Pilih Pekerjaan</option>
                                    <option value="Mahasiswa">Mahasiswa</option>
                                    <option value="Karyawan">Karyawan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="space-y-2 job-conditional job-mahasiswa hidden">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Kampus/Sekolah</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Contoh: Universitas Mataram" type="text">
                            </div>
                            <div class="space-y-2 job-conditional job-karyawan hidden">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Instansi</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Contoh: PT. Maju Bersama" type="text">
                            </div>
                            <div class="space-y-2 job-conditional job-lainnya hidden" style="transition: transform 0.2s;">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Deskripsi Pekerjaan</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Jelaskan pekerjaan Anda" type="text">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Kota Asal</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Contoh: Mataram" type="text">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Status Pendidikan Terakhir</label>
                                <select class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm">
                                    <option value="SMA/SMK">SMA/SMK</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="Sarjana">Sarjana</option>
                                    <option value="Pascasarjana">Pascasarjana</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nomor Darurat</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Nomor telepon keluarga/kerabat" type="tel">
                            </div>
                            
                            <div class="md:col-span-2 flex justify-end mt-4">
                                <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-body text-sm font-bold shadow-md hover:bg-primary-container hover:text-on-primary-container transition-all active:scale-95">Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 accordion-item">
                    <button type="button" class="w-full flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline/5 sahara-shadow group transition-all hover:bg-surface-container-high" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary" data-icon="lock">lock</span>
                            <h3 class="text-2xl font-headline">Keamanan &amp; Password</h3>
                        </div>
                        <span class="material-symbols-outlined text-secondary transition-transform duration-300 chevron">expand_more</span>
                    </button>
                    
                    <div class="hidden bg-surface-container-low rounded-xl p-8 sahara-shadow border border-outline/5 grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Password Saat Ini</label>
                                <div class="relative group">
                                    <input name="current_password" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-12 password-input" placeholder="••••••••" type="password">
                                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-primary p-1 focus:outline-none toggle-password" onclick="togglePasswordVisibility(this)" type="button">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Password Baru</label>
                                <div class="relative group">
                                    <input name="password" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-12 password-input" placeholder="••••••••" type="password">
                                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-primary p-1 focus:outline-none toggle-password" onclick="togglePasswordVisibility(this)" type="button">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Konfirmasi Password</label>
                                <div class="relative group">
                                    <input name="password_confirmation" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-12 password-input" placeholder="••••••••" type="password">
                                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-primary p-1 focus:outline-none toggle-password" onclick="togglePasswordVisibility(this)" type="button">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg font-body text-sm font-bold shadow-md hover:bg-primary-container hover:text-on-primary-container transition-all active:scale-95">Update Password</button>
                        </div>
                        <div class="flex flex-col justify-between p-6 bg-surface-container rounded-xl border border-outline-variant/30">
                            <div class="space-y-3">
                                <h4 class="font-headline text-xl">Two-Factor Authentication</h4>
                                <p class="text-sm text-secondary font-body leading-relaxed">Berikan perlindungan ekstra untuk akun Anda. Kode verifikasi akan dikirimkan ke nomor WhatsApp terdaftar.</p>
                            </div>
                            <div class="mt-8 flex items-center justify-between">
                                <span class="text-sm font-bold text-on-surface">Status: <span class="text-primary">Aktif</span></span>
                                <label class="switch">
                                    <input checked="" type="checkbox" disabled>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- Active Sessions -->
            <div class="space-y-4 accordion-item">
                <button class="w-full flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline/5 sahara-shadow group transition-all hover:bg-surface-container-high" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary" data-icon="devices">devices</span>
                        <h3 class="text-2xl font-headline">Sesi Aktif</h3>
                    </div>
                    <span class="material-symbols-outlined text-secondary transition-transform duration-300 chevron">expand_more</span>
                </button>
                <div class="hidden bg-surface-container-low rounded-xl overflow-hidden sahara-shadow border border-outline/5">
                    <div class="divide-y divide-outline/10">
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-surface-container-highest rounded-full flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined" data-icon="laptop_mac">laptop_mac</span>
                                </div>
                                <div>
                                    <p class="font-bold text-sm">Windows Laptop • Mataram, NTB</p>
                                    <p class="text-xs text-secondary">Browser Chrome • Sesi Saat Ini</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-primary px-3 py-1 bg-primary/5 rounded-lg border border-primary/20">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Danger Zone: Deactivation -->
            <div class="pt-8 border-t border-outline/10 accordion-item">
                <div class="mt-4 bg-tertiary/5 border border-tertiary/10 rounded-xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="space-y-2 text-center md:text-left">
                        <h4 class="font-headline text-xl text-tertiary">Nonaktifkan Akun</h4>
                        <p class="text-sm text-secondary font-body">Tindakan ini tidak dapat dibatalkan. Semua data properti dan riwayat sewa Anda akan dihapus permanen.</p>
                    </div>
                    <button class="bg-tertiary text-white px-8 py-3 rounded-lg font-body text-sm font-bold shadow-md hover:bg-tertiary-container transition-all active:scale-95 shrink-0">Deactivate Account</button>
                </div>
            </div>
        </section>
    </div>
</main>

<x-footer />

<script>
    // FUNGSI TAB SWITCHING
    function switchTab(tabId, clickedElement) {
        // Simpan tab aktif di localStorage untuk persistence
        localStorage.setItem('active_owner_tab', tabId);

        // 1. Sembunyikan semua tab content
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => {
            content.classList.add('hidden');
            content.classList.remove('block');
        });

        // 2. Tampilkan tab yang dipilih
        const selectedContent = document.getElementById(tabId);
        if (selectedContent) {
            selectedContent.classList.remove('hidden');
            selectedContent.classList.add('block');
        }

        // 3. Reset semua style tombol sidebar
        const navItems = document.querySelectorAll('#sidebar-nav .nav-item');
        navItems.forEach(item => {
            // Hapus style aktif
            item.classList.remove('bg-primary-container', 'text-on-primary-container', 'font-semibold', 'shadow-sm');
            // Tambahkan style non-aktif
            item.classList.add('text-secondary', 'hover:bg-surface-container-high', 'group');
            // Reset warna ikon
            const icon = item.querySelector('.material-symbols-outlined');
            if (icon) icon.classList.add('group-hover:text-primary');
        });

        // 4. Tambahkan style aktif ke tombol yang diklik
        if (clickedElement) {
            clickedElement.classList.remove('text-secondary', 'hover:bg-surface-container-high', 'group');
            clickedElement.classList.add('bg-primary-container', 'text-on-primary-container', 'font-semibold', 'shadow-sm');
            
            const clickedIcon = clickedElement.querySelector('.material-symbols-outlined');
            if (clickedIcon) clickedIcon.classList.remove('group-hover:text-primary');
        }
    }

    // Load active tab on page load
    document.addEventListener('DOMContentLoaded', () => {
        const sessionTab = "{{ session('active_tab') }}";
        const activeTab = sessionTab || localStorage.getItem('active_owner_tab') || 'dashboard';
        const tabButton = document.querySelector(`button[onclick*="switchTab('${activeTab}')"]`) || document.querySelector(`button[onclick*="switchTab('${activeTab}'"]`) || document.querySelector('button[onclick*="switchTab(\'dashboard\'"]');
        if (tabButton) {
            switchTab(activeTab, tabButton);
        }
    });

    // Profile photo upload trigger and preview
    document.getElementById('upload_photo_btn')?.addEventListener('click', () => {
        document.getElementById('profile_photo_input').click();
    });
    
    document.getElementById('profile_photo_input')?.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile_photo_preview').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Password Visibility Toggle Function
    function togglePasswordVisibility(button) {
        const container = button.closest('.relative');
        const input = container.querySelector('input');
        const icon = button.querySelector('.material-symbols-outlined');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    // Micro-interactions for form inputs
    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('scale-[1.01]');
            input.parentElement.style.transition = 'transform 0.2s ease';
        });
        input.addEventListener('blur', () => {
            input.parentElement.classList.remove('scale-[1.01]');
        });
    });

    // OTP Email Modal Logic
    window.sendEmailOtpAndOpenModal = function() {
        const emailField = document.getElementById('profile_email_field');
        const email = emailField ? emailField.value : '';
        
        // Disable button/input during request
        const verifyBtn = document.querySelector('button[onclick="sendEmailOtpAndOpenModal()"]');
        if (verifyBtn) {
            verifyBtn.setAttribute('disabled', 'true');
            verifyBtn.innerHTML = '<span class="material-symbols-outlined text-xs animate-spin" style="display: inline-block; animation: spin 1s linear infinite;">sync</span> Mengirim...';
        }

        // Send AJAX request
        fetch("{{ route('profile.send-email-otp') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const simulatedSpan = document.getElementById('simulated-email-otp');
                if (simulatedSpan && data.otp) {
                    simulatedSpan.textContent = data.otp;
                }
                openEmailOtpModal();
            } else {
                alert('Gagal mengirim OTP: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat mengirim kode OTP.');
        })
        .finally(() => {
            if (verifyBtn) {
                verifyBtn.removeAttribute('disabled');
                verifyBtn.innerHTML = '<span class="material-symbols-outlined text-xs">mail</span> VERIFIKASI';
            }
        });
    }

    window.openEmailOtpModal = function() {
        const modal = document.getElementById('email-otp-modal');
        if (modal) {
            // Clear inputs
            const fields = document.querySelectorAll('.email-otp-field');
            fields.forEach((field, index) => {
                field.value = '';
                if (index > 0) field.setAttribute('disabled', 'true');
            });
            fields[0].removeAttribute('disabled');
            fields[0].focus();
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('email-otp-modal-card').classList.remove('scale-95');
                document.getElementById('email-otp-modal-card').classList.add('scale-100');
            }, 50);
        }
    }

    window.closeEmailOtpModal = function() {
        const modal = document.getElementById('email-otp-modal');
        if (modal) {
            document.getElementById('email-otp-modal-card').classList.remove('scale-100');
            document.getElementById('email-otp-modal-card').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }
    }

    // Email OTP inputs behavior
    document.addEventListener('DOMContentLoaded', () => {
        const emailOtpFields = document.querySelectorAll('.email-otp-field');
        emailOtpFields.forEach((field, index) => {
            field.addEventListener('input', function(e) {
                const val = this.value;
                this.value = val.replace(/[^0-9]/g, '');
                
                if (this.value.length === 1 && index < emailOtpFields.length - 1) {
                    emailOtpFields[index + 1].removeAttribute('disabled');
                    emailOtpFields[index + 1].focus();
                }
                
                compileEmailOtp();
            });

            field.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                    emailOtpFields[index - 1].focus();
                    emailOtpFields[index].setAttribute('disabled', 'true');
                }
            });
        });
    });

    window.compileEmailOtp = function() {
        let compiled = '';
        const emailOtpFields = document.querySelectorAll('.email-otp-field');
        emailOtpFields.forEach(field => {
            compiled += field.value;
        });
        document.getElementById('compiled-email-otp').value = compiled;
    }
</script>

<!-- CSS Animation Keyframe for spin -->
<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<!-- MODAL VERIFIKASI OTP EMAIL -->
<div id="email-otp-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEmailOtpModal()"></div>
    
    <!-- Content Card -->
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-8 border border-outline-variant/30 z-10 scale-95 transition-all duration-300" id="email-otp-modal-card">
        <button onclick="closeEmailOtpModal()" class="absolute right-4 top-4 text-secondary hover:text-primary transition-colors" type="button">
            <span class="material-symbols-outlined">close</span>
        </button>
        
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-primary text-2xl">mail</span>
            </div>
            <h3 class="font-headline text-2xl font-bold text-on-surface">Verifikasi Email Anda</h3>
            <p class="font-body text-sm text-secondary mt-2">Masukkan 4 digit kode OTP yang kami kirimkan ke email Anda.</p>
            
        </div>
        
        <form action="{{ route('profile.verify-email') }}" method="POST" class="space-y-6">
            @csrf
            <div class="flex justify-center gap-3" id="email-otp-inputs">
                <input type="text" maxlength="1" class="email-otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required>
                <input type="text" maxlength="1" class="email-otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
                <input type="text" maxlength="1" class="email-otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
                <input type="text" maxlength="1" class="email-otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
            </div>
            
            <input type="hidden" name="otp" id="compiled-email-otp">
            
            <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-bold text-sm tracking-wider uppercase hover:bg-primary-container transition-all active:scale-[0.98]">
                Konfirmasi Kode
            </button>
        </form>
    </div>
</div>

</body>
</html>
