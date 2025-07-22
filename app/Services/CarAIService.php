<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CarAIService 
{
    private $groqApiKey;
    private $groqEndpoint = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        $this->groqApiKey = env('GROQ_API_KEY');
    }

    /**
     * Analizza la richiesta dell'utente usando AI
     */
    public function analyzeCarRequest(string $description): array
    {
        // Se non abbiamo API key, ritorna analisi base
        if (empty($this->groqApiKey)) {
            return $this->basicAnalysis($description);
        }

        $prompt = "Analizza questa richiesta di auto in italiano e rispondi SOLO in formato JSON valido.
Estrai queste informazioni:
- brand: marca preferita (es: BMW, Audi, Fiat) o null se non specificata
- category: tipo auto (sportiva, suv, city, berlina, elettrica) o null
- budget_min: budget minimo in euro o null
- budget_max: budget massimo in euro o null  
- usage: come verrà usata (città, famiglia, lavoro, sport) o null
- confidence: quanto sei sicuro (0.0 a 1.0)

Richiesta: '$description'

Esempi di risposta:
{\"brand\":\"BMW\",\"category\":\"sportiva\",\"budget_min\":40000,\"budget_max\":60000,\"usage\":\"sport\",\"confidence\":0.9}";

        try {
            $response = Http::timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $this->groqApiKey,
                'Content-Type' => 'application/json',
            ])->post($this->groqEndpoint, [
                'model' => 'llama3-8b-8192',
                'messages' => [
                    ['role' => 'system', 'content' => 'Sei un esperto di auto che risponde solo in JSON valido.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 200,
                'temperature' => 0.1
            ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'] ?? '{}';
                $parsed = json_decode($content, true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $parsed;
                }
            }

            Log::warning('Groq AI response error', ['response' => $response->body()]);
            
        } catch (\Exception $e) {
            Log::error('Groq AI Error: ' . $e->getMessage());
        }

        // Fallback a analisi base
        return $this->basicAnalysis($description);
    }

    /**
     * Analisi base senza AI (fallback)
     */
    private function basicAnalysis(string $description): array
    {
        $description = strtolower($description);
        
        return [
            'brand' => $this->extractBrand($description),
            'category' => $this->extractCategory($description),
            'budget_min' => $this->extractBudget($description, 'min'),
            'budget_max' => $this->extractBudget($description, 'max'),
            'usage' => $this->extractUsage($description),
            'confidence' => 0.7
        ];
    }

    private function extractBrand($description): ?string
    {
        $brands = [
            'bmw', 'audi', 'mercedes', 'volkswagen', 'fiat', 'ferrari', 
            'tesla', 'toyota', 'honda', 'ford', 'porsche', 'lamborghini',
            'volvo', 'nissan', 'mazda', 'hyundai', 'kia', 'jeep', 'alfa romeo'
        ];
        
        foreach ($brands as $brand) {
            if (strpos($description, $brand) !== false) {
                return ucfirst($brand === 'mercedes' ? 'Mercedes-Benz' : $brand);
            }
        }
        return null;
    }

    private function extractCategory($description): ?string
    {
        if (strpos($description, 'sport') !== false) return 'sportiva';
        if (strpos($description, 'suv') !== false) return 'suv';
        if (strpos($description, 'piccola') !== false || strpos($description, 'city') !== false) return 'city';
        if (strpos($description, 'elettrica') !== false) return 'elettrica';
        return 'berlina';
    }

    private function extractBudget($description, $type): ?int
    {
        if (preg_match('/(\d+)(?:k|mila)/i', $description, $matches)) {
            $budget = intval($matches[1]) * 1000;
            return $type === 'min' ? max($budget - 5000, 0) : $budget + 5000;
        }
        return null;
    }

    private function extractUsage($description): ?string
    {
        if (strpos($description, 'famiglia') !== false) return 'famiglia';
        if (strpos($description, 'città') !== false) return 'città';
        if (strpos($description, 'lavoro') !== false) return 'lavoro';
        if (strpos($description, 'sport') !== false || strpos($description, 'divertir') !== false) return 'sport';
        return null;
    }
}