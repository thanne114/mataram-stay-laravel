<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role === 'seeker') {
            return redirect('/dashboard-seeker');
        }

        // Jika Guest: tampilkan landing page dengan properti populer
        $popularProperties = Property::where('status', 'published')
            ->with(['roomTypes', 'reviews'])
            ->withCount('reviews')
            ->orderByDesc('reviews_count')
            ->take(4)
            ->get();

        return view('home', compact('popularProperties'));
    }

    public function kampusDirectory()
    {
        $campuses = [
            'ptn' => [
                ['name' => 'UNRAM', 'display' => 'Kos Dekat UNRAM Mataram', 'query' => 'UNRAM'],
                ['name' => 'UIN Mataram Kampus 1', 'display' => 'Kos Dekat UIN Mataram Kampus 1', 'query' => 'UIN_MATARAM_1'],
                ['name' => 'UIN Mataram Kampus 2', 'display' => 'Kos Dekat UIN Mataram Kampus 2', 'query' => 'UIN_MATARAM_2'],
                ['name' => 'Polnam', 'display' => 'Kos Dekat Polnam Mataram', 'query' => 'POLNAM'],
                ['name' => 'UT Mataram', 'display' => 'Kos Dekat UT Mataram', 'query' => 'UT_MATARAM'],
            ],
            'pts' => [
                ['name' => 'UMMAT', 'display' => 'Kos Dekat UMMAT Mataram', 'query' => 'UMMAT'],
                ['name' => 'UTM', 'display' => 'Kos Dekat UTM Mataram', 'query' => 'UTM'],
                ['name' => 'UNBIM Mataram', 'display' => 'Kos Dekat UNBIM Mataram', 'query' => 'UNBIM'],
                ['name' => 'IAHN Gde Pudja', 'display' => 'Kos Dekat IAHN Gde Pudja Mataram', 'query' => 'IAHN_GDE_PUDJA'],
                ['name' => 'STIKES Yarsi Mataram', 'display' => 'Kos Dekat STIKES Yarsi Mataram', 'query' => 'STIKES_YARSI'],
                ['name' => 'Universitas Mahasaraswati', 'display' => 'Kos Dekat Universitas Mahasaraswati Mataram', 'query' => 'UNMAS'],
            ]
        ];

        return view('kampus.index', compact('campuses'));
    }
}