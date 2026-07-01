        <div id="view-transaksi" class="view-section hidden space-y-8">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Transaksi</h1>
                <p class="text-secondary font-body mt-2">Daftar semua transaksi yang pernah Anda lakukan.</p>
            </div>

            <!-- Seeker Pending Transaction Warning Container -->
            <div id="seeker-pending-warning-container" class="{{ $hasPendingTransaction ? '' : 'hidden' }}">
                <div class="bg-orange-50 border border-orange-200 text-orange-800 p-4 rounded-xl flex items-start gap-3 shadow-sm mb-6 animate-in fade-in slide-in-from-top-4 duration-300">
                    <span class="text-xl shrink-0">⚠️</span>
                    <p class="font-body text-sm leading-relaxed">
                        Pengingat: Anda memiliki transaksi yang belum diselesaikan (Pending). Silakan cek detail pesanan dan segera lakukan pembayaran agar pengajuan sewa Anda tidak dibatalkan otomatis.
                    </p>
                </div>
            </div>
            
            <!-- Seeker Transactions Cards Container -->
            <div id="seeker-transactions-cards-container" class="space-y-6">
                @include('profile.partials.transactions_cards')
            </div>
        </div>
