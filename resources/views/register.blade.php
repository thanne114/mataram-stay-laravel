<!DOCTYPE html><html class="light" lang="id" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Daftar - Mataram Stay</title>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Manrope:wght@200..800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "surface-tint": "#c2652a",
                    "surface-container-low": "#f6f0e8",
                    "surface-container": "#f2ece4",
                    "background": "#faf5ee",
                    "on-secondary-fixed-variant": "#504840",
                    "on-error-container": "#7a1a10",
                    "tertiary-fixed-dim": "#e8a0a0",
                    "inverse-primary": "#f0a878",
                    "on-surface-variant": "#605850",
                    "primary": "#c2652a",
                    "outline": "#9a9088",
                    "on-background": "#3a302a",
                    "surface-container-lowest": "#ffffff",
                    "secondary-fixed": "#eae2da",
                    "surface-bright": "#faf5ee",
                    "inverse-on-surface": "#faf5ee",
                    "error-container": "#fce4e0",
                    "secondary-fixed-dim": "#cec6be",
                    "tertiary-fixed": "#fce0e0",
                    "surface-dim": "#dcd6cc",
                    "on-tertiary-fixed-variant": "#6e3030",
                    "tertiary-container": "#d47070",
                    "primary-fixed": "#fbe8d8",
                    "on-primary": "#ffffff",
                    "primary-container": "#e08850",
                    "surface-container-highest": "#e6e0d6",
                    "on-tertiary-container": "#3a2020",
                    "secondary": "#78706a",
                    "on-secondary-fixed": "#2a2420",
                    "on-primary-fixed-variant": "#8a4518",
                    "surface-container-high": "#ece6dc",
                    "on-secondary-container": "#605850",
                    "surface": "#faf5ee",
                    "on-primary-fixed": "#401a08",
                    "outline-variant": "#d8d0c8",
                    "on-secondary": "#ffffff",
                    "inverse-surface": "#3a302a",
                    "on-tertiary": "#ffffff",
                    "on-surface": "#3a302a",
                    "on-tertiary-fixed": "#2e1515",
                    "on-primary-container": "#fbe8d8",
                    "on-error": "#ffffff",
                    "secondary-container": "#eae2da",
                    "surface-variant": "#ece6dc",
                    "error": "#c0392b",
                    "tertiary": "#8c3c3c",
                    "primary-fixed-dim": "#f0a878"
            },
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Manrope', sans-serif; }
        .font-headline { font-family: 'EB Garamond', serif; }
        
        /* Smooth transitions for roles */
        .role-card input:checked + label {
            border-color: #c2652a;
            background-color: #fbe8d8;
        }
    </style>
</head>
<body class="bg-surface-container-lowest text-on-surface min-h-screen flex flex-col overflow-x-hidden">
<x-navbar />
<main class="flex-grow flex items-center justify-center p-4 md:p-12 lg:p-20"><div class="bg-surface rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row w-full max-w-6xl min-h-[700px] border border-outline-variant/30">
<section class="hidden md:flex md:w-1/2 relative flex-col justify-end p-12">
<div class="absolute inset-0 z-0">
<img alt="Modern Luxury Property" class="w-full h-full object-cover grayscale-[0.1] brightness-[0.85]" src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=1000">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
</div>
<div class="relative z-10">
<h1 class="font-headline text-4xl text-white mb-4 leading-tight">Bergabunglah Bersama Mataram Stay.</h1>
<p class="text-white/80 text-base font-light tracking-wide">
                    Jadilah bagian dari ribuan pengguna Mataram Stay. Proses pendaftaran cepat, data pribadimu dijamin 100% aman.
                </p>
</div>
</section>
<section class="w-full md:w-1/2 bg-surface px-6 py-12 md:p-16 flex flex-col justify-center">
<div class="max-w-md w-full mx-auto">
<div class="mb-8">
<h2 class="font-headline text-3xl font-medium text-on-background tracking-tight mb-2">Daftar Akun Baru</h2>
<p class="text-on-surface-variant font-body text-sm">Bergabunglah dengan komunitas Mataram Stay sekarang.</p>
</div>

<form class="space-y-4" method="POST" action="/register">
    @csrf

    <div class="space-y-3 mb-6">
    <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Pilih Peran Anda</label>
    <div class="grid grid-cols-2 gap-4">
        <div class="role-card relative">
            <input class="sr-only" id="pencari" name="role" type="radio" value="seeker" {{ old('role') == 'seeker' ? 'checked' : '' }}>
            <label class="flex flex-col items-center p-4 border border-outline-variant rounded-xl cursor-pointer hover:border-primary transition-all duration-300" for="pencari">
                <span class="material-symbols-outlined text-primary mb-2">person_search</span>
                <span class="text-sm font-bold">Pencari Kos</span>
            </label>
        </div>
        <div class="role-card relative">
            <input class="sr-only" id="pemilik" name="role" type="radio" value="owner" {{ old('role') == 'owner' || !old('role') ? 'checked' : '' }}>
            <label class="flex flex-col items-center p-4 border border-outline-variant rounded-xl cursor-pointer hover:border-primary transition-all duration-300" for="pemilik">
                <span class="material-symbols-outlined text-primary mb-2">holiday_village</span>
                <span class="text-sm font-bold">Pemilik Kos</span>
            </label>
        </div>
    </div>
    @error('role') <p class="text-error text-xs font-bold">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-4">
        <div class="group">
            <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1 ml-1" for="username">Username</label>
            <input class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-outline/60" id="username" name="username" value="{{ old('username') }}" placeholder="contoh: mataramstay" type="text">
        </div>
        
        <div class="group">
            <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1 ml-1" for="name">Nama Lengkap</label>
            <input class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-outline/60" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" type="text" required>
            @error('name') <p class="text-error text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
        </div>

        <div class="group">
            <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1 ml-1" for="email">Email</label>
            <input class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-outline/60" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" type="email" required>
            @error('email') <p class="text-error text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
        </div>

        <div class="group">
            <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1 ml-1" for="whatsapp">Nomor WhatsApp</label>
            <input class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-outline/60" id="whatsapp" name="no_whatsapp" value="{{ old('no_whatsapp') }}" placeholder="contoh: 08123456789" type="tel" required>
            @error('no_whatsapp') <p class="text-error text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="group">
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1 ml-1" for="password">Kata Sandi</label>
                <div class="relative">
                    <input class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-outline/60 pr-12" id="password" name="password" placeholder="••••••••" type="password" required>
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors toggle-password" type="button" data-target="password">
                        <span class="material-symbols-outlined text-xl">visibility</span>
                    </button>
                </div>
                <p class="text-[11px] text-secondary/80 mt-1 ml-1 leading-relaxed">Gunakan minimal 8 karakter dengan kombinasi huruf besar, angka, dan simbol.</p>
                @error('password') <p class="text-error text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="group">
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1 ml-1" for="confirm_password">Konfirmasi</label>
                <div class="relative">
                    <input class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-outline/60 pr-12" id="confirm_password" name="password_confirmation" placeholder="••••••••" type="password" required>
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors toggle-password" type="button" data-target="confirm_password">
                        <span class="material-symbols-outlined text-xl">visibility</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Trust Badge (reCAPTCHA Mockup) --}}
    <div class="bg-surface-container-low border border-outline-variant/60 rounded-lg p-3 flex items-center justify-between sahara-shadow my-2 select-none">
        <div class="flex items-center gap-3">
            <div class="relative flex items-center">
                <input type="checkbox" id="mock-recaptcha" required class="w-6 h-6 rounded border-outline-variant text-primary focus:ring-primary focus:ring-offset-0 cursor-pointer">
            </div>
            <label for="mock-recaptcha" class="text-xs font-bold text-on-surface-variant cursor-pointer">Saya bukan robot</label>
        </div>
        <div class="flex flex-col items-center justify-center text-center">
            <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" alt="reCAPTCHA" class="w-6 h-6">
            <span class="text-[8px] text-secondary/70 mt-0.5 leading-none">reCAPTCHA</span>
            <span class="text-[7px] text-secondary/50 leading-none">Privasi - Persyaratan</span>
        </div>
    </div>

    <div class="pt-4 space-y-4">
        <button class="w-full bg-primary text-white py-3.5 rounded-lg font-bold text-sm tracking-widest uppercase shadow-lg shadow-primary/20 hover:bg-surface-tint active:scale-[0.98] transition-all" type="submit">
            Daftar Sekarang
        </button>

        <div class="relative flex items-center py-2">
            <div class="flex-grow border-t border-outline-variant/50"></div>
            <span class="flex-shrink mx-4 text-xs font-bold text-on-surface-variant uppercase tracking-widest">Atau</span>
            <div class="flex-grow border-t border-outline-variant/50"></div>
        </div>

        <a id="google-register-btn" href="/auth/google/redirect?role=owner" class="w-full flex items-center justify-center gap-3 bg-white border border-outline-variant py-3 rounded-lg text-sm font-bold text-on-surface-variant hover:bg-surface-container transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
            </svg>
            Daftar dengan Google
        </a>
    </div>

    <div class="text-center pt-6">
        <p class="text-sm text-on-surface-variant">
            Sudah punya akun? 
            <a class="text-primary font-bold hover:underline transition-all" href="/login">Masuk</a>
        </p>
    </div>
</form>
</div>
</section>
</div></main>
<x-footer />
<script>
        // Form submission loading interaction removed for backend reliability
        
        // Toggle password visibility functionality
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-target');
                const passInput = document.getElementById(targetId);
                const icon = btn.querySelector('span');
                
                if (passInput) {
                    const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passInput.setAttribute('type', type);
                    icon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
                }
            });
        });

        // Simple form validation visual cue
        const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], input[type="tel"]');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.querySelector('label').style.color = '#c2652a';
            });
            input.addEventListener('blur', () => {
                input.parentElement.querySelector('label').style.color = '';
            });
        });

        // Update Google OAuth Redirect Link with selected role
        const googleBtn = document.getElementById('google-register-btn');
        const roleRadios = document.querySelectorAll('input[name="role"]');
        
        function updateGoogleRedirectUrl() {
            const selectedRole = document.querySelector('input[name="role"]:checked')?.value || 'owner';
            if (googleBtn) {
                googleBtn.href = `/auth/google/redirect?role=${selectedRole}`;
            }
        }
        
        roleRadios.forEach(radio => radio.addEventListener('change', updateGoogleRedirectUrl));
        updateGoogleRedirectUrl();
    </script>
</body></html>