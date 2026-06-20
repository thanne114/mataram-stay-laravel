<!DOCTYPE html><html lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>{{ $title ?? ($property->name . ' - Mataram Stay') }}</title>
<meta name="description" content="{{ $meta_description ?? Str::limit(strip_tags($property->description), 150, '') }}">
<link rel="canonical" href="{{ url('/kos/' . $property->slug) }}">

<!-- Open Graph / Facebook / WhatsApp -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $title ?? ($property->name . ' - Mataram Stay') }}">
<meta property="og:description" content="{{ $meta_description ?? Str::limit(strip_tags($property->description), 150, '') }}">
@if($property->main_image)
<meta property="og:image" content="{{ asset('storage/' . $property->main_image) }}">
@endif

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title ?? ($property->name . ' - Mataram Stay') }}">
<meta name="twitter:description" content="{{ $meta_description ?? Str::limit(strip_tags($property->description), 150, '') }}">
@if($property->main_image)
<meta name="twitter:image" content="{{ asset('storage/' . $property->main_image) }}">
@endif
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                "colors": {
                    "outline-variant": "#d8d0c8", "background": "#faf5ee", "surface": "#faf5ee",
                    "on-surface": "#3a302a", "primary": "#c2652a", "on-primary": "#ffffff",
                    "primary-container": "#e08850", "secondary": "#78706a", "surface-bright": "#faf5ee",
                    "surface-variant": "#ece6dc", "on-background": "#3a302a", "tertiary": "#8c3c3c",
                    "tertiary-fixed": "#fce0e0", "on-tertiary-fixed-variant": "#6e3030",
                    "error": "#c0392b", "surface-container-lowest": "#ffffff",
                    "surface-container-low": "#f6f0e8", "surface-container": "#f2ece4",
                    "surface-container-high": "#ece6dc", "on-surface-variant": "#605850",
                    "primary-fixed": "#fbe8d8", "on-primary-fixed": "#401a08",
                    "secondary-container": "#eae2da", "outline": "#9a9088",
                    "on-secondary": "#ffffff", "primary-fixed-dim": "#f0a878",
                    "on-primary-container": "#fbe8d8",
                    "inverse-surface": "#3a302a", "inverse-on-surface": "#faf5ee"
                },
                "fontFamily": {
                    "headline": ["EB Garamond", "serif"],
                    "body": ["Manrope", "sans-serif"],
                    "label": ["Manrope", "sans-serif"]
                }
            }
        }
    }
</script>
<style>
    .font-headline { font-family: 'EB Garamond', serif; }
    .font-body { font-family: 'Manrope', sans-serif; }
    #map-detail { z-index: 10; }
</style>
</head>
<body class="bg-background text-on-surface font-body antialiased min-h-screen flex flex-col">
<x-navbar />

<main class="flex-grow max-w-7xl mx-auto w-full px-4 md:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-secondary mb-6">
        <a href="/" class="hover:text-primary transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('search') }}" class="hover:text-primary transition-colors">Cari Kos</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="text-on-surface font-bold">{{ $property->name }}</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2 flex-wrap">
                <span class="px-3 py-1 bg-inverse-surface text-inverse-on-surface rounded-full text-[10px] font-label font-bold tracking-widest uppercase">{{ $property->type }}</span>
                <span class="px-3 py-1 bg-primary-fixed text-on-primary-fixed rounded-full text-[10px] font-label font-bold tracking-widest uppercase">{{ $property->area }}</span>
                <span class="px-3 py-1 bg-red-50 text-red-700 border border-red-200 rounded-full text-[10px] font-label font-bold tracking-widest uppercase flex items-center gap-1">
                    <span class="material-symbols-outlined text-[13px] animate-pulse">local_fire_department</span>
                    Dilihat {{ $property->views_count }} kali dalam 24 jam terakhir
                </span>
            </div>
            <h1 class="font-headline text-4xl md:text-5xl font-bold text-on-surface">{{ $property->name }}</h1>
            <div class="flex items-center gap-2 mt-2 text-secondary">
                <span class="material-symbols-outlined text-sm">location_on</span>
                <span class="text-sm">{{ $property->address }}</span>
            </div>
            @if($property->closest_campus)
            <div class="flex items-center gap-2 mt-2 text-primary font-bold text-sm">
                <span class="material-symbols-outlined text-base">school</span>
                <span>{{ $property->closest_campus['label'] }}</span>
            </div>
            @endif
        </div>
        @if($property->average_rating)
        <div class="flex items-center gap-2 bg-surface-container-lowest px-4 py-3 rounded-xl border border-outline-variant/30 shrink-0">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">star</span>
            <span class="font-headline text-2xl font-bold text-on-surface">{{ $property->average_rating }}</span>
            <span class="text-xs text-secondary">({{ $property->reviews->count() }} ulasan)</span>
        </div>
        @endif
    </div>

    {{-- Galeri Foto --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12 rounded-2xl overflow-hidden">
        <div class="aspect-[4/3] overflow-hidden">
            @if($property->main_image)
                <img src="{{ asset('storage/' . $property->main_image) }}" alt="Foto utama {{ $property->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-outline">apartment</span>
                </div>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-4">
            @forelse($property->images->take(4) as $image)
                <div class="aspect-[4/3] overflow-hidden rounded-lg">
                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Foto fasilitas {{ $property->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
            @empty
                @for($i = 0; $i < 4; $i++)
                <div class="aspect-[4/3] bg-surface-container-high rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-outline">image</span>
                </div>
                @endfor
            @endforelse
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        {{-- Konten Utama (2/3) --}}
        <div class="lg:col-span-2 space-y-10">
            {{-- Deskripsi --}}
            <section>
                <h2 class="font-headline text-2xl font-bold text-on-surface mb-4">Tentang Kos Ini</h2>
                <p class="text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $property->description ?? 'Belum ada deskripsi untuk properti ini.' }}</p>
            </section>

            {{-- Fasilitas --}}
            @if($property->facilities->count() > 0)
            <section>
                <h2 class="font-headline text-2xl font-bold text-on-surface mb-4">Fasilitas</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($property->facilities as $facility)
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/30">
                        <span class="material-symbols-outlined text-primary text-xl">{{ $facility->icon ?? 'check_circle' }}</span>
                        <span class="text-sm font-medium">{{ $facility->name }}</span>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Tipe Kamar --}}
            <section>
                <h2 class="font-headline text-2xl font-bold text-on-surface mb-4">Tipe Kamar & Harga</h2>
                <div class="space-y-4">
                    @foreach($property->roomTypes as $roomType)
                    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                            <div>
                                <h3 class="font-headline text-xl font-bold text-on-surface">{{ $roomType->name }}</h3>
                                @if($roomType->description)
                                <p class="text-sm text-secondary mt-1">{{ $roomType->description }}</p>
                                @endif
                                <div class="flex items-center gap-4 mt-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $roomType->available_rooms > 0 ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                                        {{ $roomType->available_rooms > 0 ? $roomType->available_rooms . ' kamar tersedia' : 'PENUH' }}
                                    </span>
                                    <span class="text-xs text-secondary">{{ $roomType->total_rooms }} total kamar</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-label font-bold text-2xl text-primary">Rp {{ number_format($roomType->price_per_month, 0, ',', '.') }}</div>
                                <span class="text-xs text-secondary">/ bulan</span>
                                @if(auth()->check() && auth()->user()->is_verified)
                                    @if(auth()->user()->isSeeker() && $roomType->available_rooms > 0)
                                    <a href="{{ route('booking.create', ['room_type_id' => $roomType->id]) }}" class="mt-3 block bg-primary text-on-primary px-6 py-2.5 rounded-lg font-label font-bold text-sm hover:bg-primary-container transition-all text-center">
                                        Pesan Sekarang
                                    </a>
                                    @endif
                                @else
                                    @if($roomType->available_rooms > 0)
                                    <a href="{{ route('profile.edit', ['tab' => 'view-verifikasi']) }}" class="mt-3 block bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-lg font-label font-bold text-sm transition-all text-center flex items-center justify-center gap-2">
                                        <span>🔒</span> Verifikasi Identitas untuk Menyewa
                                    </a>
                                    <p class="mt-2 text-xs text-gray-500 leading-normal">
                                        Demi keamanan bersama, Mataram Stay mewajibkan penyewa melengkapi identitas resmi sebelum melakukan pengajuan sewa.
                                    </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- Peta Lokasi --}}
            @if($property->latitude && $property->longitude)
            <section>
                <h2 class="font-headline text-2xl font-bold text-on-surface mb-4">Lokasi</h2>
                <div id="map-detail" class="w-full h-[350px] rounded-xl border border-outline-variant/40"></div>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude }},{{ $property->longitude }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-surface-container-lowest border border-outline-variant text-on-surface font-label font-bold text-sm rounded-lg hover:bg-surface-variant hover:text-primary transition-all duration-300">
                        <span class="material-symbols-outlined text-lg text-primary">directions</span>
                        Dapatkan Rute di Google Maps
                    </a>
                </div>

                @if(count($property->nearby_campuses) > 0)
                <div class="mt-6 bg-surface-container-low rounded-xl p-5 border border-outline-variant/30">
                    <h3 class="font-headline text-lg font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">school</span>
                        Akses Kampus Hub Terdekat (Radius 3 KM)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($property->nearby_campuses as $nc)
                        <div class="flex items-center justify-between text-sm p-3 bg-surface-container-lowest rounded-lg border border-outline-variant/20 hover:border-primary/40 transition-colors">
                            <span class="font-semibold text-on-surface-variant text-xs truncate max-w-[200px]" title="{{ $nc['name'] }}">{{ $nc['name'] }}</span>
                            <span class="text-[10px] font-bold text-primary bg-primary-fixed/40 px-2 py-1 rounded-full whitespace-nowrap">{{ $nc['label'] }} ({{ $nc['distance'] }} km)</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </section>
            @endif

            {{-- Ulasan --}}
            <section id="ulasan">
                <h2 class="font-headline text-2xl font-bold text-on-surface mb-4">Ulasan & Rating</h2>
                
                @if($property->reviews->count() > 0)
                <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 mb-6 flex flex-col sm:flex-row items-center gap-6">
                    <div class="text-center sm:border-r border-outline-variant/30 sm:pr-8 shrink-0 w-full sm:w-auto">
                        <p class="text-5xl font-headline font-bold text-on-surface">{{ $property->average_rating }}</p>
                        <div class="flex justify-center gap-0.5 my-2">
                            @php $avgRating = round($property->average_rating); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-lg {{ $i <= $avgRating ? 'text-primary' : 'text-outline-variant' }}" style="font-variation-settings: 'FILL' 1;">star</span>
                            @endfor
                        </div>
                        <p class="text-xs text-secondary">{{ $property->reviews->count() }} Ulasan</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm mb-1 text-on-surface">Penilaian dari Penyewa</h4>
                        <p class="text-sm text-secondary leading-relaxed">Rata-rata ulasan yang diberikan oleh penyewa sebelumnya yang telah menyelesaikan masa tinggal mereka di kos ini.</p>
                    </div>
                </div>
                @endif

                @forelse($property->reviews as $review)
                <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-sm">{{ $review->user->name }}</p>
                                <p class="text-xs text-secondary">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-sm {{ $i <= $review->rating ? 'text-primary' : 'text-outline-variant' }}" style="font-variation-settings: 'FILL' 1;">star</span>
                            @endfor
                        </div>
                    </div>
                    @if($review->comment)
                    <p class="text-sm text-on-surface-variant leading-relaxed">{{ $review->comment }}</p>
                    @endif
                </div>
                @empty
                <div class="text-center py-10 text-secondary">
                    <span class="material-symbols-outlined text-4xl mb-2">rate_review</span>
                    <p>Belum ada ulasan untuk kos ini.</p>
                </div>
                @endforelse
            </section>
        </div>

        {{-- Sidebar (1/3) --}}
        <aside class="space-y-6">
            {{-- Info Pemilik --}}
            <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 sticky top-24">
                <h3 class="font-headline text-lg font-bold text-on-surface mb-4">Pemilik Kos</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary text-lg">
                        {{ strtoupper(substr($property->owner->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold">{{ $property->owner->name }}</p>
                        <p class="text-xs text-secondary">Pemilik Kos</p>
                    </div>
                </div>


                @if(auth()->check())
                    @if(auth()->id() !== $property->user_id)
                    <form action="{{ route('chat.start', $property) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-label font-bold text-sm transition-all">
                            <span class="material-symbols-outlined text-lg">forum</span>
                            Tanya Pemilik Kos
                        </button>
                    </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="w-full mt-3 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-label font-bold text-sm transition-all">
                        <span class="material-symbols-outlined text-lg">forum</span>
                        Tanya Pemilik Kos
                    </a>
                @endif

                @if($property->roomTypes->count() > 0)
                <div class="mt-4 pt-4 border-t border-outline-variant/30">
                    <div class="text-xs text-secondary uppercase tracking-wider mb-1">Mulai dari</div>
                    <div class="font-label font-bold text-2xl text-primary">Rp {{ number_format($property->lowest_price, 0, ',', '.') }}</div>
                    <div class="text-xs text-secondary">/ bulan</div>
                </div>
                @endif
            </div>
        </aside>
    </div>
</main>

<x-footer />

@if($property->latitude && $property->longitude)
<script>
    var propertyLat = {{ $property->latitude }};
    var propertyLng = {{ $property->longitude }};
    var map = L.map('map-detail').setView([propertyLat, propertyLng], 15);
    
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var propertyType = "{{ $property->type }}";
    var markerIconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png';
    if (propertyType === 'Putra') {
        markerIconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png';
    } else if (propertyType === 'Putri') {
        markerIconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png';
    }

    var propIcon = L.icon({
        iconUrl: markerIconUrl,
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    L.marker([propertyLat, propertyLng], { icon: propIcon }).addTo(map)
        .bindPopup('<b>🏠 {{ $property->name }}</b><br>{{ $property->address }}<br><a href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude }},{{ $property->longitude }}" target="_blank" rel="noopener noreferrer" class="inline-block mt-2 text-xs font-bold text-primary hover:underline" style="color: #c2652a; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 8px;">Buka di Google Maps ↗</a>').openPopup();

    var campusIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [20, 33],
        iconAnchor: [10, 33],
        popupAnchor: [1, -30],
        shadowSize: [33, 33]
    });

    var nearbyCampuses = {!! json_encode($property->nearby_campuses) !!};
    nearbyCampuses.forEach(function (c) {
        if (c.lat && c.lng) {
            L.marker([c.lat, c.lng], { icon: campusIcon }).addTo(map)
                .bindPopup('<b>🏫 ' + c.name + '</b><br>' + c.label + ' (' + c.distance + ' km)');
        }
    });
</script>
@endif
</body></html>
