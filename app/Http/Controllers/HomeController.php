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
                [
                    'name' => 'UNRAM',
                    'display' => 'Kos Dekat UNRAM Mataram',
                    'query' => 'UNRAM',
                    'logo' => 'https://upload.wikimedia.org/wikipedia/id/c/c2/LogoUnram.png',
                    'initials' => 'UR'
                ],
                [
                    'name' => 'UIN Mataram Kampus 1',
                    'display' => 'Kos Dekat UIN Mataram Kampus 1',
                    'query' => 'UIN_MATARAM_1',
                    'logo' => 'https://upload.wikimedia.org/wikipedia/id/a/a2/Logo_UIN_Mataram.png',
                    'initials' => 'U1'
                ],
                [
                    'name' => 'UIN Mataram Kampus 2',
                    'display' => 'Kos Dekat UIN Mataram Kampus 2',
                    'query' => 'UIN_MATARAM_2',
                    'logo' => 'https://upload.wikimedia.org/wikipedia/id/a/a2/Logo_UIN_Mataram.png',
                    'initials' => 'U2'
                ],
                [
                    'name' => 'Polnam',
                    'display' => 'Kos Dekat Polnam Mataram',
                    'query' => 'POLNAM',
                    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/e/ee/Logo_Politeknik_Kesehatan_Mataram.png',
                    'initials' => 'PN'
                ],
                [
                    'name' => 'UT Mataram',
                    'display' => 'Kos Dekat UT Mataram',
                    'query' => 'UT_MATARAM',
                    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c6/Logo_Universitas_Terbuka.svg/240px-Logo_Universitas_Terbuka.svg.png',
                    'initials' => 'UT'
                ],
            ],
            'pts' => [
                [
                    'name' => 'UMMAT',
                    'display' => 'Kos Dekat UMMAT Mataram',
                    'query' => 'UMMAT',
                    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/4/46/Logo_Universitas_Muhammadiyah_Mataram.png',
                    'initials' => 'UM'
                ],
                [
                    'name' => 'UTM',
                    'display' => 'Kos Dekat UTM Mataram',
                    'query' => 'UTM',
                    'logo' => 'https://utm.ac.id/wp-content/uploads/2023/10/logo-utm.png',
                    'initials' => 'UTM'
                ],
                [
                    'name' => 'UNBIM Mataram',
                    'display' => 'Kos Dekat UNBIM Mataram',
                    'query' => 'UNBIM',
                    'logo' => 'https://unbim.ac.id/wp-content/uploads/2023/10/logo-unbim.png',
                    'initials' => 'UB'
                ],
                [
                    'name' => 'IAHN Gde Pudja',
                    'display' => 'Kos Dekat IAHN Gde Pudja Mataram',
                    'query' => 'IAHN_GDE_PUDJA',
                    'logo' => 'https://iahn-gdepudja.ac.id/wp-content/uploads/2021/04/Logo-IAHN.png',
                    'initials' => 'IA'
                ],
                [
                    'name' => 'STIKES Yarsi Mataram',
                    'display' => 'Kos Dekat STIKES Yarsi Mataram',
                    'query' => 'STIKES_YARSI',
                    'logo' => asset('images/campuses/stikes_yarsi.png'),
                    'initials' => 'SY'
                ],
                [
                    'name' => 'Universitas Mahasaraswati',
                    'display' => 'Kos Dekat Universitas Mahasaraswati Mataram',
                    'query' => 'UNMAS',
                    'logo' => 'https://unmas.ac.id/wp-content/uploads/2021/08/cropped-logo-unmas-192x192.png',
                    'initials' => 'UMS'
                ],
            ]
        ];

        return view('kampus.index', compact('campuses'));
    }
}