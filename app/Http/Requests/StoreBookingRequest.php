<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isSeeker();
    }

    public function rules(): array
    {
        return [
            'room_type_id'    => 'required|exists:room_types,id',
            'check_in_date'   => 'required|date|after_or_equal:today',
            'duration_months' => 'required|integer|min:1|max:24',
        ];
    }

    public function messages(): array
    {
        return [
            'room_type_id.required'    => 'Tipe kamar wajib dipilih.',
            'room_type_id.exists'      => 'Tipe kamar tidak valid.',
            'check_in_date.required'   => 'Tanggal check-in wajib diisi.',
            'check_in_date.after_or_equal' => 'Tanggal check-in tidak boleh di masa lalu.',
            'duration_months.required' => 'Durasi sewa wajib diisi.',
            'duration_months.min'      => 'Durasi sewa minimal 1 bulan.',
        ];
    }
}
