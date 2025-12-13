<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(public CloudinaryService $cloudinary) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->wantsJson()) {
            return response()->json(Product::with('category')->latest()->get());
        }

        return view('menu.index', [
            'products' => Product::with('category')->latest()->get(),
        ]);
    }

    /**
     * API endpoint for frontend - returns only available products
     */
    public function apiIndex()
    {
        $products = Product::with('category')
            ->where('is_available', true)
            ->latest()
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'category_id' => $product->category_id,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                    ] : null,
                    'image' => $product->image_url,
                    'is_available' => $product->is_available,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            });

        return response()->json($products);
    }

    // ...

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:6144',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $image = $this->cloudinary->uploadImage(
                $request->file('image'),
                'products'
            );

            $validated['image_url'] = $image['url'];
            $validated['image_public_id'] = $image['public_id'];
        }

        Product::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Producto creado exitosamente.');
    }

    // ...

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:6144',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image from Cloudinary if exists
            if ($product->image_public_id) {
                $this->cloudinary->deleteImage($product->image_public_id);
            }

            $image = $this->cloudinary->uploadImage(
                $request->file('image'),
                'products'
            );

            $validated['image_url'] = $image['url'];
            $validated['image_public_id'] = $image['public_id'];
        }

        $product->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Producto actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete image from Cloudinary if exists
        if ($product->image_public_id) {
            $this->cloudinary->deleteImage($product->image_public_id);
        }

        $product->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Producto eliminado.');
    }
}
