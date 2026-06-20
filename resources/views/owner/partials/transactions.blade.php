        <section id="transaksi" class="tab-content space-y-8 hidden animate-in fade-in duration-500">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Manajemen Transaksi</h1>
                <p class="text-secondary font-body mt-2">{{ auth()->user()->role === 'admin' ? 'Pantau seluruh aktivitas pembayaran dan pemesanan kos secara global.' : 'Pantau seluruh aktivitas pembayaran dan pemesanan kos Anda.' }}</p>
            </div>

            <!-- Financial Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-soft border border-outline-variant/30 group hover:border-primary/40 transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-primary/10 rounded-lg text-primary">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                    </div>
                    <h3 class="text-secondary text-sm font-semibold uppercase tracking-wider mb-1">Total Pendapatan</h3>
                    <p class="font-headline text-3xl font-bold text-on-surface">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-soft border border-outline-variant/30 group hover:border-primary/40 transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-secondary-container/50 rounded-lg text-secondary">
                            <span class="material-symbols-outlined">verified</span>
                        </div>
                    </div>
                    <h3 class="text-secondary text-sm font-semibold uppercase tracking-wider mb-1">Transaksi Berhasil</h3>
                    <p class="font-headline text-3xl font-bold text-on-surface">{{ $successCount }}</p>
                </div>
                
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-soft border border-outline-variant/30 group hover:border-primary/40 transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-orange-100 rounded-lg text-orange-700">
                            <span class="material-symbols-outlined">hourglass_empty</span>
                        </div>
                    </div>
                    <h3 class="text-secondary text-sm font-semibold uppercase tracking-wider mb-1">Menunggu Konfirmasi</h3>
                    <p class="font-headline text-3xl font-bold text-on-surface">{{ $pendingCount }} <span class="text-sm font-body font-normal text-secondary">Pesanan</span></p>
                    @if($pendingCount > 0)
                    <p class="text-xs text-orange-600 mt-2 font-medium">Perlu tindakan segera</p>
                    @endif
                </div>
            </div>

            <!-- Transaction Table/List -->
            <div class="bg-surface-container-lowest rounded-xl shadow-soft border border-outline-variant/20 overflow-hidden">
                <div class="px-8 py-6 border-b border-outline-variant/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="font-headline text-2xl font-semibold">Daftar Transaksi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-surface-container-low">
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Pelanggan</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Properti</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Tanggal</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Durasi</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Total Bayar</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Komisi OTA</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Pendapatan Bersih</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary text-center">Status</th>
                                <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-secondary">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/20">
                            @forelse($bookings as $booking)
                                @php
                                    $prop = $booking->roomType->property ?? null;
                                    $statusColors = [
                                        'Pending' => 'bg-orange-100 text-orange-700',
                                        'Active' => 'bg-green-100 text-green-700',
                                        'Completed' => 'bg-blue-100 text-blue-700',
                                        'Cancelled' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <tr class="hover:bg-surface-container-low/30 transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                                {{ strtoupper(substr($booking->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-on-surface">{{ $booking->user->name }}</p>
                                                <p class="text-xs text-secondary">{{ $booking->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 font-medium text-secondary">{{ $prop->name ?? '-' }}</td>
                                    <td class="px-8 py-6 text-sm">{{ $booking->check_in_date->format('d M Y') }}</td>
                                    <td class="px-8 py-6 text-sm">{{ $booking->duration_months }} Bulan</td>
                                    <td class="px-8 py-6 font-semibold text-on-surface">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td class="px-8 py-6 text-sm text-tertiary">
                                        - Rp {{ number_format($booking->commission_fee, 0, ',', '.') }}
                                        @php
                                            $commissionRate = $booking->room_subtotal > 0 ? round(($booking->commission_fee / $booking->room_subtotal) * 100) : \App\Models\Setting::getValue('commission_rate', 5);
                                        @endphp
                                        ({{ $commissionRate }}%)
                                    </td>
                                    <td class="px-8 py-6 font-bold text-green-700">Rp {{ number_format($booking->net_owner_amount, 0, ',', '.') }}</td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">{{ $booking->status }}</span>
                                    </td>
                                    <td class="px-8 py-6 flex items-center gap-2">
                                         <a href="{{ route('booking.show', $booking) }}" class="text-primary hover:underline text-xs font-bold uppercase">Detail</a>
                                         @if(auth()->user()->isAdmin() && $booking->status === 'Cancelled' && $booking->payment_status === 'Paid' && $booking->escrow_status !== 'refunded')
                                             <form action="{{ route('admin.booking.refund', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin memproses refund untuk pesanan ini?')">
                                                 @csrf
                                                 <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold px-2 py-1 rounded transition-colors uppercase">Refund</button>
                                             </form>
                                         @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-8 py-16 text-center text-secondary">
                                        <span class="material-symbols-outlined text-4xl text-outline mb-2 block">receipt_long</span>
                                        <p class="font-bold">Belum ada transaksi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if($bookings->hasPages())
                <div class="px-8 py-4 border-t border-outline-variant/30">
                    {{ $bookings->links() }}
                </div>
                @endif
            </div>
        </section>
