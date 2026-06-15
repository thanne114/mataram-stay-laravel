<!DOCTYPE html><html class="light" lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Masuk - Mataram Stay</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-fixed-dim": "#e8a0a0",
                        "outline": "#9a9088",
                        "on-secondary": "#ffffff",
                        "primary": "#c2652a",
                        "on-primary-container": "#fbe8d8",
                        "surface-container-low": "#f6f0e8",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed": "#2e1515",
                        "surface-bright": "#faf5ee",
                        "on-tertiary-container": "#3a2020",
                        "secondary-container": "#eae2da",
                        "primary-fixed-dim": "#f0a878",
                        "primary-fixed": "#fbe8d8",
                        "surface-container": "#f2ece4",
                        "primary-container": "#e08850",
                        "on-surface": "#3a302a",
                        "surface-container-highest": "#e6e0d6",
                        "tertiary": "#8c3c3c",
                        "on-surface-variant": "#605850",
                        "error-container": "#fce4e0",
                        "error": "#c0392b",
                        "secondary-fixed": "#eae2da",
                        "inverse-on-surface": "#faf5ee",
                        "inverse-primary": "#f0a878",
                        "surface-variant": "#ece6dc",
                        "inverse-surface": "#3a302a",
                        "on-secondary-fixed-variant": "#504840",
                        "on-primary": "#ffffff",
                        "on-error": "#ffffff",
                        "tertiary-fixed": "#fce0e0",
                        "tertiary-container": "#d47070",
                        "surface-tint": "#c2652a",
                        "on-primary-fixed-variant": "#8a4518",
                        "on-tertiary": "#ffffff",
                        "on-secondary-container": "#605850",
                        "secondary": "#78706a",
                        "on-primary-fixed": "#401a08",
                        "secondary-fixed-dim": "#cec6be",
                        "surface-dim": "#dcd6cc",
                        "surface": "#faf5ee",
                        "outline-variant": "#d8d0c8",
                        "on-secondary-fixed": "#2a2420",
                        "on-tertiary-fixed-variant": "#6e3030",
                        "background": "#faf5ee",
                        "surface-container-high": "#ece6dc",
                        "on-error-container": "#7a1a10",
                        "on-background": "#3a302a"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["EB Garamond"],
                        "display": ["EB Garamond"],
                        "body": ["Manrope"],
                        "label": ["Manrope"]
                    }
                }
            }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Manrope', sans-serif;
            background-color: #faf5ee;
        }
        h1, h2, h3 {
            font-family: 'EB Garamond', serif;
        }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col overflow-x-hidden">
<x-navbar />
<main class="flex-grow flex items-center justify-center min-h-screen"><div class="bg-surface rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row w-full max-w-6xl min-h-[700px] border border-outline-variant/30"><section class="hidden md:flex md:w-1/2 relative flex-col justify-end p-12"><div class="absolute inset-0 z-0"><img alt="Warm Interior" class="w-full h-full object-cover grayscale-[0.1] brightness-[0.85]" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAWHuZCwMVPrJhD1uHyhI5ql0uBJEB_Qe-IFoM4RgK5aVSRJBZuavenvKxf149QR8pdb35ZSAM1Lxc81hFPKAeYBhSTNA06cdNIX4m-AUXaj9W3ky2UmhUkDI79xXkFej9NDdvvGPF70ciiz1pMxgVMeTBdo1yLN9faKYmNSagkrxdfJJmBJg8LeEQcNFAfe5-43DXNqEcrDvo60hUWSqqZGnE40fubi-qM_G5OwlOvGFiosuJeISAn4lFPh5QX6jb9t4dbn1lQDbw"><div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div></div><div class="relative z-10"><h1 class="font-headline text-4xl text-white mb-4 leading-tight">Temukan kenyamanan layaknya di rumah sendiri.</h1><p class="text-white/80 text-base font-light tracking-wide">Koleksi pilihan hunian eksklusif di jantung kota Mataram dengan sentuhan hangat Sahara.</p></div></section><section class="w-full md:w-1/2 bg-surface px-6 py-12 md:p-16 flex flex-col justify-center"><div class="max-w-md w-full mx-auto"><div class="mb-8"><h2 class="font-headline text-3xl font-medium text-on-background tracking-tight mb-2">Selamat Datang Kembali</h2><p class="text-on-surface-variant font-body text-sm">Masuk ke akun Anda untuk melanjutkan pencarian hunian impian.</p></div>

<form class="space-y-4" method="POST" action="/login">
    @csrf
    
    <div class="space-y-4">
        <div class="group">
            <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1 ml-1" for="email">Email</label>
            <input class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-outline/60" id="email" name="email" placeholder="Masukkan email Anda" type="email" value="{{ old('email') }}" required autofocus>
            
            @error('email')
                <p class="text-error text-xs font-bold mt-1 ml-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="group">
            <div class="flex justify-between items-center mb-1">
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1" for="password">Kata Sandi</label>
                <a class="text-xs font-bold text-primary hover:underline" href="#">Lupa Kata Sandi?</a>
            </div>
            <div class="relative">
                <input class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-outline/60 pr-12" id="password" name="password" placeholder="••••••••" type="password" required>
                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" type="button" id="toggle-password">
                    <span class="material-symbols-outlined text-xl">visibility</span>
                </button>
            </div>
        </div>
        
        <div class="flex items-center ml-1">
            <input class="w-4 h-4 text-primary bg-surface-container-lowest border-outline-variant rounded focus:ring-primary" id="remember" type="checkbox">
            <label class="ml-2 text-sm font-body text-on-surface-variant" for="remember">Ingat saya</label>
        </div>
    </div>
    
    <div class="pt-4 space-y-4">
        <button class="w-full bg-primary text-white py-3.5 rounded-lg font-bold text-sm tracking-widest uppercase shadow-lg shadow-primary/20 hover:bg-surface-tint active:scale-[0.98] transition-all" type="submit" id="btn-submit">Masuk</button>
        
        <div class="relative flex items-center py-2">
            <div class="flex-grow border-t border-outline-variant/50"></div>
            <span class="flex-shrink mx-4 text-xs font-bold text-on-surface-variant uppercase tracking-widest">Atau</span>
            <div class="flex-grow border-t border-outline-variant/50"></div>
        </div>
        
        <a href="/auth/google/redirect" class="w-full flex items-center justify-center gap-3 bg-white border border-outline-variant py-3 rounded-lg text-sm font-bold text-on-surface-variant hover:bg-surface-container transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path><path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path></svg>
            Masuk dengan Google
        </a>
    </div>
    
    <div class="text-center pt-6">
        <p class="text-sm text-on-surface-variant">Belum punya akun? <a class="text-primary font-bold hover:underline transition-all" href="/register">Daftar Sekarang</a></p>
    </div>
</form>
</div></section></div></main>
<x-footer />
<script>
        // Subtle micro-interaction for the login button
        const loginBtn = document.getElementById('btn-submit');
        if (loginBtn) {
            loginBtn.addEventListener('click', function() {
                // Jangan cegah default behavior, biarkan form di-submit
                loginBtn.innerHTML = '<span class="inline-block animate-spin mr-2">◌</span> Memproses...';
            });
        }

        // Toggle password visibility
        const togglePass = document.getElementById('toggle-password');
        const passInput = document.getElementById('password');
        if (togglePass && passInput) {
            togglePass.addEventListener('click', () => {
                const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passInput.setAttribute('type', type);
                togglePass.children[0].textContent = type === 'password' ? 'visibility' : 'visibility_off';
            });
        }
    </script>
</body></html>