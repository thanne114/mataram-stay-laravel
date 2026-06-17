<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Facility;
use App\Models\PropertyImage;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ModerationNotificationMail;

class PropertyController extends Controller
{
    /**
     * Form tambah properti baru (GET /property/create)
     */
    public function create()
    {
        $facilities = Facility::orderBy('name')->get();
        return view('dashboard.add_property', compact('facilities'));
    }

    /**
     * Simpan properti baru ke database (POST /property)
     */
    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();

        // Upload foto utama jika ada
        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('properties', 'public');
        }

        // Parse koordinat dari input
        $this->parseCoordinates($request, $data);

        // Buat properti
        $data['user_id'] = Auth::id();
        $data['status'] = 'draft'; // Requires admin approval to publish
        $property = Property::create($data);

        // Send email notification to Admin(s)
        try {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new ModerationNotificationMail($property, 'property_submitted_admin'));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send property submitted email to admin: ' . $e->getMessage());
        }

        // Buat tipe kamar default
        $property->roomTypes()->create([
            'name'            => $data['room_name'],
            'price_per_month' => $data['price_per_month'],
            'total_rooms'     => $data['total_rooms'],
            'available_rooms' => $data['available_rooms'],
        ]);

        // Attach fasilitas (checkbox)
        if (!empty($data['facilities'])) {
            $property->facilities()->attach($data['facilities']);
        }

        // Upload galeri foto tambahan
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('properties/gallery', 'public');
                $property->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('dashboard.owner')
            ->with('success', 'Properti "' . $property->name . '" berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail properti untuk publik (GET /kos/{slug})
     */
    public function show(Property $kos)
    {
        // Hanya tampilkan properti yang sudah dipublish
        if ($kos->status !== 'published' && (!Auth::check() || (Auth::id() !== $kos->user_id && !Auth::user()->isAdmin()))) {
            abort(404);
        }

        $kos->load(['owner', 'roomTypes', 'facilities', 'images', 'reviews.user']);

        $title = $kos->name . ' - Mataram Stay';
        $meta_description = \Illuminate\Support\Str::limit(strip_tags($kos->description), 150, '');

        return view('properties.show', [
            'property' => $kos,
            'title' => $title,
            'meta_description' => $meta_description,
        ]);
    }

    /**
     * Form edit properti (GET /property/{property}/edit)
     */
    public function edit(Property $property)
    {
        $this->authorize('update', $property);

        $facilities = Facility::orderBy('name')->get();
        $property->load(['roomTypes', 'facilities', 'images']);
        $selectedFacilities = $property->facilities->pluck('id')->toArray();

        return view('dashboard.edit_property', compact('property', 'facilities', 'selectedFacilities'));
    }

    /**
     * Update properti di database (PUT /property/{property})
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $this->authorize('update', $property);

        $data = $request->validated();

        // Upload foto utama baru jika ada
        if ($request->hasFile('main_image')) {
            // Hapus foto lama
            if ($property->main_image) {
                Storage::disk('public')->delete($property->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('properties', 'public');
        }

        // Parse koordinat
        $this->parseCoordinates($request, $data);

        // Update properti
        $property->update($data);

        // Update tipe kamar (update yang pertama atau buat baru)
        $roomType = $property->roomTypes->first();
        if ($roomType) {
            $roomType->update([
                'name'            => $data['room_name'],
                'price_per_month' => $data['price_per_month'],
                'total_rooms'     => $data['total_rooms'],
                'available_rooms' => $data['available_rooms'],
            ]);
        } else {
            $property->roomTypes()->create([
                'name'            => $data['room_name'],
                'price_per_month' => $data['price_per_month'],
                'total_rooms'     => $data['total_rooms'],
                'available_rooms' => $data['available_rooms'],
            ]);
        }

        // Sync fasilitas
        $property->facilities()->sync($data['facilities'] ?? []);

        // Upload galeri foto tambahan
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('properties/gallery', 'public');
                $property->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('dashboard.owner')
            ->with('success', 'Properti "' . $property->name . '" berhasil diperbarui!');
    }

    /**
     * Hapus properti (DELETE /property/{property})
     */
    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        $property->delete();

        return redirect()->route('dashboard.owner')
            ->with('success', 'Properti berhasil diarsipkan.');
    }

    /**
     * Parse koordinat dari input (bisa berupa URL Google Maps atau lat,lng)
     */
    private function parseCoordinates($request, &$data): void
    {
        $koordinat = $request->input('koordinat_input');
        if ($koordinat) {
            // Cek URL Google Maps: @-8.5833,116.1167
            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $koordinat, $matches)) {
                $data['latitude'] = $matches[1];
                $data['longitude'] = $matches[2];
            }
            // Cek format: -8.5833, 116.1167
            elseif (preg_match('/^(-?\d+\.\d+)[,\s]+(-?\d+\.\d+)$/', trim($koordinat), $matches)) {
                $data['latitude'] = $matches[1];
                $data['longitude'] = $matches[2];
            }
        }
    }
}
