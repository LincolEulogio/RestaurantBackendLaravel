<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    public function __construct(public CloudinaryService $cloudinary) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promotions = Promotion::latest()->get();

        return view('promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = \App\Models\Product::where('is_available', true)->get();

        return view('promotions.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'badge_label' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $data = $request->except(['image', 'products']);
        $data['status'] = $request->has('status'); // Boolean checkbox

        if ($request->hasFile('image')) {
            $image = $this->cloudinary->uploadImage(
                $request->file('image'),
                'promotions'
            );

            $data['image_url'] = $image['url'];
            $data['image_public_id'] = $image['public_id'];
        }

        $promotion = Promotion::create($data);

        if ($request->has('products')) {
            $promotion->products()->attach($request->products);
        }

        return redirect()->route('promotions.index')
            ->with('success', 'Promoción creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promotion $promotion)
    {
        $products = \App\Models\Product::where('is_available', true)->get();

        return view('promotions.edit', compact('promotion', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'badge_label' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $data = $request->except(['image', 'products']);
        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            // Delete old image from Cloudinary
            if ($promotion->image_public_id) {
                $this->cloudinary->deleteImage($promotion->image_public_id);
            }
            $image = $this->cloudinary->uploadImage(
                $request->file('image'),
                'promotions'
            );

            $data['image_url'] = $image['url'];
            $data['image_public_id'] = $image['public_id'];
        }

        $promotion->update($data);

        if ($request->has('products')) {
            $promotion->products()->sync($request->products);
        } else {
            $promotion->products()->detach();
        }

        return redirect()->route('promotions.index')
            ->with('success', 'Promoción actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promotion $promotion)
    {
        if ($promotion->image_public_id) {
            $this->cloudinary->deleteImage($promotion->image_public_id);
        }

        $promotion->delete();

        return redirect()->route('promotions.index')
            ->with('success', 'Promoción eliminada exitosamente.');
    }

    /**
     * API: Get active promotions
     */
    public function apiIndex()
    {
        // Debugging: Return all active promotions without date filtering
        $promotions = Promotion::with('products')->where('status', true)
            // ->where(function ($query) {
            //     $query->whereNull('start_date')
            //           ->orWhere('start_date', '<=', now());
            // })
            // ->where(function ($query) {
            //     $query->whereNull('end_date')
            //           ->orWhere('end_date', '>=', now());
            // })
            ->latest()
            ->get()
            ->map(function ($promo) {
                return [
                    'id' => $promo->id,
                    'title' => $promo->title,
                    'description' => $promo->description,
                    'discount' => $promo->discount_percent ? $promo->discount_percent.'%' : null,
                    'validUntil' => $promo->end_date ? $promo->end_date->format('d M Y') : 'Indefinido',
                    'image' => $promo->image_url,
                    'badge' => $promo->badge_label,
                    'color' => $this->getBadgeColor($promo->badge_label),
                    'products' => $promo->products->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'price' => (float) $product->price,
                            'image' => $product->image_url,
                        ];
                    }),
                ];
            });

        return response()->json($promotions);
    }

    private function getBadgeColor($label)
    {
        return match ($label) {
            'Popular' => 'orange',
            'Nuevo' => 'green',
            'Limitado' => 'blue',
            'Oferta' => 'red',
            default => 'gray',
        };
    }
}
