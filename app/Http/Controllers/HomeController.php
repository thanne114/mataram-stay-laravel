<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Jika User sudah login sebagai seeker, redirect ke dashboard seeker
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
}