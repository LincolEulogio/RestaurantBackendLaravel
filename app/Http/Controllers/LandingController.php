<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\GalleryItem;
use App\Models\RestaurantValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class LandingController extends Controller
{
    /**
     * Display the landing page configuration.
     */
    public function index()
    {
        return view('landing.index', [
            'about_title' => Setting::get('landing_about_title', 'Donde la Tradición se Encuentra con la Excelencia'),
            'about_description1' => Setting::get('landing_about_description1', 'Desde 2009, hemos sido el hogar de momentos inolvidables y sabores auténticos.'),
            'about_description2' => Setting::get('landing_about_description2', 'Cada plato cuenta una historia de pasión, dedicación y respeto por los ingredientes.'),
            'about_years' => Setting::get('landing_about_years', '15+'),
            'about_image' => Setting::get('landing_about_image'),
            'chef_quote' => Setting::get('landing_chef_quote', '"No solo servimos comida, creamos experiencias que perduran en el corazón"'),
            'chef_name' => Setting::get('landing_chef_name', 'Chef Fundador'),
            
            // Hero
            'hero_title' => Setting::get('landing_hero_title', "Sabores auténticos a tu mesa"),
            'hero_subtitle' => Setting::get('landing_hero_subtitle', "Descubre una experiencia culinaria única con ingredientes frescos y recetas tradicionales."),
            'hero_image' => Setting::get('landing_hero_image'),

            'testimonials' => Testimonial::orderBy('created_at', 'desc')->get(),
            'gallery' => GalleryItem::orderBy('order')->get(),
            'values' => RestaurantValue::orderBy('order')->get(),
        ]);
    }

    /**
     * Update general landing page settings.
     */
    public function updateGeneral(Request $request)
    {
        $data = $request->validate([
            'landing_about_title' => 'required|string',
            'landing_about_description1' => 'required|string',
            'landing_about_description2' => 'required|string',
            'landing_about_years' => 'required|string',
            'landing_chef_quote' => 'required|string',
            'landing_chef_name' => 'required|string',
            'landing_about_image' => 'nullable|image|max:10240',
            // Hero
            'landing_hero_title' => 'nullable|string',
            'landing_hero_subtitle' => 'nullable|string',
            'landing_hero_image' => 'nullable|image|max:10240',
        ]);

        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $result = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'landing',
                ]);
                Setting::set($key, $result->getSecurePath());
            } elseif ($key !== 'landing_about_image' && $key !== 'landing_hero_image') {
                Setting::set($key, $value);
            }
        }

        Cache::forget('landing_content');

        return back()->with('success', 'Configuración de landing actualizada');
    }
}
