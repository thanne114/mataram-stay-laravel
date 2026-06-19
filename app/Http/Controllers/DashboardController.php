<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ModerationNotificationMail;

class DashboardController extends Controller
{
    /**
     * Dashboard Pencari Kos (Seeker)
     */
    public function seeker() 
    {
        $user = Auth::user();

        // Ambil booking aktif (pending/menunggu verifikasi)
        $activeBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['Pending', 'Active'])
            ->with(['roomType.property'])
            ->latest()
            ->get();

        // Ambil properti terpopuler untuk ditampilkan
        $popularProperties = Property::where('status', 'published')
            ->with(['roomTypes', 'reviews', 'facilities'])
            ->withCount('reviews')
            ->orderByDesc('reviews_count')
            ->take(4)
            ->get();

        // Cek apakah ada transaksi pending/unpaid
        $hasPendingTransaction = Booking::where('user_id', $user->id)
            ->where(function($q) {
                $q->where('status', 'Pending')
                  ->orWhere('payment_status', 'Unpaid');
            })
            ->exists();

        return view('dashboard.seeker', compact('activeBookings', 'popularProperties', 'hasPendingTransaction'));
    }

    public function owner() 
    {
        $user = Auth::user();

        // Ambil semua properti milik owner dengan relasi (atau seluruh properti jika admin)
        if ($user->role === 'admin') {
            $properties = Property::with(['roomTypes', 'facilities', 'images'])
                ->latest()
                ->get();
        } else {
            $properties = Property::where('user_id', $user->id)
                ->with(['roomTypes', 'facilities', 'images'])
                ->latest()
                ->get();
        }

        // Hitung statistik
        $totalProperties = $properties->count();
        $propertyIds = $properties->pluck('id');
        
        if ($user->role === 'admin') {
            $activeBookings = Booking::whereIn('status', ['Pending', 'Active'])
                ->count();
            
            $allBookingsQuery = Booking::query();
        } else {
            $activeBookings = Booking::whereHas('roomType', function ($query) use ($propertyIds) {
                    $query->whereIn('property_id', $propertyIds);
                })
                ->whereIn('status', ['Pending', 'Active'])
                ->count();
            
            $allBookingsQuery = Booking::whereHas('roomType', function ($query) use ($propertyIds) {
                    $query->whereIn('property_id', $propertyIds);
                });
        }

        $totalRevenue = (int) $allBookingsQuery->clone()->where('payment_status', 'Paid')->sum('net_owner_amount');
        $successCount = $allBookingsQuery->clone()->where('payment_status', 'Paid')->count();
        $pendingCount = $allBookingsQuery->clone()->whereIn('status', ['Pending'])->count();

        // Ambil semua booking untuk properti milik owner (atau seluruh booking jika admin)
        if ($user->role === 'admin') {
            $bookings = Booking::with(['user', 'roomType.property'])
                ->latest()
                ->paginate(15);
        } else {
            $bookings = Booking::whereHas('roomType', function ($query) use ($propertyIds) {
                    $query->whereIn('property_id', $propertyIds);
                })
                ->with(['user', 'roomType.property'])
                ->latest()
                ->paginate(15);
        }

        return view('owner_portal', [
            'properties'      => $properties,
            'totalProperties' => $totalProperties,
            'activeBookings'  => $activeBookings,
            'bookings'        => $bookings,
            'totalRevenue'    => $totalRevenue,
            'successCount'    => $successCount,
            'pendingCount'    => $pendingCount,
            'role'            => 'owner',
        ]);
    }

    /**
     * Dashboard Administrator (Admin)
     */
    public function admin()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Statistics
        $totalSeekers = User::where('role', 'seeker')->count();
        $pendingSeekersCount = User::where('role', 'seeker')
            ->whereNotNull('identity_photo')
            ->where('is_verified', false)
            ->count();
        $totalOwners = User::where('role', 'owner')->count();
        $totalProperties = Property::count();
        $draftPropertiesCount = Property::where('status', 'draft')->count();

        // Monetization settings
        $adminFee = (int) Setting::getValue('admin_fee', 2500);
        $commissionRate = (int) Setting::getValue('commission_rate', 5);

        // Platform revenues
        $allPaidBookings = Booking::where('payment_status', 'Paid')->get();
        $totalAdminFeesCollected = $allPaidBookings->sum('admin_fee');
        $totalCommissionsCollected = $allPaidBookings->sum('commission_fee');
        $totalRevenuePlatform = $totalAdminFeesCollected + $totalCommissionsCollected;

        // Pending queues
        $pendingSeekers = User::where('role', 'seeker')
            ->whereNotNull('identity_photo')
            ->where('is_verified', false)
            ->latest()
            ->get();

        $draftProperties = Property::where('status', 'draft')
            ->with('owner')
            ->latest()
            ->get();

        return view('admin_dashboard', compact(
            'totalSeekers',
            'pendingSeekersCount',
            'totalOwners',
            'totalProperties',
            'draftPropertiesCount',
            'adminFee',
            'commissionRate',
            'totalAdminFeesCollected',
            'totalCommissionsCollected',
            'totalRevenuePlatform',
            'pendingSeekers',
            'draftProperties'
        ));
    }

    /**
     * Verify Seeker Identity
     */
    public function verifySeeker(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $user->is_verified = true;
        $user->save();

        try {
            Mail::to($user->email)->send(new ModerationNotificationMail($user, 'seeker_verified'));
        } catch (\Exception $e) {
            Log::error('Failed to send seeker verified email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', "Identitas user {$user->name} berhasil diverifikasi!");
    }

    /**
     * Approve Property Listing (Draft -> Published)
     */
    public function approveProperty(Property $property)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $property->status = 'published';
        $property->save();

        try {
            if ($property->owner) {
                Mail::to($property->owner->email)->send(new ModerationNotificationMail($property, 'property_approved'));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send property approved email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', "Properti {$property->name} telah berhasil disetujui dan dipublikasikan!");
    }

    /**
     * Reject Property Listing (Draft -> Rejected)
     */
    public function reject(Request $request, Property $property)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $property->status = 'rejected';
        $property->rejection_reason = $request->rejection_reason;
        $property->save();

        try {
            if ($property->owner) {
                Mail::to($property->owner->email)->send(new ModerationNotificationMail($property, 'property_rejected'));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send property rejected email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', "Properti berhasil ditolak dengan alasan.");
    }

    /**
     * Update Global Monetization Settings
     */
    public function updateSettings(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'admin_fee' => 'required|integer|min:0',
            'commission_rate' => 'required|integer|min:0|max:100',
        ]);

        Setting::setValue('admin_fee', $request->admin_fee);
        Setting::setValue('commission_rate', $request->commission_rate);

        return redirect()->back()->with('success', 'Pengaturan monetisasi berhasil diperbarui!');
    }
}