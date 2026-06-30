<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'seeker') {
                return redirect()->route('dashboard.seeker');
            } elseif (Auth::user()->role === 'owner') {
                return redirect()->route('dashboard.owner');
            } elseif (Auth::user()->role === 'admin') {
                return redirect()->route('dashboard.admin');
            }
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
                [
                    'name' => 'UNRAM',
                    'display' => 'Kos Dekat UNRAM Mataram',
                    'query' => 'UNRAM',
                    'logo' => asset('images/campuses/unram.png'),
                    'initials' => 'UR'
                ],
                [
                    'name' => 'UIN Mataram Kampus 1',
                    'display' => 'Kos Dekat UIN Mataram Kampus 1',
                    'query' => 'UIN_MATARAM_1',
                    'logo' => asset('images/campuses/uin.png'),
                    'initials' => 'U1'
                ],
                [
                    'name' => 'UIN Mataram Kampus 2',
                    'display' => 'Kos Dekat UIN Mataram Kampus 2',
                    'query' => 'UIN_MATARAM_2',
                    'logo' => asset('images/campuses/uin.png'),
                    'initials' => 'U2'
                ],
                [
                    'name' => 'Poltekkes Kemenkes Mataram',
                    'display' => 'Kos Dekat Poltekkes Kemenkes Mataram',
                    'query' => 'POLTEKKES_KEMENKES_MATARAM',
                    'logo' => asset('images/campuses/polnam.png'),
                    'initials' => 'PK'
                ],
                [
                    'name' => 'UT Mataram',
                    'display' => 'Kos Dekat UT Mataram',
                    'query' => 'UT_MATARAM',
                    'logo' => asset('images/campuses/ut.png'),
                    'initials' => 'UT'
                ],
            ],
            'pts' => [
                [
                    'name' => 'UMMAT',
                    'display' => 'Kos Dekat UMMAT Mataram',
                    'query' => 'UMMAT',
                    'logo' => asset('images/campuses/ummat.png'),
                    'initials' => 'UM'
                ],
                [
                    'name' => 'UTM',
                    'display' => 'Kos Dekat UTM Mataram',
                    'query' => 'UTM',
                    'logo' => asset('images/campuses/utm.png'),
                    'initials' => 'UTM'
                ],
                [
                    'name' => 'UNBIM Mataram',
                    'display' => 'Kos Dekat UNBIM Mataram',
                    'query' => 'UNBIM',
                    'logo' => asset('images/campuses/unbim.png'),
                    'initials' => 'UB'
                ],
                [
                    'name' => 'IAHN Gde Pudja',
                    'display' => 'Kos Dekat IAHN Gde Pudja Mataram',
                    'query' => 'IAHN_GDE_PUDJA',
                    'logo' => asset('images/campuses/iahn.png'),
                    'initials' => 'IA'
                ],
                [
                    'name' => 'INKES Yarsi Mataram',
                    'display' => 'Kos Dekat INKES Yarsi Mataram',
                    'query' => 'STIKES_YARSI',
                    'logo' => asset('images/campuses/stikes_yarsi.png'),
                    'initials' => 'IY'
                ],
                [
                    'name' => 'Universitas Mahasaraswati',
                    'display' => 'Kos Dekat Universitas Mahasaraswati Mataram',
                    'query' => 'UNMAS',
                    'logo' => asset('images/campuses/unmas.png'),
                    'initials' => 'UMS'
                ],
                [
                    'name' => 'STIKES Mataram',
                    'display' => 'Kos Dekat STIKES Mataram',
                    'query' => 'STIKES_MATARAM',
                    'logo' => asset('images/campuses/stikes_mataram.png'),
                    'initials' => 'SM'
                ],
            ]
        ];

        return view('kampus.index', compact('campuses'));
    }
}