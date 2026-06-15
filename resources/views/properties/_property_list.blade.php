<div class="flex items-center justify-between mb-6">
    <h1 class="font-headline text-3xl font-bold">{{ $properties->total() }} Kos Ditemukan</h1>
</div>

@if($properties->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @foreach($properties as $property)
    <a href="{{ route('property.show', $property->slug) }}" class="group bg-surface-container-lowest rounded-2xl border border-outline-variant/30 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col">
        <div class="relative aspect-[4/3] overflow-hidden">
            @if($property->main_image)
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="{{ asset('storage/' . $property->main_image) }}" alt="{{ $property->name }}">
            @else
            <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-5xl text-outline">apartment</span>
            </div>
            @endif
            <div class="absolute top-4 left-4 bg-on-background text-surface-bright px-3 py-1 rounded-full text-[10px] font-label font-bold tracking-widest uppercase">{{ $property->type }}</div>
        </div>
        <div class="p-5 flex flex-col gap-3 flex-1">
            <div class="flex justify-between items-start">
                <h3 class="font-headline text-xl font-bold text-on-surface tracking-tight leading-tight">{{ $property->name }}</h3>
                <div class="flex items-center gap-1 shrink-0 pt-1">
                    <span class="material-symbols-outlined text-primary text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span class="font-label text-sm font-bold text-on-surface">{{ $property->average_rating ?? 'Baru' }}</span>
                </div>
            </div>
            <div class="flex items-center text-secondary gap-1">
                <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                <span class="font-label text-xs">{{ $property->area }}, Mataram</span>
                @if($property->latitude && $property->longitude)
                <button type="button" onclick="event.preventDefault(); showOnMap({{ (float) $property->latitude }}, {{ (float) $property->longitude }}, '{{ addslashes($property->name) }}')" class="ml-auto text-[11px] font-bold text-primary hover:text-primary-container transition-colors flex items-center gap-0.5 pointer-events-auto">
                    <span class="material-symbols-outlined text-[13px]">map</span> Lihat Peta
                </button>
                @endif
            </div>
            
            @if($property->closest_campus)
            <div class="flex items-center text-primary font-medium gap-1 text-[11px]">
                <span class="material-symbols-outlined text-[14px]">school</span>
                <span>{{ $property->closest_campus['label'] }}</span>
            </div>
            @endif

            <div class="flex items-center text-secondary gap-1 text-[11px]">
                <span class="material-symbols-outlined text-[14px] text-primary animate-pulse" style="font-variation-settings: 'FILL' 1;">local_fire_department</span>
                <span>Dilihat {{ $property->views_count }} kali dalam 24 jam terakhir</span>
            </div>

            <div class="mt-auto pt-2">
                @if($property->lowest_price)
                <div class="flex items-baseline gap-1">
                    <span class="font-label font-bold text-lg text-primary">Rp {{ number_format($property->lowest_price, 0, ',', '.') }}</span>
                    <span class="font-label text-xs text-secondary">/ bln</span>
                </div>
                @endif
                
                @if($property->available_rooms > 0 && $property->available_rooms <= 3)
                <div class="inline-flex bg-red-50 text-red-600 px-3 py-1 rounded-full border border-red-200 mt-2 font-bold animate-pulse">
                    <span class="font-label text-[10px] uppercase flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">warning</span>
                        Sisa {{ $property->available_rooms }} Kamar Lagi!
                    </span>
                </div>
                @elseif($property->available_rooms > 3)
                <div class="inline-flex bg-green-50 text-green-700 px-3 py-1 rounded-full border border-green-100 mt-2">
                    <span class="font-label text-[10px] font-bold uppercase">{{ $property->available_rooms }} Kamar Tersedia</span>
                </div>
                @else
                <div class="inline-flex bg-red-100 text-red-800 px-3 py-1 rounded-full border border-red-200 mt-2 font-bold">
                    <span class="font-label text-[10px] uppercase">PENUH</span>
                </div>
                @endif
            </div>
        </div>
    </a>
    @endforeach
</div>

{{-- Pagination --}}
<div class="flex justify-center ajax-pagination">
    {{ $properties->withQueryString()->links() }}
</div>
@else
<div class="text-center py-20">
    <span class="material-symbols-outlined text-6xl text-outline mb-4">search_off</span>
    <h2 class="font-headline text-2xl font-bold text-on-surface mb-2">Tidak Ada Hasil</h2>
    <p class="text-secondary">Coba ubah filter pencarian Anda atau <a href="{{ route('search') }}" class="text-primary font-bold hover:underline">lihat semua kos</a>.</p>
</div>
@endif
