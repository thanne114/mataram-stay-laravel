<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Mail\SendRentExtensionReminderEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendRentExtensionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automatic rent extension reminder emails to seekers 7 days before their stay ends.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = now()->addDays(7)->toDateString();

        $this->info("Scanning for active bookings ending on: {$targetDate}");

        // Find bookings ending exactly 7 days from now
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $bookings = Booking::with(['user', 'roomType.property'])
                ->where('status', 'Active')
                ->where('payment_status', 'Paid')
                ->whereRaw("date(check_in_date, '+' || duration_months || ' month') = ?", [$targetDate])
                ->get();
        } else {
            $bookings = Booking::with(['user', 'roomType.property'])
                ->where('status', 'Active')
                ->where('payment_status', 'Paid')
                ->whereRaw('DATE_ADD(check_in_date, INTERVAL duration_months MONTH) = ?', [$targetDate])
                ->get();
        }

        if ($bookings->isEmpty()) {
            $this->info("No active bookings ending on {$targetDate} found.");
            return 0;
        }

        $sentCount = 0;

        foreach ($bookings as $booking) {
            $checkoutDate = $booking->check_in_date->copy()->addMonths($booking->duration_months)->toDateString();

            // Check if there is already a renewal booking for this user, room type, and start date
            $existingRenewal = Booking::where('user_id', $booking->user_id)
                ->where('room_type_id', $booking->room_type_id)
                ->whereDate('check_in_date', $checkoutDate)
                ->first();

            if ($existingRenewal) {
                if ($existingRenewal->payment_status === 'Paid') {
                    // Already paid, no reminder needed
                    $this->info("User ID {$booking->user_id} has already renewed and paid for Room Type ID {$booking->room_type_id} starting on {$checkoutDate}. Skipping.");
                    continue;
                }

                // If unpaid, send reminder for the existing renewal booking
                $renewalBooking = $existingRenewal;
                $this->info("Unpaid renewal booking ID {$renewalBooking->id} already exists for User ID {$booking->user_id}. Sending reminder.");
            } else {
                // Create a new renewal booking for the next month
                $renewalBooking = Booking::create([
                    'user_id' => $booking->user_id,
                    'room_type_id' => $booking->room_type_id,
                    'check_in_date' => $checkoutDate,
                    'duration_months' => 1,
                    'total_price' => $booking->roomType->price_per_month,
                    'status' => 'Pending',
                    'payment_status' => 'Unpaid',
                ]);
                $this->info("Created new renewal booking ID {$renewalBooking->id} for User ID {$booking->user_id} starting on {$checkoutDate}.");
            }

            // Send reminder email
            try {
                Mail::to($booking->user->email)->send(new SendRentExtensionReminderEmail($booking, $renewalBooking));
                $sentCount++;
                $this->info("Extension reminder email sent to {$booking->user->email} for Booking ID {$booking->id}.");
            } catch (\Exception $e) {
                Log::error("Failed to send extension reminder email to {$booking->user->email}: " . $e->getMessage());
                $this->error("Failed to send email to {$booking->user->email}.");
            }
        }

        $this->info("Finished sending extension reminders. Total sent: {$sentCount}");
        return 0;
    }
}
