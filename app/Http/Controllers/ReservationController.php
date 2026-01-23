<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations.
     */
    public function index(Request $request)
    {
        $query = Reservation::with('table')
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by specific date
        if ($request->filled('date')) {
            $query->whereDate('reservation_date', $request->date);
        }

        // Search (by customer or ID)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter by table
        if ($request->filled('table_id') && $request->table_id !== 'all') {
            $query->where('table_id', $request->table_id);
        }

        $reservations = $query->paginate(10)->appends($request->except('page'));

        // Statistics
        $totalReservations = Reservation::count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $confirmedReservations = Reservation::where('status', 'confirmed')->count();
        $cancelledReservations = Reservation::where('status', 'cancelled')->count();
        $todayReservations = Reservation::whereDate('reservation_date', today())->count();
        $tables = Table::orderBy('table_number', 'asc')->get();

        return view('reservations.index', compact(
            'reservations',
            'totalReservations',
            'pendingReservations',
            'confirmedReservations',
            'cancelledReservations',
            'todayReservations',
            'tables'
        ));
    }

    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'party_size' => 'required|integer|min:1',
            'special_request' => 'nullable|string',
        ]);

        Reservation::create($validated);

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation created successfully');
    }

    /**
     * Display the specified reservation.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load('table');
        return view('reservations.show', compact('reservation'));
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
