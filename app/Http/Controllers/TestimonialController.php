<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestimonialController extends Controller
{
    public function __construct(public CloudinaryService $cloudinary) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'text' => 'required|string',
            'platform' => 'nullable|string|max:255',
            'date_literal' => 'nullable|string|max:255',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:6144',
        ]);

        if ($request->hasFile('image')) {
            $image = $this->cloudinary->uploadImage($request->file('image'), 'testimonials');
            $validated['image_url'] = $image['url'];
            $validated['image_public_id'] = $image['public_id'];
        }

        Testimonial::create($validated);
        Cache::forget('landing_content');

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'rating' => 'sometimes|nullable|integer|min:1|max:5',
            'text' => 'sometimes|nullable|string',
            'platform' => 'nullable|string|max:255',
            'date_literal' => 'nullable|string|max:255',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:6144',
        ]);

        if ($request->hasFile('image')) {
            if ($testimonial->image_public_id) {
                $this->cloudinary->deleteImage($testimonial->image_public_id);
            }
            $image = $this->cloudinary->uploadImage($request->file('image'), 'testimonials');
            $testimonial->image_url = $image['url'];
            $testimonial->image_public_id = $image['public_id'];
        }

        $testimonial->update(array_filter($validated, fn($value) => !is_null($value)));
        
        Cache::forget('landing_content');

        return response()->json(['success' => true, 'data' => $testimonial]);
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        if ($testimonial->image_public_id) {
            $this->cloudinary->deleteImage($testimonial->image_public_id);
        }
        $testimonial->delete();
        Cache::forget('landing_content');
        return response()->json(['success' => true]);
    }
}
