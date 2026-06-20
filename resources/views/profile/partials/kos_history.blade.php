        <div id="view-riwayatkos" class="view-section hidden space-y-8">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Riwayat Kos</h1>
                <p class="text-secondary font-body mt-2">Daftar kos yang pernah Anda huni sebelumnya.</p>
            </div>
            
            <div class="space-y-6">
            @forelse($completedBookings as $booking)
                @php
                    $prop = $booking->roomType->property ?? null;
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
                            <span class="font-label text-xs font-bold text-secondary uppercase tracking-widest">Selesai Sewa</span>
                            <h2 class="font-headline text-2xl font-bold text-on-surface">{{ $prop->name ?? 'Properti' }}</h2>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-1">
                                <span class="text-secondary text-sm font-label">Tipe Kamar: {{ $booking->roomType->name }}</span>
                                <span class="text-secondary text-sm font-label">• Selesai: {{ $booking->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('booking.show', $booking) }}" class="w-full md:w-auto bg-primary text-on-primary px-8 py-3.5 rounded-lg font-label font-bold hover:bg-primary-container transition-all shadow-sm text-center">
                        Lihat Ulasan
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-xl py-16 px-8 border border-outline/10 flex flex-col items-center justify-center text-center shadow-sm mt-4">
                    <div class="relative w-64 h-48 mb-8 flex justify-center items-end bg-[#e8f7f0] rounded-[50%_50%_50%_50%_/_60%_60%_40%_40%] overflow-visible">
                         <div class="absolute bottom-6 left-6 text-[#a3e2c6]">
                             <svg width="40" height="50" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 21h22L12 2zm0 4l6 10h-12l6-10z"/></svg>
                         </div>
                         <div class="absolute bottom-4 right-8 text-[#a3e2c6]">
                             <svg width="30" height="40" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 21h22L12 2zm0 4l6 10h-12l6-10z"/></svg>
                         </div>
                         <div class="relative z-10 w-24 h-24 bg-white border-4 border-[#e2e8f0] rounded-sm mb-4">
                            <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-0 h-0 border-l-[55px] border-l-transparent border-r-[55px] border-r-transparent border-b-[40px] border-b-[#cbd5e1]"></div>
                            <div class="w-full h-full bg-[#f1f5f9] flex gap-2 p-2">
                                <div class="w-1/2 h-full bg-white border border-[#e2e8f0]"></div>
                                <div class="w-1/2 h-1/2 bg-white border border-[#e2e8f0]"></div>
                            </div>
                         </div>
                         <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-4 bg-primary text-white text-[10px] font-bold px-4 py-2 rounded shadow-md z-20 whitespace-nowrap">
                             BELUM ADA KOS
                             <div class="w-3 h-3 bg-primary transform rotate-45 absolute -bottom-1 left-1/2 -translate-x-1/2"></div>
                         </div>
                    </div>
                    <h2 class="text-2xl font-bold text-[#1e293b] mb-3">Belum Ada Kos</h2>
                    <p class="text-[#64748b] text-sm">Semua kos yang pernah kamu sewa di Mataram Stay<br>nantinya akan muncul di halaman ini.</p>
                </div>
            @endforelse
            </div>
        </div>
