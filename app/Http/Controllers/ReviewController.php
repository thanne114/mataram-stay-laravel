<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ReviewNotificationMail;

class ReviewController extends Controller
{
    /**
     * Simpan ulasan baru
     */
    public function store(StoreReviewRequest $request)
    {
        $data = $request->validated();
        $booking = Booking::findOrFail($data['booking_id']);

        // Pastikan booking milik user, statusnya Completed dan status pembayaran Paid
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        if ($booking->status !== 'Completed' || $booking->payment_status !== 'Paid') {
            return back()->with('error', 'Anda hanya bisa memberikan ulasan untuk booking yang sudah selesai dan lunas.');
        }

        // Pastikan belum pernah review booking ini
        if ($booking->review) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk booking ini.');
        }

        $data['user_id'] = Auth::id();
        $data['property_id'] = $booking->roomType->property_id;
        if (isset($data['comment'])) {
            $data['comment'] = strip_tags($data['comment']);
        }

        $review = Review::create($data);

        // Send email notification to Owner
        try {
            if ($review->property && $review->property->owner) {
                Mail::to($review->property->owner->email)->send(new ReviewNotificationMail($review));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send review notification email to owner: ' . $e->getMessage());
        }

        return back()->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }
}
