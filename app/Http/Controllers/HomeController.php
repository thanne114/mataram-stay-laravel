<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {

        // Jika Guest: tampilkan landing page dengan properti populer
        $popularProperties = Property::where('status', 'published')
            ->with(['roomTypes', 'reviews'])
            ->withCount('reviews')
            ->orderByDesc('reviews_count')
            ->take(4)
            ->get();

        return view('home', compact('popularProperties'));
    }
}