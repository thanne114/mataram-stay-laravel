        <div id="view-transaksi" class="view-section hidden space-y-8">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Transaksi</h1>
                <p class="text-secondary font-body mt-2">Daftar semua transaksi yang pernah Anda lakukan.</p>
            </div>

            @if($hasPendingTransaction)
                <div class="bg-orange-50 border border-orange-200 text-orange-800 p-4 rounded-xl flex items-start gap-3 shadow-sm mb-6 animate-in fade-in slide-in-from-top-4 duration-300">
                    <span class="text-xl shrink-0">⚠️</span>
                    <p class="font-body text-sm leading-relaxed">
                        Pengingat: Anda memiliki transaksi yang belum diselesaikan (Pending). Silakan cek detail pesanan dan segera lakukan pembayaran agar pengajuan sewa Anda tidak dibatalkan otomatis.
                    </p>
                </div>
            @endif
            
            <div class="space-y-6">
            @forelse($bookings as $booking)
                @php
                    $prop = $booking->roomType->property ?? null;
                    $statusColors = [
                        'Pending' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                        'Active' => 'bg-green-50 text-green-700 border-green-100',
                        'Completed' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'Cancelled' => 'bg-red-50 text-red-700 border-red-100',
                    ];
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
                            <span class="font-label text-xs font-bold text-primary uppercase tracking-widest">Pemesanan Kos</span>
                            <h2 class="font-headline text-2xl font-bold text-on-surface">{{ $prop->name ?? 'Properti' }}</h2>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$booking->status] ?? 'bg-gray-50 text-gray-700' }} border">
                                    {{ $booking->status }}
                                </span>
                                <span class="text-secondary text-sm font-label">• Check-in: {{ $booking->check_in_date->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('booking.show', $booking) }}" class="w-full md:w-auto bg-primary text-on-primary px-8 py-3.5 rounded-lg font-label font-bold hover:bg-primary-container transition-all shadow-sm text-center">
                        Lihat Detail
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-xl p-12 border border-outline/10 flex flex-col items-center justify-center text-center shadow-sm">
                    <span class="material-symbols-outlined text-[60px] text-surface-dim mb-4">receipt_long</span>
                    <h2 class="text-xl font-bold text-on-surface mb-2">Belum ada transaksi</h2>
                    <p class="text-secondary font-body">Silakan lakukan pemesanan kos untuk melihat riwayat transaksi Anda.</p>
                </div>
            @endforelse
            </div>
        </div>
