<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Mail\BookingNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CancelStaleBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cancel-stale {--minutes=30 : Number of minutes before auto-cancellation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancel Pending bookings that have not been paid after the specified number of minutes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = (int) $this->option('minutes');
        $cutoffDate = now()->subMinutes($minutes);

        $this->info("Scanning for stale Pending bookings created before: {$cutoffDate->toDateTimeString()}");

        // Find bookings that are still Pending + Unpaid and older than X days
        $staleBookings = Booking::with(['user', 'roomType.property'])
            ->where('status', 'Pending')
            ->where('payment_status', 'Unpaid')
            ->where('created_at', '<', $cutoffDate)
            ->get();

        if ($staleBookings->isEmpty()) {
            $this->info("No stale bookings found. Everything is up to date.");
            return 0;
        }

        $cancelledCount = 0;

        foreach ($staleBookings as $booking) {
            $booking->update([
                'status' => 'Cancelled',
            ]);

            // Send cancellation email to seeker
            try {
                Mail::to($booking->user->email)->send(new BookingNotificationMail($booking, 'cancelled_seeker'));
            } catch (\Exception $e) {
                Log::error("Failed to send stale booking cancellation email for Booking ID {$booking->id}: " . $e->getMessage());
            }

            $cancelledCount++;
            $this->info("Cancelled stale Booking ID {$booking->id} (created: {$booking->created_at->toDateTimeString()}, User: {$booking->user->name}).");
        }

        $this->info("Finished. Total stale bookings cancelled: {$cancelledCount}");
        return 0;
    }
}
