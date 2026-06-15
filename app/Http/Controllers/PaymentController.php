<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Handle Midtrans Payment Notification Webhook
     */
    public function notification(Request $request)
    {
        $payload = $request->all();
        
        Log::info('Midtrans Webhook Received:', $payload);

        $rawOrderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        if (!$rawOrderId || !$statusCode || !$grossAmount || !$signatureKey) {
            return response()->json(['message' => 'Invalid notification payload'], 400);
        }

        // Validate Midtrans Signature
        $serverKey = config('services.midtrans.server_key');
        $localSignature = hash('sha512', $rawOrderId . $statusCode . $grossAmount . $serverKey);

        if (!hash_equals($localSignature, $signatureKey)) {
            Log::warning("Midtrans Webhook Invalid Signature Key for Order ID: {$rawOrderId}!");
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        // Extract booking ID (first part of order_id split by '-')
        $orderId = explode('-', $rawOrderId)[0];

        // Fetch Booking
        $booking = Booking::with('roomType')->find($orderId);

        if (!$booking) {
            Log::warning("Booking ID {$orderId} not found for payment notification");
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Process status
        // settlement = e-wallet, virtual account, etc.
        // capture = credit card
        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            if ($booking->payment_status !== 'Paid') {
                \Illuminate\Support\Facades\DB::transaction(function () use ($booking) {
                    // Lock the room type row for update to prevent race conditions during settlement
                    $roomType = \App\Models\RoomType::lockForUpdate()->findOrFail($booking->room_type_id);

                    if ($roomType->available_rooms <= 0) {
                        // Room became full before this payment settled
                        $booking->update([
                            'payment_status' => 'Paid',
                            'status'         => 'Cancelled', // Marked as Cancelled/Overbooked for admin follow up
                        ]);
                        Log::warning("Booking ID {$booking->id} settled via Midtrans, but Room Type ID {$roomType->id} is already full! Marking booking as Cancelled.");
                    } else {
                        $booking->update([
                            'payment_status' => 'Paid',
                            'status'         => 'Active',
                        ]);

                        // Decrement room count
                        $roomType->decrement('available_rooms');
                        Log::info("Booking ID {$booking->id} marked as Paid and Active. Room type {$roomType->name} decremented.");
                    }
                });
            }
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $oldStatus = $booking->status;
            
            \Illuminate\Support\Facades\DB::transaction(function () use ($booking, $oldStatus) {
                $booking->update([
                    'payment_status' => 'Unpaid',
                    'status'         => 'Cancelled',
                ]);

                // Re-increment room count if it was previously active (just in case)
                if ($oldStatus === 'Active') {
                    $roomType = \App\Models\RoomType::lockForUpdate()->findOrFail($booking->room_type_id);
                    $roomType->increment('available_rooms');
                    Log::info("Room type {$roomType->name} incremented because Active booking was cancelled.");
                }
            });

            Log::info("Booking ID {$booking->id} marked as Cancelled due to status: {$transactionStatus}");
        } elseif ($transactionStatus === 'pending') {
            $booking->update([
                'payment_status' => 'Unpaid',
                'status'         => 'Pending',
            ]);
            Log::info("Booking ID {$booking->id} is pending payment");
        }

        return response()->json(['message' => 'Notification processed successfully']);
    }
}
