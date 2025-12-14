<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;

class PrinterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:ticket,kitchen,bar',
            'connection_type' => 'required|string|in:network,usb,bluetooth',
            'ip_address' => 'nullable|required_if:connection_type,network|ip',
            'port' => 'nullable|required_if:connection_type,network|integer',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['port'])) {
            $validated['port'] = 9100; // Default port
        }

        Printer::create($validated);

        return back()->with('success', 'Impresora agregada correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Printer $printer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:ticket,kitchen,bar',
            'connection_type' => 'required|string|in:network,usb,bluetooth',
            'ip_address' => 'nullable|required_if:connection_type,network|ip',
            'port' => 'nullable|required_if:connection_type,network|integer',
            'is_active' => 'boolean',
        ]);

        $printer->update($validated);

        return back()->with('success', 'Impresora actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Printer $printer)
    {
        $printer->delete();

        return back()->with('success', 'Impresora eliminada correctamente.');
    }

    /**
     * Toggle the active status of the printer.
     */
    public function toggle(Request $request, Printer $printer)
    {
        $printer->update([
            'is_active' => !$printer->is_active,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $printer->is_active,
                'message' => 'Estado actualizado correctamente.'
            ]);
        }

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
