<!DOCTYPE html><html lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Pesan Kamar - Mataram Stay</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<script>
    tailwind.config = {
        theme: { extend: {
            "colors": {
                "outline-variant": "#d8d0c8", "background": "#faf5ee", "surface": "#faf5ee",
                "on-surface": "#3a302a", "primary": "#c2652a", "on-primary": "#ffffff",
                "primary-container": "#e08850", "secondary": "#78706a", "surface-bright": "#faf5ee",
                "surface-container-lowest": "#ffffff", "surface-container-low": "#f6f0e8",
                "on-surface-variant": "#605850", "primary-fixed": "#fbe8d8",
                "outline": "#9a9088", "error": "#c0392b", "on-primary-fixed": "#401a08"
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
</style>
</head>
<body class="bg-background text-on-surface font-body antialiased min-h-screen flex flex-col">
<x-navbar />

<main class="flex-grow max-w-3xl mx-auto w-full px-4 md:px-8 py-10">
    <h1 class="font-headline text-3xl md:text-4xl font-bold text-on-surface mb-8">Formulir Pemesanan</h1>

    @if(session('error'))
    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 mb-6">
        <span class="material-symbols-outlined">error</span>
        <p class="font-bold text-sm">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Ringkasan Kamar --}}
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 mb-8">
        <div class="flex flex-col sm:flex-row gap-6">
            @if($roomType->property->main_image)
            <div class="w-full sm:w-40 h-32 rounded-lg overflow-hidden shrink-0">
                <img src="{{ asset('storage/' . $roomType->property->main_image) }}" alt="{{ $roomType->property->name }}" class="w-full h-full object-cover">
            </div>
            @endif
            <div class="flex-1">
                <h2 class="font-headline text-2xl font-bold">{{ $roomType->property->name }}</h2>
                <p class="text-sm text-secondary mt-1">{{ $roomType->property->address }}</p>
                <div class="flex items-center gap-2 mt-3">
                    <span class="px-3 py-1 bg-primary-fixed text-on-primary-fixed rounded-full text-[10px] font-bold uppercase">{{ $roomType->name }}</span>
                    <span class="text-secondary text-xs">{{ $roomType->property->type }} • {{ $roomType->property->area }}</span>
                </div>
                <div class="mt-3">
                    <span class="font-label font-bold text-xl text-primary">Rp {{ number_format($roomType->price_per_month, 0, ',', '.') }}</span>
                    <span class="text-xs text-secondary">/ bulan</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Booking --}}
    <form action="{{ route('booking.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

        <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 space-y-5">
            <h3 class="font-headline text-xl font-bold">Detail Pemesanan</h3>

            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider block mb-1.5">Tanggal Check-in</label>
                <input type="date" name="check_in_date" value="{{ old('check_in_date') }}" min="{{ date('Y-m-d') }}"
                    class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary">
                @error('check_in_date')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider block mb-1.5">Durasi Sewa (Bulan)</label>
                <select name="duration_months" id="duration_months"
                    class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary">
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ old('duration_months') == $i ? 'selected' : '' }}>{{ $i }} Bulan</option>
                    @endfor
                </select>
                @error('duration_months')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ringkasan Harga --}}
            <div class="pt-4 border-t border-outline-variant/30 space-y-2.5">
                <div class="flex justify-between items-center text-sm text-secondary">
                    <span>Harga per bulan</span>
                    <span class="font-semibold text-on-surface">Rp {{ number_format($roomType->price_per_month, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm text-secondary">
                    <span>Durasi</span>
                    <span class="font-semibold text-on-surface" id="duration_display">1 Bulan</span>
                </div>
                <div class="flex justify-between items-center text-sm text-secondary pt-2 border-t border-outline-variant/20">
                    <span>Subtotal Kamar</span>
                    <span class="font-semibold text-on-surface" id="room_subtotal">Rp {{ number_format($roomType->price_per_month, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm text-secondary">
                    <span>Biaya Layanan Admin</span>
                    <span class="font-semibold text-on-surface">Rp {{ number_format(\App\Models\Setting::getValue('admin_fee', 2500), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-outline-variant/30">
                    <span class="font-bold text-on-surface">Total Pembayaran</span>
                    <span class="font-headline text-2xl font-bold text-primary" id="total_price">Rp {{ number_format($roomType->price_per_month + \App\Models\Setting::getValue('admin_fee', 2500), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-primary text-on-primary py-4 rounded-xl font-label font-bold text-base hover:bg-primary-container transition-all shadow-lg active:scale-[0.98]">
            Konfirmasi Pemesanan
        </button>
    </form>

    {{-- Info Kontak Owner --}}
    @if($roomType->property->owner->no_whatsapp)
    <div class="mt-6 bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 flex items-center gap-4">
        <span class="material-symbols-outlined text-primary">info</span>
        <div class="text-sm text-secondary">
            Ada pertanyaan? Hubungi pemilik kos via 
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $roomType->property->owner->no_whatsapp) }}" target="_blank" class="text-green-600 font-bold hover:underline">WhatsApp</a>
        </div>
    </div>
    @endif
</main>

<script>
    const pricePerMonth = {{ $roomType->price_per_month }};
    const adminFee = {{ (int) \App\Models\Setting::getValue('admin_fee', 2500) }};
    const durationSelect = document.getElementById('duration_months');
    const subtotalDisplay = document.getElementById('room_subtotal');
    const totalDisplay = document.getElementById('total_price');
    const durationDisplay = document.getElementById('duration_display');

    durationSelect.addEventListener('change', function() {
        const months = parseInt(this.value);
        const subtotal = pricePerMonth * months;
        const total = subtotal + adminFee;
        subtotalDisplay.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
        durationDisplay.textContent = months + ' Bulan';
    });
</script>
<x-footer />
</body></html>
