<!DOCTYPE html><html lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Cari Kos - Mataram Stay</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    tailwind.config = {
        theme: { extend: {
            "colors": {
                "outline-variant": "#d8d0c8", "background": "#faf5ee", "surface": "#faf5ee",
                "on-surface": "#3a302a", "primary": "#c2652a", "on-primary": "#ffffff",
                "primary-container": "#e08850", "secondary": "#78706a", "surface-bright": "#faf5ee",
                "surface-variant": "#ece6dc", "on-background": "#3a302a",
                "surface-container-lowest": "#ffffff", "surface-container-low": "#f6f0e8",
                "surface-container": "#f2ece4", "surface-container-high": "#ece6dc",
                "on-surface-variant": "#605850", "primary-fixed": "#fbe8d8",
                "on-primary-fixed": "#401a08", "outline": "#9a9088",
                "on-secondary": "#ffffff", "error": "#c0392b",
                "primary-fixed-dim": "#f0a878", "secondary-container": "#eae2da"
            },
            "fontFamily": {
                "headline": ["EB Garamond", "serif"],
                "body": ["Manrope", "sans-serif"],
                "label": ["Manrope", "sans-serif"]
            }
        }}
    }
</script>
<style>
    .font-headline { font-family: 'EB Garamond', serif; }
    #map-search { z-index: 10; }
</style>
</head>
<body class="bg-background text-on-surface font-body antialiased min-h-screen flex flex-col">
<x-navbar />

<main x-data="filterSystem()" class="flex-grow max-w-7xl mx-auto w-full px-4 md:px-8 py-8">
    {{-- Filter Bar --}}
    <form @submit.prevent="applyFilters()" class="bg-surface-container-lowest rounded-xl p-4 border border-outline-variant/30 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="text-[10px] uppercase tracking-widest font-bold text-secondary mb-1 block">Lokasi</label>
                <select name="lokasi" x-model="lokasi" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-3 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary">
                    <option value="">Semua Kecamatan</option>
                    @foreach(['Mataram','Ampenan','Cakranegara','Selaparang','Sekarbela','Sandubaya'] as $area)
                    <option value="{{ $area }}">{{ $area }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] uppercase tracking-widest font-bold text-secondary mb-1 block">Dekat Kampus (Hub)</label>
                <select name="kampus" x-model="kampus" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-3 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary">
                    <option value="">Pilih Kampus</option>
                    <option value="UNRAM">Universitas Mataram (UNRAM)</option>
                    <option value="UIN_MATARAM">UIN Mataram</option>
                    <option value="POLNAM">Politeknik Negeri Mataram (Polnam)</option>
                    <option value="UT_MATARAM">Universitas Terbuka Mataram (UT)</option>
                    <option value="UMMAT">UM Muhammadiyah Mataram (UMMAT)</option>
                    <option value="UTM">Universitas Teknologi Mataram (UTM)</option>
                    <option value="UNBIM">Universitas Bhakti Mataram (UNBIM)</option>
                    <option value="IAHN_GDE_PUDJA">IAHN Gde Pudja</option>
                    <option value="STIKES_YARSI">STIKES Yarsi Mataram</option>
                    <option value="UNMAS">Universitas Mahasaraswati Mataram</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] uppercase tracking-widest font-bold text-secondary mb-1 block">Tipe Kos</label>
                <select name="tipe_kos" x-model="tipe_kos" class="w-full bg-surface-bright border border-outline-variant rounded-lg px-3 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary">
                    <option value="">Semua Tipe</option>
                    @foreach(['Putra','Putri','Campur'] as $tipe)
                    <option value="{{ $tipe }}">{{ $tipe }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] uppercase tracking-widest font-bold text-secondary mb-1 block">
                    Harga Maksimal: <span class="text-primary font-bold" x-text="harga_maksimal >= 3000000 ? 'Tanpa Batas' : 'Rp ' + Number(harga_maksimal).toLocaleString('id-ID')"></span>
                </label>
                <div class="pt-2 px-1">
                    <input type="range" min="500000" max="3000000" step="100000" x-model="harga_maksimal" class="w-full h-2 bg-surface-container-high rounded-lg appearance-none cursor-pointer accent-primary focus:outline-none">
                    <div class="flex justify-between text-[10px] text-secondary/70 mt-1">
                        <span>500k</span>
                        <span>1.5M</span>
                        <span>3M+</span>
                    </div>
                </div>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-primary text-on-primary px-6 py-2.5 rounded-lg font-label font-bold text-sm hover:bg-primary-container transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">search</span> Cari
                </button>
            </div>
        </div>

        {{-- Facilities Checkboxes --}}
        <div class="border-t border-outline-variant/20 mt-5 pt-4">
            <label class="text-[10px] uppercase tracking-widest font-bold text-secondary mb-2.5 block">Fasilitas Kos</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-7 gap-3">
                @foreach($facilitiesList ?? [] as $facility)
                <label class="flex items-center gap-2 px-3 py-2 bg-surface-bright border border-outline-variant/40 rounded-lg cursor-pointer hover:border-primary/50 transition-all select-none group">
                    <input type="checkbox" value="{{ $facility->id }}" x-model="fasilitas" class="rounded border-outline-variant text-primary focus:ring-primary focus:ring-offset-0 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px] text-secondary group-hover:text-primary transition-colors" style="font-variation-settings: 'FILL' 1;">{{ $facility->icon ?? 'star' }}</span>
                    <span class="text-xs text-on-surface font-medium">{{ $facility->name }}</span>
                </label>
                @endforeach
            </div>
        </div>
    </form>

    {{-- Peta --}}
    <div id="map-search" class="w-full h-[300px] rounded-xl border border-outline-variant/40 mb-8"></div>

    {{-- Hasil --}}
    <div id="results-container" class="relative min-h-[200px]">
        {{-- Loading Overlay Spinner --}}
        <div x-show="isLoading" class="absolute inset-0 bg-background/60 flex items-center justify-center z-20 rounded-2xl transition-opacity duration-300" style="display: none;">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent shadow-md"></div>
        </div>
        @include('properties._property_list', ['properties' => $properties])
    </div>
</main>

<x-footer />

<script>
    var campuses = {
        'UNRAM': {
            name: 'Universitas Mataram (UNRAM)',
            lat: -8.5878,
            lng: 116.0967
        },
        'UIN_MATARAM': {
            name: 'UIN Mataram',
            lat: -8.6116,
            lng: 116.1154
        },
        'POLNAM': {
            name: 'Politeknik Negeri Mataram (Polnam)',
            lat: -8.5833,
            lng: 116.0950
        },
        'UT_MATARAM': {
            name: 'Universitas Terbuka Mataram (UT)',
            lat: -8.5796,
            lng: 116.1026
        },
        'UMMAT': {
            name: 'Universitas Muhammadiyah Mataram (UMMAT)',
            lat: -8.5982,
            lng: 116.1084
        },
        'UTM': {
            name: 'Universitas Teknologi Mataram (UTM)',
            lat: -8.5835,
            lng: 116.1054
        },
        'UNBIM': {
            name: 'Universitas Bhakti Mataram (UNBIM)',
            lat: -8.6050,
            lng: 116.0850
        },
        'IAHN_GDE_PUDJA': {
            name: 'IAHN Gde Pudja',
            lat: -8.5990,
            lng: 116.1165
        },
        'STIKES_YARSI': {
            name: 'STIKES Yarsi Mataram',
            lat: -8.6120,
            lng: 116.1060
        },
        'UNMAS': {
            name: 'Universitas Mahasaraswati Mataram',
            lat: -8.5925,
            lng: 116.1105
        }
    };

    var defaultLat = -8.5878;
    var defaultLng = 116.0967;

    // Detect campus from query params on load to set initial focus
    var urlParams = new URLSearchParams(window.location.search);
    var initialCampus = urlParams.get('kampus');
    if (initialCampus && campuses[initialCampus]) {
        defaultLat = campuses[initialCampus].lat;
        defaultLng = campuses[initialCampus].lng;
    }

    var map = L.map('map-search').setView([defaultLat, defaultLng], 14);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Custom Icon for Campuses (Red Pin)
    var campusIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var campusMarker = null;

    // Method to dynamically clear old pin, add new pin, and center map view
    function updateCampusMarker(campusKey) {
        if (campusMarker) {
            map.removeLayer(campusMarker);
            campusMarker = null;
        }

        if (campusKey && campuses[campusKey]) {
            var c = campuses[campusKey];
            campusMarker = L.marker([c.lat, c.lng], { icon: campusIcon }).addTo(map)
                .bindPopup('<b>🏫 ' + c.name + '</b><br>Pusat acuan radius pencarian.');
            campusMarker.openPopup();
            map.flyTo([c.lat, c.lng], 14, { animate: true, duration: 1.5 });
        } else {
            // Default center if no campus selected (UNRAM coords)
            map.flyTo([-8.5878, 116.0967], 14, { animate: true, duration: 1.5 });
        }
    }

    // Plot initial campus marker on first load
    if (initialCampus && campuses[initialCampus]) {
        updateCampusMarker(initialCampus);
    } else {
        updateCampusMarker('UNRAM');
    }

    // Define colored markers based on gender rules
    var icons = {
        'Putra': L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        }),
        'Putri': L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        }),
        'Campur': L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    };

    var markerLayerGroup = L.layerGroup().addTo(map);
    var activeMarkers = {};

    function loadMarkers() {
        var params = new URLSearchParams(window.location.search);
        
        // Dynamic boundary tracking for map panning & zoom
        var bounds = map.getBounds();
        params.set('north', bounds.getNorth());
        params.set('south', bounds.getSouth());
        params.set('east', bounds.getEast());
        params.set('west', bounds.getWest());

        fetch('/api/map-data?' + params.toString())
            .then(r => r.json())
            .then(data => {
                markerLayerGroup.clearLayers();
                activeMarkers = {};

                data.forEach(p => {
                    if (p.lat && p.lng) {
                        var price = p.price ? 'Rp ' + p.price.toLocaleString('id-ID') + '/bln' : '';
                        var statusKamar = p.available > 0 ? 
                            '<span style="color: #16a34a; font-weight: bold;">🟢 ' + p.available + ' Kamar</span>' : 
                            '<span style="color: #dc2626; font-weight: bold;">🔴 Penuh</span>';
                        
                        var badgeClass = p.type === 'Putra' ? 'bg-blue-100 text-blue-800' : (p.type === 'Putri' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800');

                        var content = '<div class="p-1 flex flex-col gap-1 min-w-[150px] font-sans">' +
                            '<b class="text-sm font-semibold text-gray-800 block leading-tight">' + p.name + '</b>' +
                            '<div class="flex items-center gap-1 mt-0.5">' +
                            '<span class="px-1.5 py-0.5 text-[9px] font-bold rounded ' + badgeClass + '">' + p.type + '</span>' +
                            '<span class="text-[10px] text-gray-500 font-medium">' + p.area + '</span>' +
                            '</div>' +
                            '<span class="text-xs font-bold text-primary mt-1 block">' + price + '</span>' +
                            '<span class="text-[11px] block mt-0.5">' + statusKamar + '</span>' +
                            '<a href="/kos/' + p.slug + '" class="text-[11px] text-primary hover:underline mt-2 block font-bold text-center border border-primary/20 py-1 rounded bg-primary/5 transition-all">Lihat Detail →</a>' +
                            '</div>';

                        var markerIcon = icons[p.type] || icons['Campur'];
                        var marker = L.marker([p.lat, p.lng], { icon: markerIcon })
                            .bindPopup(content);
                        
                        markerLayerGroup.addLayer(marker);
                        activeMarkers[p.lat + ',' + p.lng] = marker;
                    }
                });
            })
            .catch(err => console.error("Gagal memuat markers:", err));
    }

    // Initial load
    loadMarkers();

    // Trigger update on zoom/drag
    map.on('dragend', loadMarkers);
    map.on('zoomend', loadMarkers);

    // Zoom and fly to coordinate when clicking property card locator button
    function showOnMap(lat, lng, name) {
        map.setView([lat, lng], 16, { animate: true, duration: 1.5 });
        var key = lat + ',' + lng;
        setTimeout(function() {
            if (activeMarkers[key]) {
                activeMarkers[key].openPopup();
            }
        }, 1500);
        
        // Scroll map into center view
        document.getElementById('map-search').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Alpine.js filterSystem declaration
    function filterSystem() {
        return {
            lokasi: '{{ $filters['lokasi'] ?? '' }}',
            kampus: '{{ $filters['kampus'] ?? '' }}',
            tipe_kos: '{{ $filters['tipe_kos'] ?? '' }}',
            harga_maksimal: '{{ $filters['harga_maksimal'] ?? '3000000' }}',
            fasilitas: {!! json_encode(request('fasilitas') ?? []) !!},
            isLoading: false,

            init() {
                // Watchers to trigger filtering on change
                this.$watch('lokasi', () => this.applyFilters());
                this.$watch('kampus', (value) => {
                    this.applyFilters();
                    if (typeof updateCampusMarker === 'function') {
                        updateCampusMarker(value);
                    }
                });
                this.$watch('tipe_kos', () => this.applyFilters());
                this.$watch('harga_maksimal', () => this.applyFilters());
                this.$watch('fasilitas', () => this.applyFilters());

                // Intercept pagination link clicks for AJAX loading
                document.addEventListener('click', (e) => {
                    let paginatorLink = e.target.closest('.ajax-pagination a');
                    if (paginatorLink) {
                        e.preventDefault();
                        this.fetchPage(paginatorLink.href);
                    }
                });
            },

            applyFilters() {
                this.isLoading = true;
                
                // Construct URL parameters
                let queryParams = new URLSearchParams();
                if (this.lokasi) queryParams.set('lokasi', this.lokasi);
                if (this.kampus) queryParams.set('kampus', this.kampus);
                if (this.tipe_kos) queryParams.set('tipe_kos', this.tipe_kos);
                if (this.harga_maksimal && this.harga_maksimal < 3000000) {
                    queryParams.set('harga_maksimal', this.harga_maksimal);
                }
                if (this.fasilitas && this.fasilitas.length > 0) {
                    this.fasilitas.forEach(id => {
                        queryParams.append('fasilitas[]', id);
                    });
                }

                let newUrl = window.location.pathname + '?' + queryParams.toString();
                window.history.pushState(null, '', newUrl);

                this.fetchResults(newUrl);
            },

            fetchPage(url) {
                this.isLoading = true;
                window.history.pushState(null, '', url);
                this.fetchResults(url);
                
                // Scroll listings back to top view
                document.getElementById('results-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            },

            fetchResults(url) {
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('results-container').innerHTML = data.html;
                    this.isLoading = false;
                    
                    // Reload map markers based on the new URL context
                    if (typeof loadMarkers === 'function') {
                        loadMarkers();
                    }
                })
                .catch(err => {
                    console.error("Gagal melakukan pencarian:", err);
                    this.isLoading = false;
                });
            }
        };
    }
</script>
</body></html>
