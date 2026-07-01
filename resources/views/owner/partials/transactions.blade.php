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
                    <p id="owner-total-revenue" class="font-headline text-3xl font-bold text-on-surface">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-soft border border-outline-variant/30 group hover:border-primary/40 transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-secondary-container/50 rounded-lg text-secondary">
                            <span class="material-symbols-outlined">verified</span>
                        </div>
                    </div>
                    <h3 class="text-secondary text-sm font-semibold uppercase tracking-wider mb-1">Transaksi Berhasil</h3>
                    <p id="owner-success-count" class="font-headline text-3xl font-bold text-on-surface">{{ $successCount }}</p>
                </div>
                
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-soft border border-outline-variant/30 group hover:border-primary/40 transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-orange-100 rounded-lg text-orange-700">
                            <span class="material-symbols-outlined">hourglass_empty</span>
                        </div>
                    </div>
                    <h3 class="text-secondary text-sm font-semibold uppercase tracking-wider mb-1">Menunggu Konfirmasi</h3>
                    <p id="owner-pending-count" class="font-headline text-3xl font-bold text-on-surface">{{ $pendingCount }} <span class="text-sm font-body font-normal text-secondary">Pesanan</span></p>
                    <div id="owner-pending-warning" class="{{ $pendingCount > 0 ? '' : 'hidden' }}">
                        <p class="text-xs text-orange-600 mt-2 font-medium">Perlu tindakan segera</p>
                    </div>
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
                        <tbody id="owner-transactions-rows" class="divide-y divide-outline-variant/20">
                            @include('owner.partials.transactions_rows')
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div id="owner-pagination-container">
                    @if($bookings->hasPages())
                    <div class="px-8 py-4 border-t border-outline-variant/30">
                        {{ $bookings->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </section>
