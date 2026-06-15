<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOwner();
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'type'          => 'required|in:Putra,Putri,Campur',
            'area'          => 'required|in:Mataram,Ampenan,Cakranegara,Selaparang,Sekarbela,Sandubaya',
            'address'       => 'required|string',
            'latitude'      => 'nullable|string|max:50',
            'longitude'     => 'nullable|string|max:50',
            'description'   => 'nullable|string',
            'main_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // Tipe Kamar (array)
            'room_name'          => 'required|string|max:255',
            'price_per_month'    => 'required|integer|min:100000',
            'total_rooms'        => 'required|integer|min:1',
            'available_rooms'    => 'required|integer|min:0',

            // Fasilitas (checkbox array)
            'facilities'    => 'nullable|array',
            'facilities.*'  => 'exists:facilities,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Nama kos wajib diisi.',
            'type.required'          => 'Tipe kos wajib dipilih.',
            'area.required'          => 'Kecamatan wajib dipilih.',
            'address.required'       => 'Alamat lengkap wajib diisi.',
            'price_per_month.required' => 'Harga per bulan wajib diisi.',
            'price_per_month.min'    => 'Harga minimal Rp 100.000.',
            'total_rooms.required'   => 'Total kamar wajib diisi.',
            'main_image.image'       => 'File harus berupa gambar.',
            'main_image.max'         => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
