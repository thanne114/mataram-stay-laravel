<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Daftar transaksi untuk Owner — semua booking masuk ke propertinya
     */
    public function ownerIndex()
    {
        return redirect()->route('dashboard.owner')->with('active_tab', 'transaksi');
    }

    /**
     * Daftar transaksi untuk Seeker — semua booking miliknya
     */
    public function seekerIndex()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['roomType.property', 'review'])
            ->latest()
            ->paginate(15);

        return view('dashboard.transactions', [
            'bookings' => $bookings,
            'role'     => 'seeker',
        ]);
    }
}
