<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            return response()->json(Category::where('is_active', true)->get());
        }
        return view('categories.index');
    }

    /**
     * API endpoint for frontend - returns active categories
     */
    public function apiIndex()
    {
        $categories = Category::where('is_active', true)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                ];
            });

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'required|string|unique:categories,slug',
            'is_active' => 'boolean',
        ]);

        Category::create($validated);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'sometimes|required|string|unique:categories,slug,' . $category->id,
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['success' => true]);
    }
}
