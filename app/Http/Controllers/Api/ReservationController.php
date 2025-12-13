<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        return response()->json(Reservation::with('table')->orderBy('reservation_date', 'desc')->get());
    }

    public function getAvailableTables(Request $request)
    {
        $date = $request->input('date');
        $time = $request->input('time');
        $partySize = $request->input('party_size');

        if (! $date || ! $time || ! $partySize) {
            return response()->json(['message' => 'Missing parameters'], 400);
        }

        // Get strict 30-min slot time
        // The frontend passes time like "14:00" or similar.
        // We need to ensure format matches database (H:i:s).
        // Let's assume input is correct or basic parsing.

        // Find tables that:
        // 1. Have enough capacity
        // 2. Are not blocked by a reservation at this exact Date + Time
        // Note: The user's rule for "Strict Block" applies generally to the SLOTS.
        // But for choosing a specific TABLE, we need to check if THAT table is free.
        // However, the previous logic was "Global Block".
        // If the Global Block says "Available", it means *at least one* table is free?
        // Actually, the previous logic checked if *ANY* reservation existed for that Date+Time+Size.
        // If it did, it blocked the whole slot.
        // If the slot is OPEN, it means NO reservation exists for that Date+Time+Size.
        // BUT, there might be reservations for Date+Time+OTHER_SIZE (e.g. 2 ppl vs 4 ppl).
        // Logic: Find the "Best Fit" capacity.
        // User wants strict matching. If party is 3, and we have tables of [2, 4, 6],
        // we should ONLY show tables of 4. Not 6, not 10.

        // 1. Find the smallest capacity that can hold the party
        $bestFitCapacity = Table::where('capacity', '>=', $partySize)
            ->where('status', '!=', 'maintenance')
            ->min('capacity');

        if (! $bestFitCapacity) {
            return response()->json([]); // No table big enough
        }

        $reservedTableIds = Reservation::where('reservation_date', $date)
            ->where('reservation_time', $time)
            ->where('status', '!=', 'cancelled')
            ->pluck('table_id')
            ->toArray();

        // 2. Return ONLY tables with that specific capacity
        $tables = Table::where('capacity', $bestFitCapacity)
            ->where('status', '!=', 'maintenance')
            ->orderBy('table_number', 'asc')
            ->get();

        $tablesWithAvailability = $tables->map(function ($table) use ($reservedTableIds) {
            $isReservedForSlot = in_array($table->id, $reservedTableIds);

            // Allow general status to override?
            // If table.status == 'reserved' (permanently?), maybe blocked too.
            // Assuming table.status enum ['available', 'reserved', 'maintenance']
            // 'reserved' might just mean "Currently Occupied" in the live view,
            // but for FUTURE reservations, we check the slot.
            // However, 'maintenance' should definitely be blocked.
            $isMaintenance = $table->status === 'maintenance';

            return [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'capacity' => $table->capacity,
                'location' => $table->location,
                'status' => $table->status, // General status
                'is_blocked' => $isReservedForSlot || $isMaintenance,
                'block_reason' => $isMaintenance ? 'Mantenimiento' : ($isReservedForSlot ? 'Reservado' : null),
            ];
        });

        return response()->json($tablesWithAvailability);
    }

    public function getTables()
    {
        return response()->json(Table::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'party_size' => 'required|integer|min:1',
            'special_request' => 'nullable|string',
        ]);

        // Double check availability before confirming (race condition check)
        $isAvailable = $this->checkTableAvailability(
            $validated['table_id'],
            $validated['reservation_date'],
            $validated['reservation_time']
        );

        if (! $isAvailable) {
            return response()->json(['message' => 'Selected slot is no longer available'], 409);
        }

        $reservation = Reservation::create($validated);

        return response()->json($reservation, 201);
    }

    public function checkAvailability(Request $request)
    {
        $date = $request->input('date');
        $partySize = $request->input('party_size');

        if (! $date || ! $partySize) {
            return response()->json(['message' => 'Se requiere fecha y tamaño del grupo'], 400);
        }

        // 1. Get a candidate table just for the ID (frontend needs it)
        $candidateTable = Table::where('capacity', '>=', $partySize)
            ->where('status', 'available')
            ->orderBy('capacity', 'asc')
            ->first();

        // 2. Define Operating Hours
        $start = Carbon::createFromTime(11, 0, 0);
        $end = Carbon::createFromTime(22, 0, 0);
        $interval = 30; // minutes
        $duration = 90; // minutes per reservation

        $allSlots = [];

        // 3. Loop through slots and check strict availability
        while ($start < $end) {
            $slotTime = $start->format('H:i:s');

            // STRICT CHECK: The user wants to block the slot if ANY reservation
            // has been made with the SAME Date, Time, and Party Size.
            // "recuerda es media hora cada media hora" -> We check exact 30-min slot.
            $conflict = Reservation::where('reservation_date', $date)
                ->where('party_size', $partySize) // Strict match on party size
                ->where('status', '!=', 'cancelled')
                ->where('reservation_time', $slotTime) // Strict match on time (e.g. 11:00 matches 11:00 only)
                ->exists();

            $allSlots[] = [
                'time' => $start->format('h:i A'),
                'value' => $slotTime,
                'table_id' => $candidateTable ? $candidateTable->id : 0,
                'is_available' => ! $conflict && $candidateTable !== null, // Disabled if conflict OR no table exists
                'remaining_capacity' => $candidateTable ? $candidateTable->capacity : 0,
            ];

            $start->addMinutes($interval);
        }

        return response()->json(['available_slots' => $allSlots]);
    }

    private function checkTableAvailability($tableId, $date, $time, $partySize = null)
    {
        // STRICT LOGIC: If the user wants to block "Date + Time + PartySize",
        // we must check strictly for that combination.
        // We ignore $tableId here because the user's logic is "Global Block for this Group Size".

        $query = Reservation::where('reservation_date', $date)
            ->where('status', '!=', 'cancelled')
            ->where('reservation_time', $time);

        if ($partySize) {
            $query->where('party_size', $partySize);
        } else {
            // Fallback if partySize isn't passed (legacy calls?), though we should pass it.
            // If we don't check partySize, we might block 2 people because 4 people booked.
            // So really, we need party_size to be passed.
        }

        return ! $query->exists();
    }
}
