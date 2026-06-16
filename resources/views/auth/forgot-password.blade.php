<!DOCTYPE html><html class="light" lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Lupa Kata Sandi - Mataram Stay</title>
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
                        "outline": "#9a9088",
                        "primary": "#c2652a",
                        "surface-container-low": "#f6f0e8",
                        "surface-container-lowest": "#ffffff",
                        "surface-bright": "#faf5ee",
                        "on-surface": "#3a302a",
                        "surface-container": "#f2ece4",
                        "on-surface-variant": "#605850",
                        "error": "#c0392b",
                        "on-primary": "#ffffff",
                        "secondary": "#78706a",
                        "background": "#faf5ee",
                        "outline-variant": "#d8d0c8",
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
<main class="flex-grow flex items-center justify-center min-h-[calc(100vh-140px)] px-4 py-12">
    <div class="bg-surface rounded-xl shadow-lg border border-outline-variant/30 w-full max-w-md p-8 md:p-10">
        <div class="mb-8">
            <h2 class="font-headline text-3xl font-medium text-on-background tracking-tight mb-2">Lupa Kata Sandi</h2>
            <p class="text-on-surface-variant font-body text-sm">Masukkan alamat email terdaftar Anda, dan kami akan mengirimkan tautan untuk menyetel ulang kata sandi Anda.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm flex items-start gap-3">
                <span class="material-symbols-outlined text-emerald-600 mt-0.5">check_circle</span>
                <div>
                    <span class="font-bold">Berhasil!</span>
                    <p class="mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <form class="space-y-4" method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="group">
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1 ml-1" for="email">Alamat Email</label>
                <input class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-outline/60" id="email" name="email" placeholder="Masukkan email terdaftar Anda" type="email" value="{{ old('email') }}" required autofocus>
                
                @error('email')
                    <p class="text-error text-xs font-bold mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="pt-2 space-y-4">
                <button class="w-full bg-primary text-white py-3.5 rounded-lg font-bold text-sm tracking-widest uppercase shadow-lg shadow-primary/20 hover:bg-surface-tint active:scale-[0.98] transition-all" type="submit" id="btn-submit">Kirim Tautan Reset</button>
                
                <div class="text-center pt-4">
                    <a class="text-sm font-bold text-primary hover:underline transition-all flex items-center justify-center gap-1" href="{{ route('login') }}">
                        <span class="material-symbols-outlined text-lg">arrow_back</span>
                        Kembali ke Halaman Masuk
                    </a>
                </div>
            </div>
        </form>
    </div>
</main>
<x-footer />
<script>
    const submitBtn = document.getElementById('btn-submit');
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">◌</span> Mengirim...';
        });
    }
</script>
</body></html>
