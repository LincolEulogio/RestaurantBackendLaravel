<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GalleryController extends Controller
{
    public function __construct(public CloudinaryService $cloudinary) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:8192',
            'span_type' => 'required|string',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $image = $this->cloudinary->uploadImage($request->file('image'), 'gallery');
            $validated['image_url'] = $image['url'];
            $validated['image_public_id'] = $image['public_id'];
        }

        $gallery = GalleryItem::create($validated);
        Cache::forget('landing_content');

        return response()->json(['success' => true, 'data' => $gallery]);
    }

    public function update(Request $request, $id)
    {
        $gallery = GalleryItem::findOrFail($id);
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:8192',
            'span_type' => 'sometimes|nullable|string',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($gallery->image_public_id) {
                $this->cloudinary->deleteImage($gallery->image_public_id);
            }
            $image = $this->cloudinary->uploadImage($request->file('image'), 'gallery');
            $gallery->image_url = $image['url'];
            $gallery->image_public_id = $image['public_id'];
        }

        $gallery->update(array_filter($validated, fn($value) => !is_null($value)));
        
        Cache::forget('landing_content');

        return response()->json(['success' => true, 'data' => $gallery]);
    }

    public function destroy($id)
    {
        $gallery = GalleryItem::findOrFail($id);
        if ($gallery->image_public_id) {
            $this->cloudinary->deleteImage($gallery->image_public_id);
        }
        $gallery->delete();
        Cache::forget('landing_content');
        return response()->json(['success' => true]);
    }
}
