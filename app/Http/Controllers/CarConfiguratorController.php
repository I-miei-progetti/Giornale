<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CarConfiguratorController extends Controller
{
    private $brandSites = [
        'ferrari' => 'https://www.ferrari.com',
        'lamborghini' => 'https://www.lamborghini.com',
        'bmw' => 'https://www.bmw.it',
        'mercedes' => 'https://www.mercedes-benz.it',
        'audi' => 'https://www.audi.it',
        'volkswagen' => 'https://www.volkswagen.it',
        'fiat' => 'https://www.fiat.it',
        'alfa romeo' => 'https://www.alfaromeo.it',
        'toyota' => 'https://www.toyota.it',
        'honda' => 'https://www.honda.it',
        'ford' => 'https://www.ford.it',
        'peugeot' => 'https://www.peugeot.it',
        'renault' => 'https://www.renault.it',
        'tesla' => 'https://www.tesla.com',
        'porsche' => 'https://www.porsche.it',
    ];

    private $categories = [
        'sportiva' => ['sportiva', 'sport', 'racing', 'veloce', 'performance'],
        'suv' => ['suv', 'crossover', 'fuoristrada', 'alto', 'grande'],
        'city' => ['city', 'urbana', 'piccola', 'compatta', 'economica'],
        'berlina' => ['berlina', 'sedan', 'elegante', 'lusso', 'comfort'],
        'elettrica' => ['elettrica', 'elettrico', 'eco', 'green'],
        'cabrio' => ['cabrio', 'cabriolet', 'convertibile', 'scoperta'],
    ];

    public function analyze(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:1000'
        ]);

        $description = strtolower($request->input('description'));
        
        try {
            // Analizza la descrizione
            $brand = $this->detectBrand($description);
            $category = $this->detectCategory($description);
            $priceRange = $this->detectPriceRange($description, $brand);
            $model = $this->suggestModel($brand, $category);
            $carDescription = $this->generateDescription($brand, $model, $category, $priceRange);
            
            return response()->json([
                'brand' => $brand,
                'model' => $model,
                'category' => $category,
                'price_range' => $priceRange,
                'official_site' => $this->brandSites[$brand] ?? 'https://www.google.com',
                'image_url' => $this->getCarImage($brand, $model),
                'description' => $carDescription
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore durante l\'elaborazione: ' . $e->getMessage()
            ], 500);
        }
    }

    private function detectBrand($description)
    {
        foreach ($this->brandSites as $brand => $site) {
            if (strpos($description, $brand) !== false) {
                return ucfirst($brand);
            }
        }
        
        // Analisi per caratteristiche
        if (strpos($description, 'lusso') !== false || strpos($description, 'elegante') !== false) {
            return 'Mercedes';
        } elseif (strpos($description, 'sportiva') !== false || strpos($description, 'veloce') !== false) {
            return 'BMW';
        } elseif (strpos($description, 'economica') !== false || strpos($description, 'piccola') !== false) {
            return 'Fiat';
        } elseif (strpos($description, 'elettrica') !== false || strpos($description, 'eco') !== false) {
            return 'Tesla';
        } elseif (strpos($description, 'suv') !== false || strpos($description, 'grande') !== false) {
            return 'Volkswagen';
        } else {
            return 'Toyota';
        }
    }

    private function detectCategory($description)
    {
        foreach ($this->categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($description, $keyword) !== false) {
                    return $category;
                }
            }
        }
        return 'berlina';
    }

    private function detectPriceRange($description, $brand)
    {
        // Cerca numeri nella descrizione
        preg_match('/(\d+)(?:\s*(?:k|mila|euro|€))?/', $description, $matches);
        
        if (!empty($matches)) {
            $price = intval($matches[1]);
            if (strpos($description, 'k') !== false || strpos($description, 'mila') !== false) {
                $price *= 1000;
            }
            
            if ($price < 25000) return 'economica';
            if ($price < 50000) return 'media';
            if ($price < 100000) return 'premium';
            if ($price < 300000) return 'lusso';
            return 'supercar';
        }
        
        // Analisi per marca
        $luxuryBrands = ['ferrari', 'lamborghini', 'porsche'];
        $premiumBrands = ['bmw', 'mercedes', 'audi'];
        
        if (in_array(strtolower($brand), $luxuryBrands)) return 'lusso';
        if (in_array(strtolower($brand), $premiumBrands)) return 'premium';
        
        return 'media';
    }

    private function suggestModel($brand, $category)
    {
        $models = [
            'BMW' => ['sportiva' => 'M3', 'suv' => 'X5', 'berlina' => 'Serie 3'],
            'Mercedes' => ['sportiva' => 'AMG GT', 'suv' => 'GLE', 'berlina' => 'Classe C'],
            'Audi' => ['sportiva' => 'RS5', 'suv' => 'Q7', 'berlina' => 'A4'],
            'Fiat' => ['city' => '500', 'suv' => '500X', 'berlina' => 'Tipo'],
            'Tesla' => ['elettrica' => 'Model 3', 'suv' => 'Model Y'],
            'Toyota' => ['city' => 'Yaris', 'suv' => 'RAV4', 'berlina' => 'Camry'],
        ];
        
        return $models[$brand][$category] ?? $brand . ' Model';
    }

    private function generateDescription($brand, $model, $category, $priceRange)
    {
        $descriptions = [
            'economica' => "La $brand $model è una $category perfetta per chi cerca qualità e convenienza.",
            'media' => "La $brand $model rappresenta un ottimo compromesso tra prestazioni e prezzo.",
            'premium' => "La $brand $model è una $category di alta gamma con tecnologie avanzate.",
            'lusso' => "La $brand $model rappresenta l'eccellenza nel segmento $category.",
            'supercar' => "La $brand $model è una $category da sogno con prestazioni mozzafiato.",
        ];
        
        return $descriptions[$priceRange] ?? "La $brand $model è una $category di qualità.";
    }

    private function getCarImage($brand, $model)
    {
        // Usa immagini placeholder colorate
        $colors = ['1e3a8a', 'dc2626', '059669', '7c3aed', 'ea580c'];
        $color = $colors[array_rand($colors)];
        
        return "https://via.placeholder.com/400x300/$color/ffffff?text=" . urlencode($brand . ' ' . $model);
    }
}