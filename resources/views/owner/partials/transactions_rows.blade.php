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
