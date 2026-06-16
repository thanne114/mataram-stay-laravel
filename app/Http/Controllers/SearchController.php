<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Halaman hasil pencarian dengan filter
     */
    public function index(Request $request)
    {
        $query = Property::where('status', 'published')
            ->with(['roomTypes', 'facilities', 'reviews']);

        $this->applySearchFilters($query, $request);

        $properties = $query->latest()->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('properties._property_list', compact('properties'))->render(),
                'total' => $properties->total()
            ]);
        }

        $facilitiesList = \App\Models\Facility::all();

        return view('properties.search_results', [
            'properties' => $properties,
            'filters'    => $request->only(['lokasi', 'tipe_kos', 'harga_maksimal', 'tersedia', 'kampus', 'fasilitas']),
            'facilitiesList' => $facilitiesList,
        ]);
    }

    /**
     * API endpoint untuk data peta Leaflet (JSON)
     */
    public function mapData(Request $request)
    {
        $query = Property::where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['roomTypes']);

        $this->applySearchFilters($query, $request);

        // Bounding box filter for interactive map panning using explicit numeric casts
        if ($request->filled(['north', 'south', 'east', 'west'])) {
            $latMin = min((float)$request->south, (float)$request->north);
            $latMax = max((float)$request->south, (float)$request->north);
            $lngMin = min((float)$request->west, (float)$request->east);
            $lngMax = max((float)$request->west, (float)$request->east);

            $query->whereRaw('CAST(latitude AS DECIMAL(10,6)) BETWEEN ? AND ?', [$latMin, $latMax])
                  ->whereRaw('CAST(longitude AS DECIMAL(10,6)) BETWEEN ? AND ?', [$lngMin, $lngMax]);
        }

        $properties = $query->get()->map(function ($property) {
            return [
                'id'        => $property->id,
                'name'      => $property->name,
                'slug'      => $property->slug,
                'type'      => $property->type,
                'area'      => $property->area,
                'lat'       => (float) $property->latitude,
                'lng'       => (float) $property->longitude,
                'price'     => $property->lowest_price,
                'available' => $property->available_rooms,
                'image'     => $property->main_image ? asset('storage/' . $property->main_image) : null,
            ];
        });

        return response()->json($properties);
    }

    /**
     * Helper to apply all search and campus hub filters
     */
    private function applySearchFilters($query, Request $request)
    {
        // Filter berdasarkan lokasi/kecamatan
        if ($request->filled('lokasi')) {
            $query->where('area', $request->lokasi);
        }

        // Filter berdasarkan tipe kos
        if ($request->filled('tipe_kos')) {
            $query->where('type', $request->tipe_kos);
        }

        // Filter berdasarkan harga maksimal (dari tipe kamar termurah)
        if ($request->filled('harga_maksimal') && (int)$request->harga_maksimal < 3000000) {
            $query->whereHas('roomTypes', function ($q) use ($request) {
                $q->where('price_per_month', '<=', $request->harga_maksimal);
            });
        }

        // Filter berdasarkan fasilitas (logical AND)
        if ($request->filled('fasilitas')) {
            $facilities = is_array($request->fasilitas) ? $request->fasilitas : explode(',', $request->fasilitas);
            foreach ($facilities as $facilityId) {
                if (!empty($facilityId)) {
                    $query->whereHas('facilities', function ($q) use ($facilityId) {
                        $q->where('facilities.id', $facilityId);
                    });
                }
            }
        }

        // Filter ketersediaan kamar
        if ($request->filled('tersedia')) {
            $query->whereHas('roomTypes', function ($q) {
                $q->where('available_rooms', '>', 0);
            });
        }

        // Filter Kampus Hub (Radius Geolocation Query)
        if ($request->filled('kampus')) {
            $campuses = [
                'UNRAM' => ['lat' => -8.587063, 'lng' => 116.092185],
                'UIN_MATARAM' => ['lat' => -8.609817, 'lng' => 116.100646],
                'UIN_MATARAM_1' => ['lat' => -8.582297, 'lng' => 116.094629],
                'UIN_MATARAM_2' => ['lat' => -8.609817, 'lng' => 116.100646],
                'UMMAT' => ['lat' => -8.5982, 'lng' => 116.1084],
                'POLNAM' => ['lat' => -8.5833, 'lng' => 116.0950],
                'UT_MATARAM' => ['lat' => -8.5796, 'lng' => 116.1026],
                'UTM' => ['lat' => -8.5835, 'lng' => 116.1054],
                'UNBIM' => ['lat' => -8.6050, 'lng' => 116.0850],
                'IAHN_GDE_PUDJA' => ['lat' => -8.5990, 'lng' => 116.1165],
                'STIKES_YARSI' => ['lat' => -8.6120, 'lng' => 116.1060],
                'UNMAS' => ['lat' => -8.5925, 'lng' => 116.1105],
            ];

            $selectedCampus = $request->kampus;
            if (array_key_exists($selectedCampus, $campuses)) {
                $lat = $campuses[$selectedCampus]['lat'];
                $lng = $campuses[$selectedCampus]['lng'];
                $radiusKm = 3.0; // Radius 3 KM

                $query->whereRaw("
                    (6371 * acos(
                        cos(radians(?)) * cos(radians(CAST(latitude AS DECIMAL(10,6)))) * 
                        cos(radians(CAST(longitude AS DECIMAL(10,6))) - radians(?)) + 
                        sin(radians(?)) * sin(radians(CAST(latitude AS DECIMAL(10,6))))
                    )) <= CAST(? AS DOUBLE)", [$lat, $lng, $lat, $radiusKm]);
            }
        }
    }
}
