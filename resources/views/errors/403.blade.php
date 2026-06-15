<!DOCTYPE html><html lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Akses Ditolak - Mataram Stay</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<script>
    tailwind.config = {
        theme: { extend: {
            "colors": { "background": "#faf5ee", "on-surface": "#3a302a", "primary": "#c2652a", "secondary": "#78706a" },
            "fontFamily": { "headline": ["EB Garamond", "serif"], "body": ["Manrope", "sans-serif"] }
        }}
    }
</script>
</head>
<body class="bg-background text-on-surface font-body antialiased min-h-screen flex items-center justify-center p-4" style="font-family: 'Manrope', sans-serif;">
<div class="text-center max-w-md mx-auto">
    <div class="w-24 h-24 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-8">
        <span class="material-symbols-outlined text-5xl text-red-500">lock</span>
    </div>
    <h1 class="font-headline text-6xl font-bold text-on-surface mb-4" style="font-family: 'EB Garamond', serif;">403</h1>
    <h2 class="font-headline text-2xl font-bold text-on-surface mb-4" style="font-family: 'EB Garamond', serif;">Akses Ditolak</h2>
    <p class="text-secondary leading-relaxed mb-8">Anda tidak memiliki izin untuk mengakses halaman ini. Silakan kembali ke halaman sebelumnya.</p>
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="/" class="bg-primary text-white px-8 py-3 rounded-lg font-bold text-sm hover:opacity-90 transition-all">Kembali ke Beranda</a>
        <button onclick="history.back()" class="border border-secondary/30 text-secondary px-8 py-3 rounded-lg font-bold text-sm hover:bg-secondary/5 transition-all">Kembali</button>
    </div>
</div>
</body></html>
