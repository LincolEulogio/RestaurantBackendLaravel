<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(private readonly CloudinaryService $cloudinary) {}

    public function index()
    {
        if (request()->wantsJson()) {
            return Blog::latest()->get();
        }
        
        $blogs = Blog::latest()->paginate(12);
        return view('blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:10240', // Max 10MB
        ]);

        if ($request->hasFile('image')) {
            $image = $this->cloudinary->uploadImage($request->file('image'), 'blogs');
            $validated['image_url'] = $image['url'];
            $validated['image_public_id'] = $image['public_id'];
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Blog::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Blog creado exitosamente'], 201);
        }

        return redirect()->route('blogs.index')->with('success', 'Blog creado correctamente.');
    }

    public function show(Blog $blog)
    {
        return view('blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        return view('blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:10240', // Max 10MB
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($blog->image_public_id) {
                $this->cloudinary->deleteImage($blog->image_public_id);
            }

            // Upload new image
            $image = $this->cloudinary->uploadImage($request->file('image'), 'blogs');
            $validated['image_url'] = $image['url'];
            $validated['image_public_id'] = $image['public_id'];
        }

        if ($validated['status'] === 'published' && !$blog->published_at) {
            $validated['published_at'] = now();
        }

        $blog->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Blog actualizado exitosamente']);
        }

        return redirect()->route('blogs.index')->with('success', 'Blog actualizado correctamente.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image_public_id) {
            $this->cloudinary->deleteImage($blog->image_public_id);
        }

        $blog->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Blog eliminado exitosamente']);
        }

        return redirect()->route('blogs.index')->with('success', 'Blog eliminado correctamente.');
    }

    // API Methods
    public function apiIndex()
    {
        return Blog::where('status', 'published')
            ->latest()
            ->get()
            ->map(fn($blog) => [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'content' => $blog->content,
                'image' => $blog->image_url,
                'status' => $blog->status,
                'published_at' => $blog->published_at,
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at,
            ]);
    }

    public function apiShow($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json([
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'content' => $blog->content,
            'image' => $blog->image_url,
            'status' => $blog->status,
            'published_at' => $blog->published_at,
            'created_at' => $blog->created_at,
            'updated_at' => $blog->updated_at,
        ]);
    }
}
