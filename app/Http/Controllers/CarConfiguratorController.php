<?php
namespace App\Http\Controllers;

use App\Services\CarAIService;
use App\Services\CarDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarConfiguratorController extends Controller
{
    private $carAI;
    private $carData;

    public function __construct()
    {
        $this->carAI = new CarAIService();
        $this->carData = new CarDataService();
    }

    public function analyze(Request $request): JsonResponse
    {
        $description = trim($request->input('description', ''));

        if (strlen($description) < 8) {
            return $this->askForMoreDetails();
        }

        try {
            // 1. Analizza richiesta con AI
            $aiAnalysis = $this->carAI->analyzeCarRequest($description);

            // 2. Ottieni dati auto reali
            $allMakes = $this->carData->getAllMakes();

            // 3. Trova la marca migliore
            $detectedBrand = $aiAnalysis['brand'] ?? null;
            if ($detectedBrand) {
                $brand = $this->carData->findBestMakeMatch($detectedBrand, $allMakes) ?? $detectedBrand;
            } else {
                $brand = $this->carData->findBestMakeMatch($description, $allMakes);
            }

            if (!$brand) {
                return $this->askForMoreDetails(
                    "🤔 Non ho capito la marca. Marche disponibili: " .
                    implode(', ', array_slice($allMakes, 0, 8)) . "..."
                );
            }

            // 4. Ottieni modelli reali per la marca
            $availableModels = $this->carData->getModelsByMake($brand);

            // 5. Trova il modello migliore
            $bestModel = $this->findBestModel($availableModels, $aiAnalysis, $description);

            // 6. Determina categoria e prezzo
            $category = $aiAnalysis['category'] ?? 'berlina';
            $priceRange = $this->calculatePriceRange($aiAnalysis, $brand);

            // 7. Genera risposta completa
            return response()->json([
                'brand' => $brand,
                'model' => $bestModel,
                'category' => $category,
                'price_range' => $priceRange,
                'official_site' => $this->getOfficialSite($brand),
                'image_url' => $this->getCarImage($brand, $bestModel),
                'description' => $this->generateDescription($brand, $bestModel, $category, $priceRange),
                'conversation' => $this->generateConversation($brand, $bestModel, $category, $description, $aiAnalysis, count($availableModels)),
                'available_models' => array_slice($availableModels, 0, 6),
                'ai_confidence' => $aiAnalysis['confidence'] ?? 0.7,
                'total_models_found' => count($availableModels),
            ]);

        } catch (\Exception $e) {
            \Log::error('Car analysis error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Si è verificato un errore nell\'analisi. Riprova.',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Trova il modello migliore tra quelli disponibili
     */
    private function findBestModel(array $availableModels, array $aiAnalysis, string $description): string
    {
        if (empty($availableModels)) {
            // Se non hai modelli, genera uno smart
            $brand = $aiAnalysis['brand'] ?? 'Auto';
            $category = $aiAnalysis['category'] ?? 'berlina';
            return $this->generateSmartModel($brand, $category);
        }

        $description = strtolower($description);
        $category = $aiAnalysis['category'] ?? '';

        // 1. Cerca match diretto nella descrizione
        foreach ($availableModels as $model) {
            if (strpos($description, strtolower($model)) !== false) {
                return $model;
            }
        }

        // 2. Match basato su categoria
        $categoryKeywords = [
            'sportiva' => ['sport', 'rs', 'amg', 'm2', 'm3', 'm4', 'm5', 'gtr', 'turbo', 'gt', 'performance'],
            'suv' => ['x1', 'x2', 'x3', 'x4', 'x5', 'x6', 'x7', 'q2', 'q3', 'q4', 'q5', 'q7', 'q8', 'gle', 'glc', 'gla', 'glb', 'gls', 'suv', 'crossover'],
            'city' => ['1', '2', 'mini', 'smart', 'city', 'up', 'aygo', 'ka', 'jazz'],
            'elettrica' => ['e-', 'i3', 'i4', 'ix3', 'model', 'leaf', 'id.', 'ev', 'electric', 'eq', 'taycan']
        ];

        if (isset($categoryKeywords[$category])) {
            foreach ($availableModels as $model) {
                foreach ($categoryKeywords[$category] as $keyword) {
                    if (strpos(strtolower($model), $keyword) !== false) {
                        return $model;
                    }
                }
            }
        }

        // 3. Estrai modello dalla descrizione usando pattern
        $extractedModel = $this->extractModelFromDescription($description, $aiAnalysis['brand'] ?? '');
        if ($extractedModel) {
            // Trova il match più simile nei modelli disponibili
            foreach ($availableModels as $model) {
                if (strpos(strtolower($model), strtolower($extractedModel)) !== false ||
                    strpos(strtolower($extractedModel), strtolower($model)) !== false) {
                    return $model;
                }
            }
        }

        // 4. Ritorna il primo modello disponibile
        return $availableModels[0];
    }

    private function extractModelFromDescription(string $description, string $brand): ?string
    {
        $description = strtolower($description);
        $brand = strtolower($brand);

        // Pattern comuni per modelli auto
        $patterns = [
            'bmw' => ['/serie\s*(\d)/i', '/x(\d)/i', '/m(\d)/i', '/(i\d)/i'],
            'mercedes' => ['/classe\s*([a-z])/i', '/gl([a-z])/i', '/(amg)/i'],
            'audi' => ['/a(\d)/i', '/q(\d)/i', '/rs(\d)/i', '/(tt)/i'],
            'volkswagen' => ['/(golf)/i', '/(polo)/i', '/(tiguan)/i', '/(passat)/i'],
            'fiat' => ['/(\d{3})/i', '/(panda)/i', '/(tipo)/i', '/(punto)/i'],
            'toyota' => ['/(yaris)/i', '/(corolla)/i', '/(rav\d)/i', '/(prius)/i'],
            'tesla' => ['/model\s*([3sxy])/i'],
        ];

        if (isset($patterns[$brand])) {
            foreach ($patterns[$brand] as $pattern) {
                if (preg_match($pattern, $description, $matches)) {
                    return $this->formatModelName($brand, $matches[1]);
                }
            }
        }

        return null;
    }

    private function formatModelName(string $brand, string $match): string
    {
        $brand = strtolower($brand);
        $match = strtolower(trim($match));

        $formatting = [
            'bmw' => [
                '1' => 'Serie 1', '2' => 'Serie 2', '3' => 'Serie 3',
                '4' => 'Serie 4', '5' => 'Serie 5', '7' => 'Serie 7'
            ],
            'mercedes' => [
                'a' => 'Classe A', 'b' => 'Classe B', 'c' => 'Classe C',
                'e' => 'Classe E', 's' => 'Classe S'
            ],
            'audi' => [
                '1' => 'A1', '3' => 'A3', '4' => 'A4', '6' => 'A6', '8' => 'A8'
            ]
        ];

        return $formatting[$brand][$match] ?? ucwords($match);
    }

    private function generateSmartModel(string $brand, ?string $category): string
    {
        $smartModels = [
            'sportiva' => [
                'BMW' => 'M3', 'Mercedes-Benz' => 'AMG GT', 'Audi' => 'RS4',
                'Ferrari' => 'F8 Tributo', 'Porsche' => '911', 'Lamborghini' => 'Huracán',
                'Toyota' => 'GR Supra', 'Ford' => 'Mustang', 'Tesla' => 'Model S'
            ],
            'suv' => [
                'BMW' => 'X3', 'Mercedes-Benz' => 'GLC', 'Audi' => 'Q5',
                'Volkswagen' => 'Tiguan', 'Toyota' => 'RAV4', 'Jeep' => 'Compass',
                'Tesla' => 'Model Y', 'Volvo' => 'XC60'
            ],
            'city' => [
                'BMW' => 'Serie 1', 'Mercedes-Benz' => 'Classe A', 'Audi' => 'A1',
                'Fiat' => '500', 'Toyota' => 'Yaris', 'Honda' => 'Jazz',
                'Volkswagen' => 'Polo', 'Ford' => 'Fiesta'
            ],
            'berlina' => [
                'BMW' => 'Serie 3', 'Mercedes-Benz' => 'Classe C', 'Audi' => 'A4',
                'Volkswagen' => 'Passat', 'Toyota' => 'Corolla', 'Tesla' => 'Model 3'
            ],
            'elettrica' => [
                'BMW' => 'i4', 'Mercedes-Benz' => 'EQC', 'Audi' => 'e-tron',
                'Tesla' => 'Model 3', 'Nissan' => 'Leaf', 'Volkswagen' => 'ID.4'
            ]
        ];

        return $smartModels[$category][$brand] ?? $smartModels['berlina'][$brand] ?? "{$brand} Premium";
    }

    private function calculatePriceRange(array $aiAnalysis, string $brand): string
    {
        // Se AI ha rilevato budget specifico
        if (!empty($aiAnalysis['budget_min']) && !empty($aiAnalysis['budget_max'])) {
            $min = number_format($aiAnalysis['budget_min'], 0, '.', '.');
            $max = number_format($aiAnalysis['budget_max'], 0, '.', '.');
            return "{$min}€ - {$max}€";
        }

        // Range basato su marca
        $priceRanges = [
            'Ferrari' => '200.000€ - 500.000€+',
            'Lamborghini' => '150.000€ - 400.000€+',
            'Porsche' => '60.000€ - 200.000€',
            'BMW' => '30.000€ - 80.000€',
            'Mercedes-Benz' => '35.000€ - 85.000€',
            'Audi' => '28.000€ - 75.000€',
            'Tesla' => '40.000€ - 100.000€',
            'Volkswagen' => '20.000€ - 45.000€',
            'Fiat' => '15.000€ - 35.000€',
            'Toyota' => '18.000€ - 40.000€',
            'Honda' => '20.000€ - 45.000€',
            'Ford' => '18.000€ - 50.000€'
        ];

        return $priceRanges[$brand] ?? '20.000€ - 50.000€';
    }

    private function generateConversation(string $brand, string $model, string $category, string $description, array $aiAnalysis, int $totalModels): array
    {
        $confidence = $aiAnalysis['confidence'] ?? 0.7;
        $confidenceText = $confidence > 0.8 ? 'molto sicuro' : ($confidence > 0.6 ? 'abbastanza sicuro' : 'moderatamente sicuro');

        return [
            "📝 **La tua richiesta:** *\"{$description}\"*",
            "",
            "🤖 **Analisi AI rilevata:**",
            "• Marca: " . ($aiAnalysis['brand'] ?? 'Auto-rilevata'),
            "• Categoria: {$category}",
            "• Budget: " . ($aiAnalysis['budget_min'] ? number_format($aiAnalysis['budget_min']) . "€+" : 'Non specificato'),
            "✅ **La mia raccomandazione: {$brand} {$model}**",
            "",
            "**💡 Perché questa scelta:**",
            "• Analizzati {$totalModels} modelli {$brand} dal database",
            "• Categoria {$category}",
            "• Marca affidabile e ben recensita",
            "• Tecnologia e design all'avanguardia",
            "",
            "**🚀 Prossimi passi raccomandati:**",
            "1. 🌐 Visita il sito ufficiale per configurazioni dettagliate",
            "2. 🗺️ Trova concessionari nella tua zona",
            "3. 📞 Prenota un test drive gratuito",
            "4. 💰 Richiedi preventivo personalizzato",
            ""
        ];
    }

    private function getOfficialSite(string $brand): string
    {
        $sites = [
            'BMW' => 'https://www.bmw.it',
            'Mercedes-Benz' => 'https://www.mercedes-benz.it',
            'Audi' => 'https://www.audi.it',
            'Volkswagen' => 'https://www.volkswagen.it',
            'Fiat' => 'https://www.fiat.it',
            'Tesla' => 'https://www.tesla.com/it',
            'Toyota' => 'https://www.toyota.it',
            'Honda' => 'https://www.honda.it',
            'Ford' => 'https://www.ford.it',
            'Ferrari' => 'https://www.ferrari.com',
            'Porsche' => 'https://www.porsche.it',
            'Lamborghini' => 'https://www.lamborghini.com',
            'Volvo' => 'https://www.volvocars.com/it'
        ];

        return $sites[$brand] ?? "https://www.google.com/search?q=" . urlencode($brand . " italia");
    }

    private function getCarImage(string $brand, string $model): string
    {
        $queries = [
            urlencode("{$brand} {$model} car exterior"),
            urlencode("{$brand} {$model} automotive"),
            urlencode("{$brand} car 2024"),
            urlencode("luxury car {$brand}"),
            urlencode("car automotive vehicle")
        ];

        foreach ($queries as $index => $query) {
            $imageUrl = "https://source.unsplash.com/1200x800/?{$query}&" . time() . $index;
            if ($index < 2) {
                return $imageUrl;
            }
        }

        return $this->getPlaceholderImage($brand, $model);
    }

    private function getPlaceholderImage(string $brand, string $model): string
    {
        $alternatives = [
            "https://picsum.photos/1200/800?random=" . crc32($brand . $model),
            "https://via.placeholder.com/1200x800/3498db/ffffff?text=" . urlencode($brand . " " . $model),
            "https://source.unsplash.com/1200x800/?car,automotive&" . rand(1, 1000)
        ];

        return $alternatives[0];
    }

    private function generateDescription(string $brand, string $model, string $category, string $priceRange): string
    {
        $categoryDescriptions = [
            'sportiva' => 'dalle prestazioni elevate e design aggressivo',
            'suv' => 'versatile e spaziosa, perfetta per la famiglia',
            'city' => 'compatta e agile, ideale per la città',
            'berlina' => 'elegante e confortevole',
            'elettrica' => 'ecologica e tecnologicamente avanzata',
        ];

        $desc = $categoryDescriptions[$category] ?? 'di alta qualità';

        return "La {$brand} {$model} è una {$category} {$desc}, con un prezzo nella fascia {$priceRange}. Rappresenta un'eccellente combinazione di stile, tecnologia e affidabilità per soddisfare le tue esigenze di mobilità.";
    }

    private function askForMoreDetails(string $message = null): JsonResponse
    {
        return response()->json([
            'ask_details' => true,
            'message' => $message ?: "🚗 **Aiutami a trovarti l'auto perfetta!**\n\nSpecifica almeno:\n💰 **Budget** (es: 30.000€)\n🏷️ **Marca** (BMW, Audi, Fiat...)\n🎯 **Tipo** (sportiva, SUV, city car...)\n🎪 **Utilizzo** (città, famiglia, lavoro...)\n\n**Esempio completo:** 'BMW Serie 3 sportiva, budget 45k, per divertirmi nei weekend'",
            'suggestions' => [
                'BMW Serie 3 sportiva, budget 45k, per divertirmi',
                'Audi Q3 SUV familiare, budget 40k, per la famiglia',
                'Tesla Model 3, budget 50k, ecologica e moderna',
                'Fiat 500 city car, budget 20k, per la città',
                'Mercedes Classe C berlina, budget 35k, per lavoro'
            ],
        ]);
    }
}