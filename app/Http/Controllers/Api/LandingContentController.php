<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\GalleryItem;
use App\Models\RestaurantValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingContentController extends Controller
{
    /**
     * Get all content for the landing page.
     */
    public function index()
    {
        $content = Cache::remember('landing_content', 3600, function () {
            return [
                'hero' => [
                    'title' => Setting::get('landing_hero_title', "Sabores auténticos a tu mesa"),
                    'subtitle' => Setting::get('landing_hero_subtitle', "Descubre una experiencia culinaria única con ingredientes frescos y recetas tradicionales."),
                    'image' => Setting::get('landing_hero_image', "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1600&q=80"),
                ],
                'about' => [
                    'title' => Setting::get('landing_about_title', 'Donde la Tradición se Encuentra con la Excelencia'),
                    'description1' => Setting::get('landing_about_description1', 'Desde 2009, hemos sido el hogar de momentos inolvidables y sabores auténticos.'),
                    'description2' => Setting::get('landing_about_description2', 'Cada plato cuenta una historia de pasión, dedicación y respeto por los ingredientes.'),
                    'years' => Setting::get('landing_about_years', '15+'),
                    'image' => Setting::get('landing_about_image', 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80'),
                    'chef_quote' => Setting::get('landing_chef_quote', '"No solo servimos comida, creamos experiencias que perduran en el corazón"'),
                    'chef_name' => Setting::get('landing_chef_name', 'Chef Fundador'),
                ],
                'values' => RestaurantValue::where('is_active', true)->orderBy('order')->get(),
                'testimonials' => Testimonial::where('is_active', true)->orderBy('created_at', 'desc')->get(),
                'gallery' => GalleryItem::where('is_active', true)->orderBy('order')->get(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $content
        ]);
    }
}
