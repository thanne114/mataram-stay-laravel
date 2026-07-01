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
                                <a href="https://wa.me/6281337594955?text=Halo%20Admin%20Mataram%20Stay,%20saya%20ingin%20mengajukan%20refund%20untuk%20pemesanan%20%23{{ $ob->id }}%20(Kode%20Refund%20{{ $refCode }}).%20Terima%20kasih." target="_blank" class="w-full sm:w-auto bg-[#25d366] hover:bg-[#20ba5a] text-white px-5 py-2.5 rounded-lg font-label font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.472 14.382c-.022-.08-.115-.188-.417-.34-.3-.149-1.777-.878-2.052-.978-.276-.1-.476-.149-.674.15-.198.298-.769.978-.943 1.177-.173.2-.347.224-.647.075-.3-.15-1.266-.467-2.41-1.485-.89-.792-1.492-1.77-1.666-2.07-.174-.3-.019-.462.13-.61.135-.133.3-.348.45-.52.15-.173.2-.3.3-.5.1-.2.05-.375-.025-.524-.075-.15-.675-1.624-.925-2.225-.244-.589-.493-.508-.674-.517-.181-.008-.389-.009-.595-.009-.206 0-.54.078-.823.386-.283.308-1.08 1.055-1.08 2.57 0 1.517 1.01 2.977 1.15 3.177.14.2 1.983 3.027 4.806 4.242.673.29 1.2.463 1.61.593.675.215 1.288.185 1.772.113.539-.08 1.62-.663 1.848-1.27.228-.607.228-1.127.16-1.226-.068-.1-.247-.149-.549-.3zM12 2c-5.52 0-10 4.48-10 10 0 1.91.54 3.7 1.56 5.23L2.03 22l4.9-1.28C8.38 21.49 10.13 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm0 18c-1.66 0-3.23-.46-4.57-1.27l-.33-.2-2.9.76.77-2.82-.22-.35C3.89 14.88 3.4 13.5 3.4 12c0-4.74 3.86-8.6 8.6-8.6 4.74 0 8.6 3.86 8.6 8.6S16.74 20 12 20z"/>
                                    </svg>
                                    <span>Ajukan Klaim Refund ke Admin</span>
                                </a>
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
