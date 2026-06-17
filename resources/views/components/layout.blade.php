<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $title ?? 'Mataram Stay - Cari Kos & Kontrakan Terbaik di Mataram' }}</title>
    <meta name="description" content="{{ $meta_description ?? 'Cari kos dan kontrakan murah terdekat di Mataram dengan mudah dan aman.' }}">
    
    <!-- Script Tailwind & Fonts -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Konfigurasi Warna Tailwind -->
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
                        "error": "#c0392b"
                    },
                    "fontFamily": {
                        "headline": ["EB Garamond", "serif"],
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
    </style>
</head>
<body class="bg-background text-on-surface font-body antialiased min-h-screen flex flex-col">

    <!-- Memanggil file navbar.blade.php -->
    <x-navbar />

    <!-- Konten dari welcome.blade.php akan masuk ke sini -->
    {{ $slot }}

    <!-- Memanggil file footer.blade.php -->
    <x-footer />

</body>
</html>