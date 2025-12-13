<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations.
     */
    public function index(Request $request)
    {
        $query = Reservation::with('table')->orderBy('reservation_date', 'asc')->orderBy('reservation_time', 'asc');

        // Filter by date (default to today if no filters? No, show all upcoming maybe? Let's show all desc or filtered)
        // If sorting by date desc, we see newest bookings first.
        // Actually, for reservations, seeing upcoming is usually better.
        // Let's stick to created_at desc for "management" like orders, or reservation_date desc.
        // Let's use reservation_date desc for now to see latest dates.
        if (! $request->has('sort')) {
            $query->orderBy('reservation_date', 'desc');
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by specific date
        if ($request->has('date')) {
            $query->where('reservation_date', $request->date);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $reservations = $query->paginate(20);

        // Statistics
        $totalReservations = Reservation::count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $confirmedReservations = Reservation::where('status', 'confirmed')->count();
        $cancelledReservations = Reservation::where('status', 'cancelled')->count();
        $todayReservations = Reservation::whereDate('reservation_date', today())->count();

        return view('reservations.index', compact(
            'reservations',
            'totalReservations',
            'pendingReservations',
            'confirmedReservations',
            'cancelledReservations',
            'todayReservations'
        ));
    }

    /**
     * Update the reservation status.
     */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $reservation->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Restervation status updated successfully');
    }

    /**
     * Remove the specified reservation.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation deleted successfully');
    }
}
