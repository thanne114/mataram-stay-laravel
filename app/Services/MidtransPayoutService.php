<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransPayoutService
{
    /**
     * Send payout request to Midtrans Iris API.
     * Returns reference_no on success, or false on failure.
     */
    public static function sendPayout(Booking $booking)
    {
        $apiKey = config('services.midtrans.iris_api_key');
        if (empty($apiKey)) {
            Log::error('Midtrans Iris Payout: MIDTRANS_IRIS_API_KEY is not configured in config/services.php');
            return false;
        }

        $isProduction = config('services.midtrans.is_production', false);
        $baseUrl = $isProduction 
            ? 'https://app.midtrans.com/iris/api/v1' 
            : 'https://app.sandbox.midtrans.com/iris/api/v1';

        $owner = $booking->roomType?->property?->owner;

        if (!$owner || empty($owner->bank_name) || empty($owner->bank_account_number) || empty($owner->bank_account_name)) {
            Log::error("Midtrans Iris Payout failed: Booking #{$booking->id} owner bank details are missing or incomplete.");
            return false;
        }

        try {
            $payload = [
                'payouts' => [
                    [
                        'beneficiary_name' => $owner->bank_account_name,
                        'beneficiary_account' => $owner->bank_account_number,
                        'beneficiary_bank' => strtolower($owner->bank_name),
                        'amount' => number_format($booking->net_owner_amount, 2, '.', ''),
                        'notes' => 'Payout Booking ' . $booking->id
                    ]
                ]
            ];

            Log::info("Sending Payout request for Booking #{$booking->id} to URL: {$baseUrl}/payouts");

            $response = Http::withBasicAuth($apiKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($baseUrl . '/payouts', $payload);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Midtrans Iris Payout success response for Booking #{$booking->id}: " . json_encode($data));

                if (isset($data['payouts'][0]['reference_no'])) {
                    return $data['payouts'][0]['reference_no'];
                }

                if (isset($data['reference_no'])) {
                    return $data['reference_no'];
                }

                return 'iris-ref-' . uniqid();
            } else {
                Log::error("Midtrans Iris Payout failed with status code {$response->status()}: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Midtrans Iris Payout exception for Booking #{$booking->id}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return false;
        }
    }
}
