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
