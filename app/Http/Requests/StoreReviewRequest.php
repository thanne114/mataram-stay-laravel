<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isSeeker();
    }

    public function rules(): array
    {
        return [
            'booking_id'  => 'required|exists:bookings,id',
            'rating'      => 'required|integer|min:1|max:5',
            'comment'     => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Rating wajib diberikan.',
            'rating.min'      => 'Rating minimal 1 bintang.',
            'rating.max'      => 'Rating maksimal 5 bintang.',
            'comment.max'     => 'Komentar maksimal 1000 karakter.',
        ];
    }
}
