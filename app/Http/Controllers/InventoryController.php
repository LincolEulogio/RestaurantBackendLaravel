<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
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
