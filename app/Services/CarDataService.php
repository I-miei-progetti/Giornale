<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CarDataService 
{
    private $apiBase;

    public function __construct()
    {
        $this->apiBase = env('CARQUERY_API_BASE', 'https://www.carqueryapi.com/api/0.3/');
    }

    /**
     * Ottieni tutte le marche auto
     */
    public function getAllMakes(): array
    {
        return Cache::remember('car_makes', 86400, function () {
            try {
                $response = Http::timeout(10)->get($this->apiBase, [
                    'cmd' => 'getMakes'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['Makes']) && is_array($data['Makes'])) {
                        return collect($data['Makes'])
                            ->pluck('make_display')
                            ->filter()
                            ->values()
                            ->toArray();
                    }
                }

                Log::warning('CarQuery API getMakes failed', ['response' => $response->body()]);

            } catch (\Exception $e) {
                Log::error('CarQuery getMakes Error: ' . $e->getMessage());
            }
            
            // Fallback a marche principali
            return [
                'Audi', 'BMW', 'Mercedes-Benz', 'Volkswagen', 'Fiat', 'Ferrari', 
                'Toyota', 'Honda', 'Ford', 'Tesla', 'Porsche', 'Volvo', 'Nissan',
                'Lamborghini', 'Maserati', 'Alfa Romeo', 'Jeep', 'Hyundai', 'Kia',
                'Mazda', 'Subaru', 'Mitsubishi', 'Peugeot', 'Citroën', 'Renault'
            ];
        });
    }

    /**
     * Ottieni modelli per una marca
     */
    public function getModelsByMake(string $make, ?int $year = null): array
    {
        $year = $year ?: date('Y');
        $cacheKey = "car_models_{$make}_{$year}";
        
        return Cache::remember($cacheKey, 3600, function () use ($make, $year) {
            try {
                $response = Http::timeout(10)->get($this->apiBase, [
                    'cmd' => 'getModels',
                    'make' => $make,
                    'year' => $year
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['Models']) && is_array($data['Models'])) {
                        return collect($data['Models'])
                            ->pluck('model_name')
                            ->filter()
                            ->unique()
                            ->values()
                            ->toArray();
                    }
                }

                Log::warning('CarQuery API getModels failed', [
                    'make' => $make, 
                    'year' => $year,
                    'response' => $response->body()
                ]);

            } catch (\Exception $e) {
                Log::error('CarQuery getModels Error: ' . $e->getMessage());
            }
            
            // Fallback a modelli comuni
            return $this->getFallbackModels($make);
        });
    }

    /**
     * Trova la migliore corrispondenza per marca
     */
    public function findBestMakeMatch(string $query, array $allMakes): ?string
    {
        $query = strtolower($query);
        
        // Prima: match esatto
        foreach ($allMakes as $make) {
            if (strtolower($make) === $query) {
                return $make;
            }
        }
        
        // Seconda: match parziale
        foreach ($allMakes as $make) {
            if (strpos(strtolower($make), $query) !== false || 
                strpos($query, strtolower($make)) !== false) {
                return $make;
            }
        }
        
        return null;
    }

    /**
     * Modelli di fallback per marche comuni
     */
    private function getFallbackModels(string $make): array
    {
        $fallbackModels = [
            'BMW' => ['Serie 1', 'Serie 2', 'Serie 3', 'Serie 4', 'Serie 5', 'Serie 7', 'X1', 'X2', 'X3', 'X4', 'X5', 'X6', 'X7', 'M2', 'M3', 'M4', 'M5', 'i3', 'i4', 'iX3'],
            'Audi' => ['A1', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q2', 'Q3', 'Q4', 'Q5', 'Q7', 'Q8', 'TT', 'R8', 'RS3', 'RS4', 'RS5', 'RS6', 'e-tron', 'e-tron GT'],
            'Mercedes-Benz' => ['Classe A', 'Classe B', 'Classe C', 'Classe E', 'Classe S', 'CLA', 'CLS', 'GLA', 'GLB', 'GLC', 'GLE', 'GLS', 'AMG GT', 'EQA', 'EQB', 'EQC', 'EQS'],
            'Volkswagen' => ['up!', 'Polo', 'Golf', 'Jetta', 'Passat', 'Arteon', 'T-Cross', 'T-Roc', 'Tiguan', 'Touareg', 'Sharan', 'ID.3', 'ID.4', 'ID.5'],
            'Fiat' => ['500', '500C', '500X', '500L', 'Panda', 'Tipo', 'Doblò', 'Ducato', '500e'],
            'Tesla' => ['Model 3', 'Model Y', 'Model S', 'Model X'],
            'Toyota' => ['Aygo', 'Yaris', 'Corolla', 'Camry', 'Prius', 'C-HR', 'RAV4', 'Highlander', 'Land Cruiser'],
            'Honda' => ['Jazz', 'Civic', 'Accord', 'HR-V', 'CR-V', 'Pilot', 'e'],
            'Ford' => ['Ka+', 'Fiesta', 'Focus', 'Mondeo', 'Mustang', 'EcoSport', 'Kuga', 'Explorer', 'F-150'],
            'Ferrari' => ['Portofino M', 'Roma', '296 GTB', 'F8 Tributo', 'SF90 Stradale', 'LaFerrari'],
            'Lamborghini' => ['Huracán', 'Aventador', 'Urus'],
            'Porsche' => ['718', '911', 'Panamera', 'Cayenne', 'Macan', 'Taycan'],
            'Volvo' => ['XC40', 'XC60', 'XC90', 'S60', 'S90', 'V60', 'V90']
        ];

        return $fallbackModels[$make] ?? ['Base Model', 'Premium Model'];
    }
}