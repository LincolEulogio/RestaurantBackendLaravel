<?php

namespace App\Http\Controllers;

use App\Exports\InventoryExport;
use App\Models\InventoryItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new InventoryExport, 'inventario-'.now()->format('d-m-Y').'.xlsx');
    }

    public function exportPdf()
    {
        $items = InventoryItem::all();
        $pdf = Pdf::loadView('inventory.pdf', compact('items'));

        return $pdf->download('inventario-'.now()->format('d-m-Y').'.pdf');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->wantsJson()) {
            return response()->json(InventoryItem::latest()->get());
        }

        return view('inventory.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:inventory_items,sku',
            'category' => 'nullable|string',
            'stock_current' => 'required|numeric|min:0',
            'stock_min' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'price_unit' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        InventoryItem::create($validated);

        return response()->json(['success' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inventoryItem = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'sku' => 'sometimes|required|string|unique:inventory_items,sku,'.$id,
            'category' => 'nullable|string',
            'stock_current' => 'sometimes|required|numeric|min:0',
            'stock_min' => 'sometimes|required|numeric|min:0',
            'unit' => 'sometimes|required|string',
            'price_unit' => 'sometimes|required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $inventoryItem->update($validated);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inventoryItem = InventoryItem::findOrFail($id);
        $inventoryItem->delete();

        return response()->json(['success' => true]);
    }
}
