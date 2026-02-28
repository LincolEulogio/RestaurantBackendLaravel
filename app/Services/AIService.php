<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
        $this->model = config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    /**
     * Generate content using Groq (Ultra-fast AI Service)
     */
    public function generateInsights(string $prompt): ?string
    {
        if (empty($this->apiKey)) {
            Log::error('Groq API Key is missing in config/services.php');
            return 'Error: Llave de Groq no configurada.';
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un consultor experto en negocios de restaurantes de clase mundial.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'No se pudo generar la respuesta.';
            }

            Log::error('Groq API Error: ' . $response->body());
            return 'Error al conectar con el servicio de IA (Groq).';

        } catch (\Exception $e) {
            Log::error('AI Service Exception: ' . $e->getMessage());
            return 'Excepción en el servicio de IA: ' . $e->getMessage();
        }
    }
}
