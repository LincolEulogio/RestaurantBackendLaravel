<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Cache;

use App\Models\AuditLog;

class ProductController extends Controller
{
    public function __construct(public CloudinaryService $cloudinary) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->wantsJson() || request()->ajax()) {
            return ProductResource::collection(Product::with('category')->latest()->get());
        }

        return view('menu.index', [
            'products' => Product::with('category')->latest()->get(),
        ]);
    }

    /**
     * API endpoint for frontend - returns only available products
     * 
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get all available products",
     *     description="Returns list of all available products with category information",
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filter by category ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="available",
     *         in="query",
     *         description="Filter by availability (1=available, 0=unavailable)",
     *         required=false,
     *         @OA\Schema(type="integer", enum={0, 1})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Pizza Margherita"),
     *                     @OA\Property(property="description", type="string", example="Deliciosa pizza con tomate y queso"),
     *                     @OA\Property(property="price", type="string", example="25.00"),
     *                     @OA\Property(property="image", type="string", example="https://res.cloudinary.com/..."),
     *                     @OA\Property(property="available", type="boolean", example=true),
     *                     @OA\Property(
     *                         property="category",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Pizzas")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function apiIndex()
    {
        $products = Cache::remember('api_products', 3600, function () {
            return Product::with('category')
                ->where('is_available', true)
                ->latest()
                ->get();
        });

        return ProductResource::collection($products);
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

        $product = Product::create($validated);

        // Clear all caches
        Cache::forget('api_products');
        Cache::flush(); // Clear all cache to ensure fresh data

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'product' => $product]);
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

        $oldValues = $product->only(['price', 'is_available', 'category_id']);
        $product->update($validated);

        AuditLog::log('update_product', $product, $oldValues, $product->only(['price', 'is_available', 'category_id']));

        // Clear all caches
        Cache::forget('api_products');
        Cache::flush(); // Clear all cache to ensure fresh data

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'product' => $product]);
        }

        return redirect()->back()->with('success', 'Producto actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // For Soft Deletes, we usually don't delete the image 
        // in case we need to restore the product or see it in old orders.
        /* 
        if ($product->image_public_id) {
            $this->cloudinary->deleteImage($product->image_public_id);
        }
        */

        $product->delete();

        // Clear all caches
        Cache::forget('api_products');
        Cache::flush(); // Clear all cache to ensure fresh data

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Producto eliminado.');
    }
}
