<?php

namespace App\Http\Controllers;

use App\Models\RestaurantValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RestaurantValueController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        RestaurantValue::create($validated);
        Cache::forget('landing_content');

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $value = RestaurantValue::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'icon' => 'sometimes|required|string|max:255',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $value->update(array_filter($validated, fn($value) => !is_null($value)));
        Cache::forget('landing_content');

        return response()->json(['success' => true, 'data' => $value]);
    }

    public function destroy($id)
    {
        $value = RestaurantValue::findOrFail($id);
        $value->delete();
        Cache::forget('landing_content');
        return response()->json(['success' => true]);
    }
}
