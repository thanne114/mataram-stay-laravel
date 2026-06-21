<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\RoomType;
use App\Services\MidtransPayoutService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoCompleteBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:auto-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically complete Active bookings whose stay duration has expired, restore room inventory, and release escrow funds.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();
        $this->info("Scanning for expired active bookings as of today: {$today}");

        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $expiredBookings = Booking::where('status', 'Active')
                ->where('payment_status', 'Paid')
                ->whereRaw("date(check_in_date, '+' || duration_months || ' month') <= ?", [$today])
                ->get();
        } else {
            $expiredBookings = Booking::where('status', 'Active')
                ->where('payment_status', 'Paid')
                ->whereRaw('DATE_ADD(check_in_date, INTERVAL duration_months MONTH) <= ?', [$today])
                ->get();
        }

        if ($expiredBookings->isEmpty()) {
            $this->info("No expired active bookings found.");
            return 0;
        }

        $completedCount = 0;

        foreach ($expiredBookings as $booking) {
            $checkoutDate = $booking->check_in_date->copy()->addMonths($booking->duration_months)->toDateString();
            $this->info("Processing expired booking ID {$booking->id} (Checkout date: {$checkoutDate})");

            $statusUpdated = false;

            DB::transaction(function () use ($booking, &$statusUpdated) {
                // Update booking status
                $booking->update(['status' => 'Completed']);

                // Increment room availability
                $roomType = RoomType::lockForUpdate()->find($booking->room_type_id);
                if ($roomType) {
                    $roomType->increment('available_rooms');
                }
                $statusUpdated = true;
            });

            if ($statusUpdated) {
                // Release escrow funds (outside database transaction)
                $payoutReference = MidtransPayoutService::sendPayout($booking);
                if ($payoutReference) {
                    $booking->update([
                        'escrow_status' => 'released',
                        'payout_status' => 'success',
                        'payout_reference' => $payoutReference,
                    ]);
                    $this->info("Escrow payout successful for Booking ID {$booking->id}. Ref: {$payoutReference}");
                } else {
                    $booking->update([
                        'payout_status' => 'failed',
                    ]);
                    $this->error("Escrow payout failed for Booking ID {$booking->id}.");
                }

                $completedCount++;
            }
        }

        $this->info("Finished auto-completing expired bookings. Total completed: {$completedCount}");
        return 0;
    }
}
