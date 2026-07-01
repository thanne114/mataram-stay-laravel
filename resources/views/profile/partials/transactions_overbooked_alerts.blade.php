@php
    $overbookedBookings = $bookings->filter(function($b) {
        return $b->status === 'Cancelled' && $b->payment_status === 'Paid';
    });
@endphp

@if($overbookedBookings->isNotEmpty())
    <div class="space-y-6">
        @foreach($overbookedBookings as $ob)
            @php
                $propName = $ob->roomType->property->name ?? 'Properti Kos';
                $refCode = 'REF-' . $ob->id;
            @endphp
            <div class="bg-red-50 border border-red-200 text-red-900 p-6 rounded-2xl shadow-sm mb-6 animate-in fade-in slide-in-from-top-4 duration-300">
                <div class="flex items-start gap-4">
                    <span class="text-3xl shrink-0">🚨</span>
                    <div class="space-y-3 flex-grow text-left">
                        <h3 class="font-headline text-lg font-bold text-red-950">Kamar Kos Penuh (Overbooked) & Pengembalian Dana</h3>
                        <p class="font-body text-xs md:text-sm leading-relaxed text-red-800">
                            Pemesanan Anda untuk properti <strong>{{ $propName }}</strong> otomatis dibatalkan oleh platform karena kapasitas kamar telah penuh sesaat sebelum pembayaran Anda terkonfirmasi.
                        </p>
                        <div class="bg-white/80 rounded-xl p-4 text-xs font-mono border border-red-100 flex flex-col md:flex-row justify-between gap-3">
                            <div>
                                <span class="text-secondary">Nomor Pemesanan:</span> <strong class="text-red-950">#{{ $ob->id }}</strong>
                            </div>
                            <div>
                                <span class="text-secondary">Kode Refund:</span> <strong class="text-red-950">{{ $refCode }}</strong>
                            </div>
                            <div>
                                <span class="text-secondary">Status Refund:</span> 
                                @if($ob->escrow_status === 'refunded')
                                    <span class="text-green-700 font-bold uppercase tracking-wider">Telah Direfund</span>
                                @else
                                    <span class="text-orange-700 font-bold uppercase tracking-wider animate-pulse">Menunggu Proses Admin</span>
                                @endif
                            </div>
                        </div>
                        @if($ob->escrow_status !== 'refunded')
                            <div class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                                @if($ob->roomType && $ob->roomType->property)
                                    <form action="{{ route('chat.start', $ob->roomType->property) }}" method="POST" class="w-full sm:w-auto inline">
                                        @csrf
                                        <input type="hidden" name="message" value="Halo, pemesanan saya untuk kos ini dibatalkan karena overbooked (Pemesanan #{{ $ob->id }}, Kode Refund {{ $refCode }}). Bagaimana kelanjutan proses refund saya?">
                                        <button type="submit" class="w-full bg-primary text-on-primary hover:bg-primary-container px-5 py-2.5 rounded-lg font-label font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-sm">chat</span>
                                            <span>Tanya Pemilik Kos via Chat</span>
                                        </button>
                                    </form>
                                @endif
                                <p class="text-[10px] text-red-700 italic">Tim kami akan mengembalikan dana 100% tanpa potongan.</p>
                            </div>
                        @else
                            <div class="pt-2">
                                <span class="inline-flex items-center gap-1.5 text-xs text-green-700 bg-green-50 border border-green-200 px-3 py-1.5 rounded-lg font-medium">
                                    <span class="material-symbols-outlined text-sm">check_circle</span>
                                    Refund selesai diproses. Silakan hubungi bank Anda jika dana belum masuk.
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
