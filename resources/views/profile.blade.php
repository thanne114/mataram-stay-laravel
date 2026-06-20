<!DOCTYPE html>
<html class="light" lang="id" style="">
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Seeker Portal | Mataram Stay</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Manrope:wght@200..800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                "outline-variant": "#d8d0c8",
                "background": "#faf5ee",
                "surface": "#faf5ee",
                "on-surface": "#3a302a",
                "primary": "#c2652a",
                "on-primary": "#ffffff",
                "primary-container": "#e08850",
                "secondary": "#78706a",
                "surface-bright": "#faf5ee",
                "surface-variant": "#ece6dc",
                "on-background": "#3a302a",
                "tertiary": "#8c3c3c",
                "tertiary-fixed": "#fce0e0",
                "on-tertiary-fixed-variant": "#6e3030",
                "error": "#c0392b",
                "surface-container-low": "#f6f0e8",
                "surface-container": "#f2ece4",
                "surface-container-high": "#ece6dc",
                "surface-container-highest": "#e6e0d6",
                "outline": "#9a9088",
                "surface-container-lowest": "#ffffff"
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
@php
    $hasPendingTransaction = $bookings->where('status', 'Pending')->isNotEmpty();
@endphp

<body class="bg-background text-on-surface selection:bg-primary/20 flex flex-col min-h-screen">

<x-navbar />

<main class="max-w-screen-2xl mx-auto px-6 lg:px-12 py-10 lg:py-16 flex flex-col lg:flex-row gap-12 flex-grow w-full">
    
    <aside class="w-full lg:w-64 flex flex-col gap-2 shrink-0">
        <div class="mb-6 px-4">
            <h2 class="text-xl font-display font-semibold text-on-surface">Ruang Penyewa</h2>
            <p class="text-xs text-secondary font-body">Pusat kendali akun dan riwayat pesanan Anda.</p>
        </div>
        <nav class="flex flex-col gap-1" id="sidebar-nav">
            <a href="#" onclick="switchView('view-transaksi', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">receipt_long</span>
                Transaksi
                @if($hasPendingTransaction)
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse ml-1"></span>
                @endif
            </a>
            <a href="#" onclick="switchView('view-pesan', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">forum</span>
                Pesan
            </a>
            <a href="#" onclick="switchView('view-kossaya', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">apartment</span>
                Kos Saya
            </a>
            <a href="#" onclick="switchView('view-riwayatpengajuan', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">list_alt</span>
                Riwayat Pengajuan Sewa
            </a>
            <a href="#" onclick="switchView('view-riwayatkos', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">history</span>
                Riwayat Kos
            </a>
            <a href="#" onclick="switchView('view-verifikasi', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">verified_user</span>
                Verifikasi Akun
                @if(!auth()->user()->is_verified)
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse ml-1"></span>
                @endif
            </a>
            <a href="#" onclick="switchView('view-settings', this)" class="nav-link flex items-center gap-3 bg-primary-container text-on-primary-container rounded-lg px-4 py-3 font-semibold font-body text-sm shadow-sm group">
                <span class="material-symbols-outlined text-xl group-hover:text-on-primary-container">settings</span>
                Settings
            </a>
            <div class="mt-8 pt-4 border-t border-outline/10">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-tertiary hover:bg-tertiary/10 rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group w-full text-left">
                        <span class="material-symbols-outlined text-xl">logout</span>
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <section class="flex-1 max-w-4xl">

        @if(!auth()->user()->is_verified)
            @if(empty(auth()->user()->identity_photo))
                <div class="bg-orange-50 border border-orange-200 text-orange-800 p-4 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm mb-6 animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex items-start gap-3">
                        <span class="text-xl shrink-0">⚠️</span>
                        <p class="font-body text-sm leading-relaxed">
                            Perhatian: Anda belum mengunggah Kartu Identitas. Harap segera lengkapi verifikasi identitas Anda (KTP / SIM / Paspor) untuk dapat melakukan pengajuan sewa kos.
                        </p>
                    </div>
                    <button onclick="switchView('view-verifikasi', document.querySelector('a[onclick*=\'view-verifikasi\']'))" class="bg-primary text-white text-xs font-bold px-4 py-2 rounded-lg transition-all hover:bg-primary-container hover:text-on-primary-container active:scale-95 whitespace-nowrap self-end sm:self-center">
                        Verifikasi Sekarang
                    </button>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm mb-6 animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex items-start gap-3">
                        <span class="text-xl shrink-0">⏳</span>
                        <p class="font-body text-sm leading-relaxed">
                            Dokumen sedang ditinjau. Menunggu verifikasi dari Administrator.
                        </p>
                    </div>
                </div>
            @endif
        @endif

        {{-- 1. VIEW SETTINGS --}}
        <div id="view-settings" class="view-section space-y-12 block">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Pengaturan Akun</h1>
                <p class="text-secondary font-body mt-2">Kelola informasi pribadi dan preferensi keamanan Anda.</p>
            </div>

            {{-- Alert Success & Errors --}}
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

                {{-- Accordion 1: Informasi Pribadi --}}
                <div class="space-y-4 animate-in fade-in duration-700 accordion-item">
                    <button type="button" class="w-full flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline/5 sahara-shadow group transition-all hover:bg-surface-container-high" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">person</span>
                            <h3 class="text-2xl font-headline">Informasi Pribadi</h3>
                        </div>
                        <span class="material-symbols-outlined text-secondary transition-transform duration-300 chevron">expand_more</span>
                    </button>
                    
                    <div class="bg-surface-container-low rounded-xl p-8 sahara-shadow border border-outline/5 flex flex-col md:flex-row gap-10">
                        <div class="flex flex-col items-center gap-4 flex-shrink-0">
                            <div class="relative group">
                                <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-md">
                                    <img id="profile_photo_preview" alt="Profile avatar focus" class="w-full h-full object-cover" src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=c2652a&color=fff' }}"/>
                                </div>
                                <button type="button" id="upload_photo_btn" class="absolute bottom-1 right-1 bg-primary text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform active:scale-95">
                                    <span class="material-symbols-outlined text-sm leading-none">photo_camera</span>
                                </button>
                                <input type="file" name="profile_photo" id="profile_photo_input" class="hidden" accept="image/*">
                            </div>
                            <p class="text-xs text-secondary font-body text-center max-w-[120px]">JPG, PNG, WEBP. Maksimal 2MB.</p>
                        </div>
                        <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Lengkap</label>
                                <input name="name" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="text" value="{{ old('name', auth()->user()->name) }}" required/>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Username</label>
                                <input name="username" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="text" value="{{ old('username', auth()->user()->username) }}"/>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nomor WhatsApp</label>
                                <input name="no_whatsapp" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="tel" value="{{ old('no_whatsapp', auth()->user()->no_whatsapp) }}" required/>
                            </div>
                             <div class="space-y-2">
                                 <label class="text-xs font-bold uppercase tracking-wider text-secondary">Email</label>
                                 <div class="relative">
                                     <input name="email" id="profile_email_field" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-32" type="email" value="{{ old('email', auth()->user()->email) }}" required/>
                                     @if(auth()->user()->email_verified_at)
                                         <span class="absolute right-3 top-1/2 -translate-y-1/2 bg-green-50 text-green-700 text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 border border-green-200">
                                             <span class="material-symbols-outlined text-xs" style='font-variation-settings: "FILL" 1;'>verified</span>
                                             VERIFIED
                                         </span>
                                     @else
                                         <button type="button" onclick="sendEmailOtpAndOpenModal()" class="absolute right-3 top-1/2 -translate-y-1/2 bg-amber-50 text-amber-700 hover:bg-amber-100 text-[10px] font-bold px-2 py-1.5 rounded flex items-center gap-1 border border-amber-200 transition-all active:scale-95">
                                             <span class="material-symbols-outlined text-xs" style='font-variation-settings: "FILL" 0;'>mail</span>
                                             VERIFIKASI
                                         </button>
                                     @endif
                                 </div>
                             </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Jenis Kelamin</label>
                                <select class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="space-y-2" style="transition: transform 0.2s;">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Tanggal Lahir</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="date"/>
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
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Contoh: Universitas Mataram" type="text"/>
                            </div>
                            <div class="space-y-2 job-conditional job-karyawan hidden">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Instansi</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Contoh: PT. Maju Bersama" type="text"/>
                            </div>
                            <div class="space-y-2 job-conditional job-lainnya hidden" style="transition: transform 0.2s;">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Deskripsi Pekerjaan</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Jelaskan pekerjaan Anda" type="text"/>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Kota Asal</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Contoh: Mataram" type="text"/>
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
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Nomor telepon keluarga/kerabat" type="tel"/>
                            </div>
                            <div class="md:col-span-2 flex justify-end mt-4">
                                <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-body text-sm font-bold shadow-md hover:bg-primary-container hover:text-on-primary-container transition-all active:scale-95">Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </div>
                @if(auth()->user()->isOwner())
                {{-- Accordion: Informasi Rekening Pencairan --}}
                <div class="space-y-4 accordion-item mt-6">
                    <button type="button" class="w-full flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline/5 sahara-shadow group transition-all hover:bg-surface-container-high" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">account_balance</span>
                            <h3 class="text-2xl font-headline">Informasi Rekening Pencairan</h3>
                        </div>
                        <span class="material-symbols-outlined text-secondary transition-transform duration-300 chevron">expand_more</span>
                    </button>
                    
                    <div class="hidden bg-surface-container-low rounded-xl p-8 sahara-shadow border border-outline/5 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Bank</label>
                            <select name="bank_name" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm">
                                <option value="">Pilih Bank</option>
                                <option value="BCA" {{ old('bank_name', auth()->user()->bank_name) == 'BCA' ? 'selected' : '' }}>BCA</option>
                                <option value="Mandiri" {{ old('bank_name', auth()->user()->bank_name) == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                <option value="BNI" {{ old('bank_name', auth()->user()->bank_name) == 'BNI' ? 'selected' : '' }}>BNI</option>
                                <option value="BRI" {{ old('bank_name', auth()->user()->bank_name) == 'BRI' ? 'selected' : '' }}>BRI</option>
                                <option value="BSI" {{ old('bank_name', auth()->user()->bank_name) == 'BSI' ? 'selected' : '' }}>BSI</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nomor Rekening</label>
                            <input name="bank_account_number" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="text" placeholder="Contoh: 1234567890" value="{{ old('bank_account_number', auth()->user()->bank_account_number) }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Pemilik Rekening</label>
                            <input name="bank_account_name" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="text" placeholder="Nama sesuai buku tabungan" value="{{ old('bank_account_name', auth()->user()->bank_account_name) }}">
                        </div>
                    </div>
                </div>
                @endif

                {{-- Accordion 2: Keamanan & Password --}}
                <div class="space-y-4 accordion-item">
                    <button type="button" class="w-full flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline/5 sahara-shadow group transition-all hover:bg-surface-container-high" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">lock</span>
                            <h3 class="text-2xl font-headline">Keamanan & Password</h3>
                        </div>
                        <span class="material-symbols-outlined text-secondary transition-transform duration-300 chevron">expand_more</span>
                    </button>
                    <div class="hidden bg-surface-container-low rounded-xl p-8 sahara-shadow border border-outline/5 grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Password Saat Ini</label>
                                <div class="relative group">
                                    <input name="current_password" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-12 password-input" placeholder="••••••••" type="password"/>
                                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-primary p-1 focus:outline-none toggle-password" onclick="togglePasswordVisibility(this)" type="button">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Password Baru</label>
                                <div class="relative group">
                                    <input name="password" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-12 password-input" placeholder="••••••••" type="password"/>
                                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-primary p-1 focus:outline-none toggle-password" onclick="togglePasswordVisibility(this)" type="button">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Konfirmasi Password</label>
                                <div class="relative group">
                                    <input name="password_confirmation" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-12 password-input" placeholder="••••••••" type="password"/>
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
                                    <input checked="" type="checkbox" disabled/>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Accordion 3: Sesi Aktif --}}
            <div class="space-y-4 accordion-item">
                <button class="w-full flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline/5 sahara-shadow group transition-all hover:bg-surface-container-high" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">devices</span>
                        <h3 class="text-2xl font-headline">Sesi Aktif</h3>
                    </div>
                    <span class="material-symbols-outlined text-secondary transition-transform duration-300 chevron">expand_more</span>
                </button>
                <div class="hidden bg-surface-container-low rounded-xl overflow-hidden sahara-shadow border border-outline/5">
                    <div class="divide-y divide-outline/10">
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-surface-container-highest rounded-full flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">laptop_mac</span>
                                </div>
                                <div>
                                    <p class="font-bold text-sm">Windows Laptop • Mataram, NTB</p>
                                    <p class="text-xs text-secondary font-medium mt-0.5">Sesi Saat Ini • Chrome</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-primary px-3 py-1 bg-primary/5 rounded-lg border border-primary/20">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="pt-8 border-t border-outline/10 accordion-item">
                <div class="mt-4 bg-tertiary/5 border border-tertiary/10 rounded-xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="space-y-2 text-center md:text-left">
                        <h4 class="font-headline text-xl text-tertiary">Nonaktifkan Akun</h4>
                        <p class="text-sm text-secondary font-body">Tindakan ini tidak dapat dibatalkan. Semua data properti dan riwayat sewa Anda akan dihapus permanen.</p>
                    </div>
                    <button class="bg-tertiary text-white px-8 py-3 rounded-lg font-body text-sm font-bold shadow-md hover:bg-tertiary-container transition-all active:scale-95 shrink-0">Deactivate Account</button>
                </div>
            </div>
        </div>

        {{-- 2. VIEW TRANSAKSI --}}
        <div id="view-transaksi" class="view-section hidden space-y-8">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Transaksi</h1>
                <p class="text-secondary font-body mt-2">Daftar semua transaksi yang pernah Anda lakukan.</p>
            </div>

            @if($hasPendingTransaction)
                <div class="bg-orange-50 border border-orange-200 text-orange-800 p-4 rounded-xl flex items-start gap-3 shadow-sm mb-6 animate-in fade-in slide-in-from-top-4 duration-300">
                    <span class="text-xl shrink-0">⚠️</span>
                    <p class="font-body text-sm leading-relaxed">
                        Pengingat: Anda memiliki transaksi yang belum diselesaikan (Pending). Silakan cek detail pesanan dan segera lakukan pembayaran agar pengajuan sewa Anda tidak dibatalkan otomatis.
                    </p>
                </div>
            @endif
            
            <div class="space-y-6">
            @forelse($bookings as $booking)
                @php
                    $prop = $booking->roomType->property ?? null;
                    $statusColors = [
                        'Pending' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                        'Active' => 'bg-green-50 text-green-700 border-green-100',
                        'Completed' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'Cancelled' => 'bg-red-50 text-red-700 border-red-100',
                    ];
                @endphp
                <div class="bg-white rounded-2xl border border-outline-variant/60 p-6 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/40">
                            @if($prop && $prop->main_image)
                                <img alt="{{ $prop->name }}" class="w-full h-full object-cover" src="{{ asset('storage/' . $prop->main_image) }}">
                            @else
                                <div class="w-full h-full bg-surface-container-high flex items-center justify-center text-outline">
                                    <span class="material-symbols-outlined text-4xl">apartment</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-1 text-center md:text-left">
                            <span class="font-label text-xs font-bold text-primary uppercase tracking-widest">Pemesanan Kos</span>
                            <h2 class="font-headline text-2xl font-bold text-on-surface">{{ $prop->name ?? 'Properti' }}</h2>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$booking->status] ?? 'bg-gray-50 text-gray-700' }} border">
                                    {{ $booking->status }}
                                </span>
                                <span class="text-secondary text-sm font-label">• Check-in: {{ $booking->check_in_date->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('booking.show', $booking) }}" class="w-full md:w-auto bg-primary text-on-primary px-8 py-3.5 rounded-lg font-label font-bold hover:bg-primary-container transition-all shadow-sm text-center">
                        Lihat Detail
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-xl p-12 border border-outline/10 flex flex-col items-center justify-center text-center shadow-sm">
                    <span class="material-symbols-outlined text-[60px] text-surface-dim mb-4">receipt_long</span>
                    <h2 class="text-xl font-bold text-on-surface mb-2">Belum ada transaksi</h2>
                    <p class="text-secondary font-body">Silakan lakukan pemesanan kos untuk melihat riwayat transaksi Anda.</p>
                </div>
            @endforelse
            </div>
        </div>

        {{-- 3. VIEW KOS SAYA --}}
        <div id="view-kossaya" class="view-section hidden space-y-8">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Kos Saya</h1>
                <p class="text-secondary font-body mt-2">Daftar kos yang sedang Anda huni saat ini.</p>
            </div>
            
            @if($activeBooking && $activeBooking->status === 'Active')
                @php
                    $prop = $activeBooking->roomType->property ?? null;
                @endphp
                <div class="bg-white rounded-2xl border border-outline-variant/60 p-6 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/40">
                            @if($prop && $prop->main_image)
                                <img alt="{{ $prop->name }}" class="w-full h-full object-cover" src="{{ asset('storage/' . $prop->main_image) }}">
                            @else
                                <div class="w-full h-full bg-surface-container-high flex items-center justify-center text-outline">
                                    <span class="material-symbols-outlined text-4xl">apartment</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-1 text-center md:text-left">
                            <span class="font-label text-xs font-bold text-green-600 uppercase tracking-widest">Kos Aktif</span>
                            <h2 class="font-headline text-2xl font-bold text-on-surface">{{ $prop->name ?? 'Properti' }}</h2>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-1">
                                <span class="text-secondary text-sm font-label">Tipe Kamar: {{ $activeBooking->roomType->name }}</span>
                                <span class="text-secondary text-sm font-label">• Check-in: {{ $activeBooking->check_in_date->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('booking.show', $activeBooking) }}" class="w-full md:w-auto bg-primary text-on-primary px-8 py-3.5 rounded-lg font-label font-bold hover:bg-primary-container transition-all shadow-sm text-center">
                        Detail Kos
                    </a>
                </div>
            @else
                <div class="bg-white rounded-xl p-12 border border-outline/10 flex flex-col items-center justify-center text-center shadow-sm">
                    <span class="material-symbols-outlined text-[60px] text-surface-dim mb-4">apartment</span>
                    <h2 class="text-xl font-bold text-on-surface mb-2">Belum ada kos aktif</h2>
                    <p class="text-secondary font-body">Anda belum menyewa kos apapun saat ini.</p>
                </div>
            @endif
        </div>

        {{-- 4. VIEW RIWAYAT PENGAJUAN --}}
        <div id="view-riwayatpengajuan" class="view-section hidden space-y-8">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Riwayat Pengajuan Sewa</h1>
                <p class="text-secondary font-body mt-2">Pantau status pengajuan sewa kos Anda di sini.</p>
            </div>
            
            <div class="space-y-6">
            @forelse($pendingBookings as $booking)
                @php
                    $prop = $booking->roomType->property ?? null;
                @endphp
                <div class="bg-white rounded-2xl border border-outline-variant/60 p-6 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/40">
                            @if($prop && $prop->main_image)
                                <img alt="{{ $prop->name }}" class="w-full h-full object-cover" src="{{ asset('storage/' . $prop->main_image) }}">
                            @else
                                <div class="w-full h-full bg-surface-container-high flex items-center justify-center text-outline">
                                    <span class="material-symbols-outlined text-4xl">apartment</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-1 text-center md:text-left">
                            <span class="font-label text-xs font-bold text-yellow-600 uppercase tracking-widest">Menunggu Konfirmasi</span>
                            <h2 class="font-headline text-2xl font-bold text-on-surface">{{ $prop->name ?? 'Properti' }}</h2>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-1">
                                <span class="text-secondary text-sm font-label">Tipe Kamar: {{ $booking->roomType->name }}</span>
                                <span class="text-secondary text-sm font-label">• Diajukan: {{ $booking->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('booking.show', $booking) }}" class="w-full md:w-auto bg-primary text-on-primary px-8 py-3.5 rounded-lg font-label font-bold hover:bg-primary-container transition-all shadow-sm text-center">
                        Pantau Status
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-xl p-12 border border-outline/10 flex flex-col items-center justify-center text-center shadow-sm">
                    <span class="material-symbols-outlined text-[60px] text-surface-dim mb-4">list_alt</span>
                    <h2 class="text-xl font-bold text-on-surface mb-2">Tidak ada pengajuan</h2>
                    <p class="text-secondary font-body">Anda belum mengajukan sewa kos baru-baru ini.</p>
                </div>
            @endforelse
            </div>
        </div>

        {{-- 5. VIEW RIWAYAT KOS --}}
        <div id="view-riwayatkos" class="view-section hidden space-y-8">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Riwayat Kos</h1>
                <p class="text-secondary font-body mt-2">Daftar kos yang pernah Anda huni sebelumnya.</p>
            </div>
            
            <div class="space-y-6">
            @forelse($completedBookings as $booking)
                @php
                    $prop = $booking->roomType->property ?? null;
                @endphp
                <div class="bg-white rounded-2xl border border-outline-variant/60 p-6 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/40">
                            @if($prop && $prop->main_image)
                                <img alt="{{ $prop->name }}" class="w-full h-full object-cover" src="{{ asset('storage/' . $prop->main_image) }}">
                            @else
                                <div class="w-full h-full bg-surface-container-high flex items-center justify-center text-outline">
                                    <span class="material-symbols-outlined text-4xl">apartment</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-1 text-center md:text-left">
                            <span class="font-label text-xs font-bold text-secondary uppercase tracking-widest">Selesai Sewa</span>
                            <h2 class="font-headline text-2xl font-bold text-on-surface">{{ $prop->name ?? 'Properti' }}</h2>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-1">
                                <span class="text-secondary text-sm font-label">Tipe Kamar: {{ $booking->roomType->name }}</span>
                                <span class="text-secondary text-sm font-label">• Selesai: {{ $booking->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('booking.show', $booking) }}" class="w-full md:w-auto bg-primary text-on-primary px-8 py-3.5 rounded-lg font-label font-bold hover:bg-primary-container transition-all shadow-sm text-center">
                        Lihat Ulasan
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-xl py-16 px-8 border border-outline/10 flex flex-col items-center justify-center text-center shadow-sm mt-4">
                    <div class="relative w-64 h-48 mb-8 flex justify-center items-end bg-[#e8f7f0] rounded-[50%_50%_50%_50%_/_60%_60%_40%_40%] overflow-visible">
                         <div class="absolute bottom-6 left-6 text-[#a3e2c6]">
                             <svg width="40" height="50" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 21h22L12 2zm0 4l6 10h-12l6-10z"/></svg>
                         </div>
                         <div class="absolute bottom-4 right-8 text-[#a3e2c6]">
                             <svg width="30" height="40" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 21h22L12 2zm0 4l6 10h-12l6-10z"/></svg>
                         </div>
                         <div class="relative z-10 w-24 h-24 bg-white border-4 border-[#e2e8f0] rounded-sm mb-4">
                            <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-0 h-0 border-l-[55px] border-l-transparent border-r-[55px] border-r-transparent border-b-[40px] border-b-[#cbd5e1]"></div>
                            <div class="w-full h-full bg-[#f1f5f9] flex gap-2 p-2">
                                <div class="w-1/2 h-full bg-white border border-[#e2e8f0]"></div>
                                <div class="w-1/2 h-1/2 bg-white border border-[#e2e8f0]"></div>
                            </div>
                         </div>
                         <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-4 bg-primary text-white text-[10px] font-bold px-4 py-2 rounded shadow-md z-20 whitespace-nowrap">
                             BELUM ADA KOS
                             <div class="w-3 h-3 bg-primary transform rotate-45 absolute -bottom-1 left-1/2 -translate-x-1/2"></div>
                         </div>
                    </div>
                    <h2 class="text-2xl font-bold text-[#1e293b] mb-3">Belum Ada Kos</h2>
                    <p class="text-[#64748b] text-sm">Semua kos yang pernah kamu sewa di Mataram Stay<br>nantinya akan muncul di halaman ini.</p>
                </div>
            @endforelse
            </div>
        </div>

        {{-- 6. VIEW VERIFIKASI --}}
        <div id="view-verifikasi" class="view-section hidden space-y-6">
            <div class="bg-white border border-outline/10 rounded-xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#1e293b] border-b border-outline/10 pb-4 mb-4">Email and Phone</h2>
                
                <div class="mb-6">
                    <h3 class="text-primary font-medium text-sm mb-1">Mengapa Verifikasi Penting?</h3>
                    <p class="text-sm text-[#64748b]">Verifikasi bisa mencegah akun kamu diretas oleh orang lain. Karena untuk mengakses akun tetap membutuhkan kode verifikasi yang hanya diketahui oleh Anda.</p>
                </div>
                
                <div class="flex items-center justify-between py-4">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-primary text-3xl">mail</span>
                        <div>
                            <p class="font-medium text-[15px] text-[#1e293b]">Email</p>
                            <p class="text-sm text-[#94a3b8]">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <button class="bg-primary text-white px-6 py-2 rounded-md font-medium text-sm hover:bg-primary-container transition">Ubah</button>
                </div>
                
                <div class="flex items-center justify-between py-4 border-t border-outline/5">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-primary text-3xl">call</span>
                        <div>
                            <p class="font-medium text-[15px] text-[#1e293b]">Nomor Handphone</p>
                            <p class="text-sm text-[#94a3b8]">{{ auth()->user()->no_whatsapp ?? 'Belum verifikasi' }}</p>
                        </div>
                    </div>
                    @if(auth()->user()->isPhoneVerified())
                        <span class="flex items-center gap-1 text-green-600 bg-green-50 px-3 py-1.5 rounded-lg text-xs font-bold border border-green-200">
                            <span class="material-symbols-outlined text-sm">verified</span> Terverifikasi
                        </span>
                    @else
                        <button type="button" onclick="openOtpModal()" class="bg-primary text-white px-6 py-2 rounded-md font-medium text-sm hover:bg-primary-container transition">Verifikasi</button>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-outline/10 rounded-xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#1e293b] border-b border-outline/10 pb-4 mb-4">Verifikasi Identitas</h2>
                
                @if(auth()->user()->isIdentityVerified())
                    <div class="bg-green-50 border border-green-200 p-4 rounded-xl mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600 text-3xl">verified</span>
                        <div>
                            <p class="text-green-800 font-bold text-sm">Identitas Terverifikasi</p>
                            <p class="text-green-700 text-xs font-body">Selamat! Identitas Anda ({{ strtoupper(auth()->user()->identity_type) }}) telah berhasil diverifikasi oleh sistem.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6 mt-4">
                        <div>
                            <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Foto Identitas</p>
                            <img src="{{ route('profile.identity-photo', basename(auth()->user()->identity_photo)) }}" class="w-full h-48 object-cover rounded-lg border border-outline-variant/50 shadow-sm" alt="Foto Identitas">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Swafoto Identitas</p>
                            <img src="{{ route('profile.identity-photo', basename(auth()->user()->selfie_photo)) }}" class="w-full h-48 object-cover rounded-lg border border-outline-variant/50 shadow-sm" alt="Swafoto Identitas">
                        </div>
                    </div>
                @elseif(!empty(auth()->user()->identity_photo))
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl mb-6 flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
                        <span class="material-symbols-outlined text-blue-600 text-3xl">schedule</span>
                        <div>
                            <p class="text-blue-800 font-bold text-sm">Dokumen Sedang Ditinjau</p>
                            <p class="text-blue-700 text-xs font-body">Dokumen sedang ditinjau. Menunggu verifikasi dari Administrator.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6 mt-4">
                        <div>
                            <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Foto Identitas</p>
                            <img src="{{ route('profile.identity-photo', basename(auth()->user()->identity_photo)) }}" class="w-full h-48 object-cover rounded-lg border border-outline-variant/50 shadow-sm" alt="Foto Identitas">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Swafoto Identitas</p>
                            <img src="{{ route('profile.identity-photo', basename(auth()->user()->selfie_photo)) }}" class="w-full h-48 object-cover rounded-lg border border-outline-variant/50 shadow-sm" alt="Swafoto Identitas">
                        </div>
                    </div>
                @else
                    <form action="{{ route('profile.verify-identity') }}" method="POST" enctype="multipart/form-data" id="identity_form">
                        @csrf
                        <div class="bg-primary/5 border border-primary/10 p-4 rounded-md mb-6">
                            <p class="text-primary font-medium text-sm mb-1">Lengkapi datamu agar proses pengajuan sewa lebih cepat.</p>
                            <p class="text-primary/80 text-sm">Kami melindungi informasi dan penggunaan data diri para pengguna kami.</p>
                        </div>
                        
                        <div class="flex items-center gap-6 mb-8">
                            <span class="text-sm font-medium text-[#1e293b] w-32 font-bold">Jenis Identitas</span>
                            <div class="flex gap-8">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="identity_type" value="ktp" checked class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                                    <span class="text-sm text-[#475569]">eKTP</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="identity_type" value="sim" class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                                    <span class="text-sm text-[#475569]">SIM</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="identity_type" value="passport" class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                                    <span class="text-sm text-[#475569]">Passport</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6 mb-4">
                            <span class="text-sm font-medium text-[#1e293b] w-32 shrink-0 font-bold">Upload Foto Identitas</span>
                            <div class="flex gap-4 w-full">
                                <div onclick="document.getElementById('identity_photo_input').click()" class="border border-dashed border-[#cbd5e1] rounded-lg p-6 flex flex-col items-center justify-center w-1/2 text-center cursor-pointer hover:bg-gray-50 transition relative min-h-[140px]" id="identity_dropzone">
                                    <div id="identity_preview_container" class="hidden absolute inset-0 w-full h-full bg-white rounded-lg overflow-hidden">
                                        <img src="" id="identity_preview" class="w-full h-full object-cover">
                                    </div>
                                    <span class="material-symbols-outlined text-primary text-4xl mb-2">badge</span>
                                    <p class="text-primary font-medium text-sm">Kartu Identitas</p>
                                </div>
                                <input type="file" name="identity_photo" id="identity_photo_input" class="hidden" accept="image/*" required>
                                
                                <div onclick="document.getElementById('selfie_photo_input').click()" class="border border-dashed border-[#cbd5e1] rounded-lg p-6 flex flex-col items-center justify-center w-1/2 text-center cursor-pointer hover:bg-gray-50 transition relative min-h-[140px]" id="selfie_dropzone">
                                    <div id="selfie_preview_container" class="hidden absolute inset-0 w-full h-full bg-white rounded-lg overflow-hidden">
                                        <img src="" id="selfie_preview" class="w-full h-full object-cover">
                                    </div>
                                    <span class="material-symbols-outlined text-primary text-4xl mb-2">photo_camera</span>
                                    <p class="text-primary font-medium text-sm">Selfie dengan Kartu Identitas</p>
                                </div>
                                <input type="file" name="selfie_photo" id="selfie_photo_input" class="hidden" accept="image/*" required>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 text-error ml-0 md:ml-[150px] mb-6" id="verification_error_msg">
                            <span class="material-symbols-outlined text-base">info</span>
                            <span class="text-[13px]">Kamu belum mengunggah foto kartu identitas dan swafoto</span>
                        </div>
                        
                        <div class="ml-0 md:ml-[150px] space-y-4">
                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="checkbox" id="agree_verification" class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-[13px] text-[#64748b] leading-tight">Dengan melanjutkan, saya menjamin data yang diberikan adalah<br>benar dan menyetujui <a href="#" class="text-primary font-medium hover:underline">privacy policy</a></span>
                            </label>
                            <button type="submit" id="btn_submit_verification" class="bg-[#cbd5e1] text-white px-10 py-2.5 rounded-md font-medium text-sm cursor-not-allowed transition-all" disabled>Simpan</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- View Pesan (Obrolan) -->
        <div id="view-pesan" class="view-section hidden">
            <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant/30 overflow-hidden shadow-soft flex min-h-[550px] h-[600px]">
                
                <!-- Left Pane: Conversation List -->
                <div class="w-full md:w-80 lg:w-96 border-r border-outline-variant/30 flex flex-col bg-surface-container-lowest shrink-0">
                    <div class="p-4 border-b border-outline-variant/30">
                        <h3 class="font-headline text-xl font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">forum</span>
                            <span>Pesan Saya</span>
                        </h3>
                    </div>
                    <div class="flex-grow overflow-y-auto divide-y divide-outline-variant/20" id="seeker-chat-sidebar-list">
                        @forelse($conversations as $conv)
                            @php
                                $partner = $conv->partner;
                                $lastMsg = $conv->messages->first();
                                $isOwner = auth()->id() === $conv->owner_id;
                            @endphp
                            <button onclick="loadConversation({{ $conv->id }}, this)" 
                                    class="conversation-item w-full text-left flex items-center gap-3 p-4 hover:bg-surface-container transition-colors duration-200" 
                                    data-id="{{ $conv->id }}"
                                    data-partner-name="{{ $partner->name }}"
                                    data-partner-initial="{{ strtoupper(substr($partner->name, 0, 1)) }}"
                                    data-partner-role="{{ $isOwner ? 'Pemilik Kos' : 'Pencari Kos' }}"
                                    data-property-name="{{ $conv->property ? $conv->property->name : '' }}"
                                    data-property-area="{{ $conv->property ? $conv->property->area : '' }}"
                                    data-property-price="{{ $conv->property ? number_format($conv->property->lowest_price, 0, ',', '.') : '' }}"
                                    data-property-slug="{{ $conv->property ? $conv->property->slug : '' }}"
                                    data-property-image="{{ $conv->property && $conv->property->main_image ? asset('storage/' . $conv->property->main_image) : '' }}">
                                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-headline font-bold text-primary text-base shrink-0">
                                    {{ strtoupper(substr($partner->name, 0, 1)) }}
                                </div>
                                <div class="flex-grow min-w-0">
                                    <div class="flex justify-between items-baseline mb-1">
                                        <h4 class="font-bold text-xs text-on-surface truncate pr-2">{{ $partner->name }}</h4>
                                        <span class="text-[9px] text-secondary shrink-0 last-msg-time">
                                            {{ $lastMsg ? $lastMsg->created_at->diffForHumans(null, true) : $conv->created_at->diffForHumans(null, true) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1 mb-0.5">
                                        @if($isOwner)
                                            <span class="px-1.5 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed-variant text-[8px] font-bold rounded-full uppercase tracking-wider scale-90 origin-left">Pencari</span>
                                        @else
                                            <span class="px-1.5 py-0.5 bg-primary-fixed text-on-primary-fixed text-[8px] font-bold rounded-full uppercase tracking-wider scale-90 origin-left">Pemilik</span>
                                        @endif
                                        @if($conv->property)
                                            <span class="text-[9px] text-primary truncate max-w-[100px] font-medium">{{ $conv->property->name }}</span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-secondary truncate last-msg-body">
                                        @if($lastMsg)
                                            @if($lastMsg->sender_id === auth()->id())
                                                <span class="text-primary font-medium">Anda: </span>
                                            @endif
                                            {{ $lastMsg->body }}
                                        @else
                                            <span class="italic text-secondary/70">Mulai percakapan...</span>
                                        @endif
                                    </p>
                                </div>
                                @if($conv->unread_messages_count > 0)
                                    <div class="w-4 h-4 rounded-full bg-primary text-on-primary flex items-center justify-center text-[9px] font-bold shrink-0 unread-badge">
                                        {{ $conv->unread_messages_count }}
                                    </div>
                                @endif
                            </button>
                        @empty
                            <div class="p-8 text-center text-secondary">
                                <span class="material-symbols-outlined text-3xl mb-1 text-outline-variant">mail_outline</span>
                                <p class="text-xs font-medium">Belum ada obrolan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Right Pane: Active Chat Room -->
                @php
                    $activeConversationId = request('conversation_id');
                    $activeConversation = null;
                    if ($activeConversationId) {
                        $activeConversation = $conversations->firstWhere('id', $activeConversationId);
                    }
                    $activeMessages = $activeConversation ? $activeConversation->messages()->with('sender')->oldest()->get() : collect();
                    $hasProperty = $activeConversation && $activeConversation->property;
                @endphp
                <div class="flex flex-col flex-grow bg-surface-container-low" id="chat-room-container">
                    <!-- Initial Empty State -->
                    <div class="flex flex-col flex-grow items-center justify-center p-8 text-center {{ $activeConversation ? 'hidden' : '' }}" id="chat-empty-state">
                        <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center mb-4 border border-outline-variant/30">
                            <span class="material-symbols-outlined text-3xl text-primary" style="font-variation-settings: 'FILL' 1;">chat_bubble_outline</span>
                        </div>
                        <h3 class="font-headline text-xl font-bold text-on-surface mb-1">Mulai Obrolan</h3>
                        <p class="text-xs text-secondary max-w-xs leading-relaxed">
                            Pilih obrolan di sebelah kiri untuk berkirim pesan dengan pemilik atau pencari kos.
                        </p>
                    </div>

                    <!-- Dynamic Chat Area -->
                    <div class="{{ $activeConversation ? '' : 'hidden' }} flex flex-col h-full" id="chat-active-area">
                        <!-- Header -->
                        <div class="p-4 border-b border-outline-variant/30 bg-surface-container-lowest flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-primary-fixed flex items-center justify-center font-headline font-bold text-primary text-sm shrink-0" id="chat-header-avatar">
                                    {{ $activeConversation ? strtoupper(substr($activeConversation->partner->name, 0, 1)) : 'A' }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm text-on-surface truncate" id="chat-header-name">{{ $activeConversation ? $activeConversation->partner->name : 'Andi Owner' }}</h4>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="px-1.5 py-0.5 bg-primary-fixed text-on-primary-fixed text-[8px] font-bold rounded-full uppercase tracking-wider" id="chat-header-role">{{ $activeConversation ? (auth()->id() === $activeConversation->owner_id ? 'Pemilik Kos' : 'Pencari Kos') : 'Pemilik Kos' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Banner -->
                        <div class="px-4 py-2 bg-surface-container border-b border-outline-variant/30 flex items-center justify-between gap-4 {{ $hasProperty ? '' : 'hidden' }}" id="chat-property-banner">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-lg overflow-hidden shrink-0 bg-surface-container-high border border-outline-variant/20">
                                    @if($hasProperty && $activeConversation->property->main_image)
                                        <img src="{{ asset('storage/' . $activeConversation->property->main_image) }}" class="w-full h-full object-cover" id="chat-property-image">
                                        <div class="w-full h-full flex items-center justify-center bg-surface-container hidden" id="chat-property-placeholder">
                                            <span class="material-symbols-outlined text-base text-outline">apartment</span>
                                        </div>
                                    @else
                                        <img src="" class="w-full h-full object-cover hidden" id="chat-property-image">
                                        <div class="w-full h-full flex items-center justify-center bg-surface-container" id="chat-property-placeholder">
                                            <span class="material-symbols-outlined text-base text-outline">apartment</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 text-left">
                                    <p class="text-xs font-bold truncate text-on-surface" id="chat-property-name">{{ $hasProperty ? $activeConversation->property->name : 'Nama Kos' }}</p>
                                    <p class="text-[9px] text-secondary" id="chat-property-details">{{ $hasProperty ? $activeConversation->property->area . ' • Rp ' . number_format($activeConversation->property->lowest_price, 0, ',', '.') . '/bln' : 'Area • Rp 0/bln' }}</p>
                                </div>
                            </div>
                            <a href="{{ $hasProperty ? route('property.show', $activeConversation->property->slug) : '' }}" class="text-xs font-bold text-primary hover:text-primary-container shrink-0 flex items-center gap-0.5 transition-colors" id="chat-property-link">
                                <span>Detail Kos</span>
                                <span class="material-symbols-outlined text-xs">arrow_forward</span>
                            </a>
                        </div>

                        <!-- Messages Stream -->
                        <div id="tab-message-container" class="flex-grow overflow-y-auto p-4 space-y-4">
                            @if($activeConversation)
                                @forelse($activeMessages as $msg)
                                    @php
                                        $isSelf = $msg->sender_id === auth()->id();
                                    @endphp
                                    <div class="flex {{ $isSelf ? 'justify-end' : 'justify-start' }}">
                                        <div class="flex flex-col max-w-[75%] md:max-w-[65%] gap-1">
                                            <!-- Bubble -->
                                            <div class="p-3.5 rounded-2xl shadow-sm leading-relaxed text-sm {{ $isSelf ? 'bg-primary text-on-primary rounded-tr-none' : 'bg-surface-container-lowest text-on-surface rounded-tl-none border border-outline-variant/20' }}">
                                                <p class="whitespace-pre-wrap break-words">{{ $msg->body }}</p>
                                            </div>
                                            <!-- Timestamp and status -->
                                            <div class="flex items-center gap-1 text-[9px] text-secondary mt-0.5 {{ $isSelf ? 'justify-end' : 'justify-start' }}">
                                                <span>{{ $msg->created_at->format('H:i') }}</span>
                                                @if($isSelf)
                                                    @if($msg->is_read)
                                                        <span class="material-symbols-outlined text-xs text-primary font-bold">done_all</span>
                                                    @else
                                                        <span class="material-symbols-outlined text-xs text-secondary/60">done</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex items-center justify-center h-full py-12 text-secondary flex-col">
                                        <span class="material-symbols-outlined text-3xl mb-1 text-outline-variant">forum</span>
                                        <p class="text-xs font-bold">Mulai obrolan Anda</p>
                                    </div>
                                @endforelse
                            @endif
                        </div>

                        <!-- Form -->
                        <div class="p-4 border-t border-outline-variant/30 bg-surface-container-lowest">
                            <form id="chat-msg-form" onsubmit="sendMessage(event)" class="flex gap-3 items-end">
                                @csrf
                                <input type="hidden" id="active-conversation-id" value="{{ $activeConversation ? $activeConversation->id : '' }}">
                                <div class="flex-grow">
                                    <textarea 
                                        id="chat-msg-input"
                                        name="body" 
                                        rows="1" 
                                        placeholder="Tulis pesan..." 
                                        required
                                        class="w-full bg-surface-container border border-outline-variant/40 focus:border-primary focus:ring-1 focus:ring-primary rounded-2xl px-4 py-2 text-xs resize-none max-h-24 min-h-[38px] outline-none text-on-surface font-body"
                                        oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px';"
                                    ></textarea>
                                </div>
                                <button type="submit" class="bg-primary text-on-primary hover:bg-primary-container p-2.5 rounded-2xl font-bold flex items-center justify-center shrink-0 shadow-md transition-all active:scale-95">
                                    <span class="material-symbols-outlined text-sm">send</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<x-footer />

<script>
    // Logika untuk Pindah Tab Menu
    function switchView(viewId, clickedElement) {
        // Simpan tab aktif di localStorage untuk persistence
        localStorage.setItem('active_seeker_tab', viewId);

        // Hentikan polling chat jika pindah dari tab chat
        if (viewId !== 'view-pesan') {
            if (chatPollingInterval) {
                clearInterval(chatPollingInterval);
                chatPollingInterval = null;
            }
        }

        // Sembunyikan semua section view
        const views = document.querySelectorAll('.view-section');
        views.forEach(view => {
            view.classList.add('hidden');
            view.classList.remove('block');
        });
        
        // Tampilkan section yang dituju
        const targetView = document.getElementById(viewId);
        if(targetView) {
            targetView.classList.remove('hidden');
            targetView.classList.add('block');
        }

        // Hapus status aktif dari semua menu link
        const links = document.querySelectorAll('.nav-link');
        links.forEach(link => {
            // Reset ke default class (abu-abu, hover background dll)
            link.className = "nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group";
            // Reset icon color
            const icon = link.querySelector('span');
            if(icon) {
                icon.className = "material-symbols-outlined text-xl group-hover:text-primary";
                icon.style.fontVariationSettings = "'FILL' 0";
            }
        });

        // Berikan status aktif pada menu yang diklik
        if(clickedElement) {
            clickedElement.className = "nav-link flex items-center gap-3 bg-primary-container text-on-primary-container rounded-lg px-4 py-3 font-semibold font-body text-sm shadow-sm group";
            const activeIcon = clickedElement.querySelector('span');
            if(activeIcon) {
                activeIcon.className = "material-symbols-outlined text-xl group-hover:text-on-primary-container";
                activeIcon.style.fontVariationSettings = "'FILL' 1";
            }
        }
    }

    // Load active tab on page load
    document.addEventListener('DOMContentLoaded', () => {
        const sessionTab = "{{ session('active_tab') }}";
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || sessionTab || localStorage.getItem('active_seeker_tab') || 'view-settings';
        const tabButton = document.querySelector(`a[onclick*="${activeTab}"]`) || document.querySelector('a[onclick*="view-settings"]');
        if (tabButton) {
            switchView(activeTab, tabButton);
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

    // Toggle handling
    const toggles = document.querySelectorAll('.switch input');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const statusSpan = this.closest('.flex').querySelector('.text-primary, .text-secondary');
            if(statusSpan) {
                statusSpan.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                statusSpan.className = this.checked ? 'text-primary' : 'text-secondary';
            }
        });
    });

    // Verification Form Logic
    const identityInput = document.getElementById('identity_photo_input');
    const selfieInput = document.getElementById('selfie_photo_input');
    const agreeCheckbox = document.getElementById('agree_verification');
    const submitBtn = document.getElementById('btn_submit_verification');
    const errorMsg = document.getElementById('verification_error_msg');

    function checkVerificationForm() {
        if (!submitBtn) return;
        const hasIdentity = identityInput && identityInput.files && identityInput.files.length > 0;
        const hasSelfie = selfieInput && selfieInput.files && selfieInput.files.length > 0;
        const isAgreed = agreeCheckbox && agreeCheckbox.checked;

        if (hasIdentity && hasSelfie) {
            if (errorMsg) errorMsg.classList.add('hidden');
        } else {
            if (errorMsg) errorMsg.classList.remove('hidden');
        }

        if (hasIdentity && hasSelfie && isAgreed) {
            submitBtn.removeAttribute('disabled');
            submitBtn.className = "bg-primary text-white px-10 py-2.5 rounded-md font-medium text-sm hover:bg-primary-container transition shadow-md cursor-pointer";
        } else {
            submitBtn.setAttribute('disabled', 'true');
            submitBtn.className = "bg-[#cbd5e1] text-white px-10 py-2.5 rounded-md font-medium text-sm cursor-not-allowed transition-all";
        }
    }

    if (identityInput) {
        identityInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('identity_preview');
                    const container = document.getElementById('identity_preview_container');
                    if (preview && container) {
                        preview.src = e.target.result;
                        container.classList.remove('hidden');
                    }
                }
                reader.readAsDataURL(this.files[0]);
            }
            checkVerificationForm();
        });
    }

    if (selfieInput) {
        selfieInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('selfie_preview');
                    const container = document.getElementById('selfie_preview_container');
                    if (preview && container) {
                        preview.src = e.target.result;
                        container.classList.remove('hidden');
                    }
                }
                reader.readAsDataURL(this.files[0]);
            }
            checkVerificationForm();
        });
    }

    if (agreeCheckbox) {
        agreeCheckbox.addEventListener('change', checkVerificationForm);
    }

    // OTP Modal Logic (Phone verification)
    window.openOtpModal = function() {
        const modal = document.getElementById('otp-modal');
        if (modal) {
            // Clear inputs
            const fields = document.querySelectorAll('.otp-field');
            fields.forEach((field, index) => {
                field.value = '';
                if (index > 0) field.setAttribute('disabled', 'true');
            });
            fields[0].removeAttribute('disabled');
            fields[0].focus();
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('otp-modal-card').classList.remove('scale-95');
                document.getElementById('otp-modal-card').classList.add('scale-100');
            }, 50);
        }
    }

    window.closeOtpModal = function() {
        const modal = document.getElementById('otp-modal');
        if (modal) {
            document.getElementById('otp-modal-card').classList.remove('scale-100');
            document.getElementById('otp-modal-card').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }
    }

    // OTP inputs behavior
    document.addEventListener('DOMContentLoaded', () => {
        const otpFields = document.querySelectorAll('.otp-field');
        otpFields.forEach((field, index) => {
            field.addEventListener('input', function(e) {
                const val = this.value;
                // Clean non-digits
                this.value = val.replace(/[^0-9]/g, '');
                
                if (this.value.length === 1 && index < otpFields.length - 1) {
                    otpFields[index + 1].removeAttribute('disabled');
                    otpFields[index + 1].focus();
                }
                
                // Compile values
                compileOtp();
            });

            field.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                    otpFields[index - 1].focus();
                    otpFields[index].setAttribute('disabled', 'true');
                }
            });
        });

        // Email OTP inputs behavior
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

    window.compileOtp = function() {
        let compiled = '';
        const otpFields = document.querySelectorAll('.otp-field');
        otpFields.forEach(field => {
            compiled += field.value;
        });
        document.getElementById('compiled-otp').value = compiled;
    }

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

    window.compileEmailOtp = function() {
        let compiled = '';
        const emailOtpFields = document.querySelectorAll('.email-otp-field');
        emailOtpFields.forEach(field => {
            compiled += field.value;
        });
        document.getElementById('compiled-email-otp').value = compiled;
    }

    // AJAX CHAT SYSTEM INTEGRATION
    const currentAuthId = {{ auth()->id() }};
    let activeConversationId = null;
    let chatPollingInterval = null;

    window.loadConversation = async function(conversationId, element) {
        activeConversationId = conversationId;
        const activeConvInput = document.getElementById('active-conversation-id');
        if (activeConvInput) activeConvInput.value = conversationId;
        
        // Hentikan polling lama jika ada
        if (chatPollingInterval) {
            clearInterval(chatPollingInterval);
            chatPollingInterval = null;
        }

        // Cari element di sidebar jika tidak di-pass
        const targetElement = element || document.querySelector(`.conversation-item[data-id="${conversationId}"]`);
        
        // Highlight sidebar item
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.remove('bg-surface-container-high/60', 'border-l-4', 'border-primary');
        });
        if (targetElement) {
            targetElement.classList.add('bg-surface-container-high/60', 'border-l-4', 'border-primary');
            // Hide unread badge
            const badge = targetElement.querySelector('.unread-badge');
            if (badge) badge.remove();
        }
        
        // Update header & property banner dynamically using sidebar dataset
        if (targetElement) {
            const emptyState = document.getElementById('chat-empty-state');
            const activeArea = document.getElementById('chat-active-area');
            if (emptyState) emptyState.classList.add('hidden');
            if (activeArea) activeArea.classList.remove('hidden');

            const headerName = document.getElementById('chat-header-name');
            const headerAvatar = document.getElementById('chat-header-avatar');
            const headerRole = document.getElementById('chat-header-role');
            if (headerName) headerName.innerText = targetElement.dataset.partnerName || '';
            if (headerAvatar) headerAvatar.innerText = targetElement.dataset.partnerInitial || '';
            if (headerRole) headerRole.innerText = targetElement.dataset.partnerRole || '';

            const banner = document.getElementById('chat-property-banner');
            if (banner) {
                if (targetElement.dataset.propertyName) {
                    banner.classList.remove('hidden');
                    const propName = document.getElementById('chat-property-name');
                    const propDetails = document.getElementById('chat-property-details');
                    const propLink = document.getElementById('chat-property-link');
                    if (propName) propName.innerText = targetElement.dataset.propertyName;
                    if (propDetails) {
                        propDetails.innerText = `${targetElement.dataset.propertyArea} • Rp ${targetElement.dataset.propertyPrice}/bln`;
                    }
                    if (propLink) propLink.href = `/kos/${targetElement.dataset.propertySlug}`;

                    const img = document.getElementById('chat-property-image');
                    const placeholder = document.getElementById('chat-property-placeholder');
                    if (img && placeholder) {
                        if (targetElement.dataset.propertyImage) {
                            img.src = targetElement.dataset.propertyImage;
                            img.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        } else {
                            img.classList.add('hidden');
                            placeholder.classList.remove('hidden');
                        }
                    }
                } else {
                    banner.classList.add('hidden');
                }
            }
        }
        
        try {
            const response = await fetch(`/chat/${conversationId}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            if (!response.ok) throw new Error("Failed to load chat");
            
            const data = await response.json();
            console.log('Chat Data:', data);

            // Render Messages
            const msgContainer = document.getElementById('tab-message-container');
            if (msgContainer) {
                msgContainer.innerHTML = '';
                
                if (data.messages.length === 0) {
                    msgContainer.innerHTML = `
                        <div class="flex items-center justify-center h-full py-12 text-secondary flex-col">
                            <span class="material-symbols-outlined text-3xl mb-1 text-outline-variant">forum</span>
                            <p class="text-xs font-bold">Mulai obrolan Anda</p>
                        </div>
                    `;
                } else {
                    data.messages.forEach(msg => {
                        const bubble = createMessageBubble(msg);
                        msgContainer.appendChild(bubble);
                    });
                }
                
                msgContainer.scrollTop = msgContainer.scrollHeight;
            }
            
            // Mulai polling berkala setiap 5 detik
            chatPollingInterval = setInterval(async () => {
                if (activeConversationId !== conversationId) return;
                try {
                    const pollResponse = await fetch(`/chat/${conversationId}`, {
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    if (!pollResponse.ok) return;
                    const pollData = await pollResponse.json();
                    console.log('Poll Chat Data:', pollData);
                    
                    const pollMsgContainer = document.getElementById('tab-message-container');
                    if (pollMsgContainer && activeConversationId === conversationId) {
                        const currentBubbles = pollMsgContainer.querySelectorAll('.flex.justify-start, .flex.justify-end').length;
                        const hasPlaceholder = pollMsgContainer.querySelector('.py-12') !== null;
                        const currentCount = hasPlaceholder ? 0 : currentBubbles;
                        
                        if (pollData.messages.length > currentCount) {
                            pollMsgContainer.innerHTML = '';
                            pollData.messages.forEach(msg => {
                                const bubble = createMessageBubble(msg);
                                pollMsgContainer.appendChild(bubble);
                            });
                            pollMsgContainer.scrollTop = pollMsgContainer.scrollHeight;
                            
                            // Update sidebar preview
                            const sidebarItem = document.querySelector(`.conversation-item[data-id="${conversationId}"]`);
                            if (sidebarItem && pollData.messages.length > 0) {
                                const lastMsg = pollData.messages[pollData.messages.length - 1];
                                const bodyText = sidebarItem.querySelector('.last-msg-body');
                                const timeText = sidebarItem.querySelector('.last-msg-time');
                                if (bodyText) {
                                    if (lastMsg.sender_id === currentAuthId) {
                                        bodyText.innerHTML = `<span class="text-primary font-medium">Anda: </span>${lastMsg.body}`;
                                    } else {
                                        bodyText.innerText = lastMsg.body;
                                    }
                                }
                                if (timeText) {
                                    let lastMsgTime = '';
                                    if (lastMsg.created_at) {
                                        const date = new Date(lastMsg.created_at);
                                        const hours = String(date.getHours()).padStart(2, '0');
                                        const minutes = String(date.getMinutes()).padStart(2, '0');
                                        lastMsgTime = `${hours}:${minutes}`;
                                    }
                                    timeText.innerText = lastMsgTime || 'Barusan';
                                }
                                
                                const parent = document.getElementById('seeker-chat-sidebar-list');
                                if (parent) parent.insertBefore(sidebarItem, parent.firstChild);
                            }
                        }
                    }
                } catch (pollErr) {
                    console.error("Polling error:", pollErr);
                }
            }, 5000);
            
        } catch (err) {
            console.error("Error loading chat:", err);
        }
    }

    function createMessageBubble(msg) {
        const isSelf = msg.is_self !== undefined ? msg.is_self : (msg.sender_id === currentAuthId);
        const outerDiv = document.createElement('div');
        outerDiv.className = `flex ${isSelf ? 'justify-end' : 'justify-start'}`;
        
        const innerDiv = document.createElement('div');
        innerDiv.className = `flex flex-col max-w-[75%] md:max-w-[65%] gap-0.5`;
        
        const bubble = document.createElement('div');
        bubble.className = `p-3 rounded-2xl shadow-sm leading-relaxed text-xs ${isSelf ? 'bg-primary text-on-primary rounded-tr-none' : 'bg-surface-container-lowest text-on-surface rounded-tl-none border border-outline-variant/20'}`;
        
        const p = document.createElement('p');
        p.className = 'whitespace-pre-wrap break-words';
        p.innerText = msg.body;
        bubble.appendChild(p);
        
        const meta = document.createElement('div');
        meta.className = `flex items-center gap-1 text-[8px] text-secondary mt-0.5 ${isSelf ? 'justify-end' : 'justify-start'}`;
        
        const timeSpan = document.createElement('span');
        let timeStr = msg.time;
        if (!timeStr && msg.created_at) {
            const date = new Date(msg.created_at);
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            timeStr = `${hours}:${minutes}`;
        }
        timeSpan.innerText = timeStr || '';
        meta.appendChild(timeSpan);
        
        if (isSelf) {
            const checkIcon = document.createElement('span');
            checkIcon.className = 'material-symbols-outlined text-[10px] text-primary font-bold';
            checkIcon.innerText = msg.is_read ? 'done_all' : 'done';
            meta.appendChild(checkIcon);
        }
        
        innerDiv.appendChild(bubble);
        innerDiv.appendChild(meta);
        outerDiv.appendChild(innerDiv);
        return outerDiv;
    }

    window.sendMessage = async function(event) {
        event.preventDefault();
        const input = document.getElementById('chat-msg-input');
        const text = input.value.trim();
        if (!text || !activeConversationId) return;
        
        try {
            const response = await fetch(`/chat/${activeConversationId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ body: text })
            });
            
            if (!response.ok) throw new Error("Failed to send message");
            
            const data = await response.json();
            if (data.success) {
                input.value = '';
                input.style.height = 'auto';
                
                const msgContainer = document.getElementById('tab-message-container');
                if (msgContainer.querySelector('.py-12')) {
                    msgContainer.innerHTML = '';
                }
                
                const bubble = createMessageBubble(data.message);
                msgContainer.appendChild(bubble);
                msgContainer.scrollTop = msgContainer.scrollHeight;
                
                // Update last message in sidebar list
                const sidebarItem = document.querySelector(`.conversation-item[data-id="${activeConversationId}"]`);
                if (sidebarItem) {
                    const bodyText = sidebarItem.querySelector('.last-msg-body');
                    const timeText = sidebarItem.querySelector('.last-msg-time');
                    if (bodyText) bodyText.innerHTML = `<span class="text-primary font-medium">Anda: </span>${text}`;
                    if (timeText) timeText.innerText = 'Barusan';
                    
                    const parent = document.getElementById('seeker-chat-sidebar-list');
                    parent.insertBefore(sidebarItem, parent.firstChild);
                }
            }
        } catch (err) {
            console.error("Error sending message:", err);
        }
    }

    // Auto-open chat if conversation_id query parameter exists
    document.addEventListener('DOMContentLoaded', () => {
        // Scroll fallback messages to bottom
        const msgContainer = document.getElementById('tab-message-container');
        if (msgContainer) {
            msgContainer.scrollTop = msgContainer.scrollHeight;
        }
        
        const urlParams = new URLSearchParams(window.location.search);
        const conversationId = urlParams.get('conversation_id');
        if (conversationId) {
            const sidebarItem = document.querySelector(`.conversation-item[data-id="${conversationId}"]`);
            if (sidebarItem) {
                setTimeout(() => {
                    loadConversation(conversationId, sidebarItem);
                }, 100);
            }
        }
    });
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

<!-- MODAL VERIFIKASI OTP TELEPON -->
<div id="otp-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeOtpModal()"></div>
    
    <!-- Content Card -->
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-8 border border-outline-variant/30 z-10 scale-95 transition-all duration-300" id="otp-modal-card">
        <button onclick="closeOtpModal()" class="absolute right-4 top-4 text-secondary hover:text-primary transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
        
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-primary text-2xl">sms</span>
            </div>
            <h3 class="font-headline text-2xl font-bold text-on-surface">Verifikasi Nomor Handphone</h3>
            <p class="font-body text-sm text-secondary mt-2">Masukkan 4 digit kode OTP yang kami kirimkan ke nomor WhatsApp Anda.</p>
            

        </div>
        
        <form action="{{ route('profile.verify-phone') }}" method="POST" class="space-y-6">
            @csrf
            <div class="flex justify-center gap-3" id="otp-inputs">
                <input type="text" maxlength="1" class="otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required>
                <input type="text" maxlength="1" class="otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
                <input type="text" maxlength="1" class="otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
                <input type="text" maxlength="1" class="otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
            </div>
            
            <input type="hidden" name="otp" id="compiled-otp">
            
            <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-bold text-sm tracking-wider uppercase hover:bg-primary-container transition-all active:scale-[0.98]">
                Konfirmasi Kode
            </button>
        </form>
    </div>
</div>

</body>
</html>