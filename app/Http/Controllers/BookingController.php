<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\RoomType;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\BookingNotificationMail;

class BookingController extends Controller
{
    /**
     * Form pemesanan (GET /booking/create?room_type_id=X)
     */
    public function create(Request $request)
    {
        $roomType = RoomType::with('property.owner')->findOrFail($request->room_type_id);

        // Pastikan kamar tersedia
        if ($roomType->available_rooms <= 0) {
            return back()->with('error', 'Maaf, kamar ini sudah penuh.');
        }

        return view('bookings.create', compact('roomType'));
    }

    /**
     * Simpan pemesanan baru (POST /booking)
     */
    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();

        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            // Lock room type row in database for update to prevent race conditions
            $roomType = RoomType::lockForUpdate()->findOrFail($data['room_type_id']);

            // Pastikan kamar tersedia
            if ($roomType->available_rooms <= 0) {
                return back()->with('error', 'Maaf, kamar ini sudah penuh.');
            }

            // Hitung total harga
            $data['user_id'] = Auth::id();
            $roomSubtotal = $roomType->price_per_month * $data['duration_months'];
            $adminFee = (int) \App\Models\Setting::getValue('admin_fee', 2500);
            $commissionRate = (float) \App\Models\Setting::getValue('commission_rate', 5);
            $commissionFee = (int) round($roomSubtotal * ($commissionRate / 100));
            $netOwnerAmount = $roomSubtotal - $commissionFee;
            $totalPrice = $roomSubtotal + $adminFee;

            $data['room_subtotal'] = $roomSubtotal;
            $data['admin_fee'] = $adminFee;
            $data['commission_fee'] = $commissionFee;
            $data['net_owner_amount'] = $netOwnerAmount;
            $data['total_price'] = $totalPrice;
            $data['status'] = 'Pending';
            $data['payment_status'] = 'Unpaid';
            $data['is_approved'] = false;

            $booking = Booking::create($data);

            // Send notification email to Seeker & Owner
            try {
                Mail::to($booking->user->email)->send(new BookingNotificationMail($booking, 'created_seeker'));
                if ($booking->roomType->property->owner) {
                    Mail::to($booking->roomType->property->owner->email)->send(new BookingNotificationMail($booking, 'created_owner'));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send booking created emails: ' . $e->getMessage());
            }

            return redirect()->route('booking.show', $booking)
                ->with('success', 'Pemesanan berhasil dibuat! Silakan lakukan pembayaran.');
        });
    }

    /**
     * Detail pemesanan (GET /booking/{booking})
     */
    public function show(Booking $booking)
    {
        // Hanya pemesan atau pemilik kos yang bisa lihat
        $booking->load(['roomType.property.owner', 'user', 'review']);
        $property = $booking->roomType->property;

        if (Auth::id() !== $booking->user_id && Auth::id() !== $property->user_id) {
            abort(403);
        }

        // Generate Midtrans Snap Token jika seeker, disetujui, dan belum bayar (Unpaid atau Checking)
        if ($booking->is_approved && Auth::id() === $booking->user_id && in_array($booking->payment_status, ['Unpaid', 'Checking']) && $booking->status === 'Pending') {
            if ($booking->payment_token) {
                try {
                    \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
                    \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
                    
                    $status = \Midtrans\Transaction::status($booking->id . '-' . $booking->updated_at->timestamp);
                    
                    if (isset($status->transaction_status) && in_array($status->transaction_status, ['expire', 'cancel', 'deny'])) {
                        $booking->payment_token = null;
                        $booking->save(); // touches updated_at timestamp
                    }
                } catch (\Exception $e) {
                    if ($booking->updated_at->addDay()->isPast()) {
                        $booking->payment_token = null;
                        $booking->save();
                    }
                }
            }

            if (!$booking->payment_token) {
                try {
                    // Set konfigurasi midtrans
                    \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
                    \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
                    \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
                    \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

                    $params = [
                        'transaction_details' => [
                            'order_id' => $booking->id . '-' . $booking->updated_at->timestamp,
                            'gross_amount' => (int) $booking->total_price,
                        ],
                        'customer_details' => [
                            'first_name' => $booking->user->name,
                            'email' => $booking->user->email,
                            'phone' => $booking->user->no_whatsapp ?? '',
                        ],
                        'item_details' => [
                            [
                                'id' => (string) $booking->room_type_id,
                                'price' => (int) $booking->roomType->price_per_month,
                                'quantity' => (int) $booking->duration_months,
                                'name' => substr('Sewa ' . $property->name . ' - ' . $booking->roomType->name, 0, 50),
                            ],
                            [
                                'id' => 'admin-fee',
                                'price' => (int) $booking->admin_fee,
                                'quantity' => 1,
                                'name' => 'Biaya Layanan Admin',
                            ]
                        ],
                        'callbacks' => [
                            'finish' => route('booking.show', $booking),
                            'unfinish' => route('booking.show', $booking),
                            'error' => route('booking.show', $booking),
                        ]
                    ];

                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    $booking->payment_token = $snapToken;
                    $booking->save();
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Midtrans Token Generation Failed: ' . $e->getMessage());
                }
            }
        }

        return view('bookings.show', compact('booking', 'property'));
    }

    /**
     * Upload bukti pembayaran (POST /booking/{booking}/upload-proof)
     */
    public function uploadProof(Request $request, Booking $booking)
    {
        // Hanya pemesan yang bisa upload
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $booking->update([
            'payment_proof'  => $path,
            'payment_status' => 'Checking',
            'status'         => 'Pending',
        ]);

        try {
            if ($booking->roomType->property->owner) {
                Mail::to($booking->roomType->property->owner->email)->send(new BookingNotificationMail($booking, 'proof_uploaded'));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send proof uploaded email: ' . $e->getMessage());
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi pemilik kos.');
    }

    /**
     * Owner verifikasi pembayaran (POST /booking/{booking}/verify)
     */
    public function verify(Booking $booking)
    {
        $property = $booking->roomType->property;

        // Hanya pemilik kos yang bisa verifikasi
        if (Auth::id() !== $property->user_id) {
            abort(403);
        }

        if ($booking->payment_status === 'Paid' || $booking->status === 'Active') {
            return back()->with('error', 'Pemesanan ini sudah diverifikasi sebelumnya.');
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($booking) {
            // Lock room type row in database for update to prevent race conditions
            $roomType = RoomType::lockForUpdate()->findOrFail($booking->room_type_id);

            // Double check availability before confirming payment
            if ($roomType->available_rooms <= 0) {
                return back()->with('error', 'Maaf, kamar untuk tipe ini sudah penuh. Verifikasi tidak dapat dilanjutkan.');
            }

            $booking->update([
                'payment_status' => 'Paid',
                'status'         => 'Active',
            ]);

            // Kurangi kamar tersedia
            $roomType->decrement('available_rooms');

            try {
                Mail::to($booking->user->email)->send(new BookingNotificationMail($booking, 'payment_success_seeker'));
                if ($booking->roomType->property->owner) {
                    Mail::to($booking->roomType->property->owner->email)->send(new BookingNotificationMail($booking, 'payment_success_owner'));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send payment success emails: ' . $e->getMessage());
            }

            return back()->with('success', 'Pembayaran berhasil diverifikasi. Booking sekarang aktif.');
        });
    }

    /**
     * Owner update status booking (POST /booking/{booking}/update-status)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $property = $booking->roomType->property;

        if (Auth::id() !== $property->user_id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Active,Completed,Cancelled',
        ]);

        $oldStatus = $booking->status;

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $booking, $oldStatus) {
            $booking->update(['status' => $request->status]);

            // Jika dibatalkan atau selesai, tambah kamar tersedia kembali
            if (in_array($request->status, ['Completed', 'Cancelled']) && $oldStatus === 'Active') {
                $roomType = RoomType::lockForUpdate()->findOrFail($booking->room_type_id);
                $roomType->increment('available_rooms');
            }

            if ($request->status === 'Cancelled') {
                try {
                    Mail::to($booking->user->email)->send(new BookingNotificationMail($booking, 'cancelled_seeker'));
                } catch (\Exception $e) {
                    Log::error('Failed to send booking cancelled email: ' . $e->getMessage());
                }
            }

            return back()->with('success', 'Status booking berhasil diperbarui menjadi ' . $request->status . '.');
        });
    }

    /**
     * Seeker membatalkan booking pending secara manual (POST /booking/{booking}/cancel)
     */
    public function cancel(Booking $booking)
    {
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        if ($booking->status !== 'Pending') {
            return back()->with('error', 'Booking ini tidak dapat dibatalkan.');
        }

        $booking->update([
            'status'         => 'Cancelled',
            'payment_status' => 'Unpaid'
        ]);

        try {
            Mail::to($booking->user->email)->send(new BookingNotificationMail($booking, 'cancelled_seeker'));
        } catch (\Exception $e) {
            Log::error('Failed to send booking cancelled email: ' . $e->getMessage());
        }

        return back()->with('success', 'Pemesanan Anda telah berhasil dibatalkan.');
    }

    /**
     * Owner menyetujui pemesanan (POST /booking/{booking}/approve)
     */
    public function approve(Booking $booking)
    {
        $property = $booking->roomType->property;

        // Hanya pemilik kos yang bisa menyetujui
        if (Auth::id() !== $property->user_id) {
            abort(403);
        }

        if ($booking->is_approved) {
            return back()->with('error', 'Pemesanan ini sudah disetujui sebelumnya.');
        }

        $booking->update([
            'is_approved' => true,
        ]);

        try {
            Mail::to($booking->user->email)->send(new BookingNotificationMail($booking, 'approved_seeker'));
        } catch (\Exception $e) {
            Log::error('Failed to send booking approved email: ' . $e->getMessage());
        }

        return back()->with('success', 'Pesanan berhasil disetujui! Seeker telah diberi tahu untuk melakukan pembayaran.');
    }

    /**
     * Owner menolak pemesanan (POST /booking/{booking}/reject)
     */
    public function reject(Booking $booking)
    {
        $property = $booking->roomType->property;

        // Hanya pemilik kos yang bisa menolak
        if (Auth::id() !== $property->user_id) {
            abort(403);
        }

        if ($booking->status === 'Cancelled') {
            return back()->with('error', 'Pemesanan ini sudah dibatalkan sebelumnya.');
        }

        $oldStatus = $booking->status;

        return \Illuminate\Support\Facades\DB::transaction(function () use ($booking, $oldStatus) {
            $booking->update([
                'status' => 'Cancelled',
            ]);

            // Jika status sebelumnya adalah Active (kamar sudah terkurang), kembalikan ketersediaan kamar
            if ($oldStatus === 'Active') {
                $roomType = RoomType::lockForUpdate()->findOrFail($booking->room_type_id);
                $roomType->increment('available_rooms');
            }

            try {
                Mail::to($booking->user->email)->send(new BookingNotificationMail($booking, 'rejected_seeker'));
            } catch (\Exception $e) {
                Log::error('Failed to send booking rejected email: ' . $e->getMessage());
            }

            return back()->with('success', 'Pesanan berhasil ditolak.');
        });
    }
}
