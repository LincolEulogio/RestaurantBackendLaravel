<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\AIService;
use Illuminate\Http\Request;

class AIMenuController extends Controller
{
    protected $ai;

    public function __construct(AIService $ai)
    {
        $this->ai = $ai;
    }

    /**
     * Handle customer queries about the menu using AI
     */
    public function ask(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $userPrompt = $request->input('prompt');

        // Fetch available products and categories for context
        $products = Product::where('is_available', true)
            ->with('category')
            ->get(['name', 'description', 'price', 'category_id']);

        $categories = Category::all(['id', 'name']);

        // Format menu context for the AI
        $menuContext = "CATÁLOGO DEL RESTAURANTE:\n";
        foreach ($products as $product) {
            $catName = $product->category ? $product->category->name : 'General';
            $menuContext .= "- [{$catName}] {$product->name}: {$product->description} (Precio: S/ {$product->price})\n";
        }

        $systemPrompt = "Eres 'Sabor AI', el asistente virtual experto del Restaurante Sabor.
        Tu objetivo es ayudar a los clientes a elegir qué comer de forma amable, persuasiva y profesional.
        
        REGLAS:
        1. Usa el CATÁLOGO proporcionado para responder. Si algo no está, di que no contamos con ello amablemente.
        2. Sé breve y directo (máximo 3-4 líneas por respuesta).
        3. Siempre sugiere una bebida o postre si el cliente elige un plato fuerte.
        4. Usa emojis y un tono acogedor peruano.
        5. No hables de temas que no sean el restaurante.
        
        CATÁLOGO:\n{$menuContext}";

        $finalPrompt = "{$systemPrompt}\n\nPREGUNTA DEL CLIENTE: {$userPrompt}";

        $response = $this->ai->generateInsights($finalPrompt);

        return response()->json([
            'success' => true,
            'response' => $response
        ]);
    }
}
