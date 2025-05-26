<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpoonacularServices
{
    public function getMealsByCarbs($minCarbs = 10, $maxCarbs = 50)
    {
        try {
            $response = Http::timeout(5)->retry(3, 100)->get(
                config('services.spoonacular.base_uri') . 'recipes/findByNutrients',
                [
                    'minCarbs' => $minCarbs, 
                    'maxCarbs' => $maxCarbs,
                    'apiKey' => config('services.spoonacular.key'),
                ]
            );

            if ($response->successful()) {
                return $response->json();
            } elseif ($response->clientError()) {
                throw new \Exception('Client error');
            } elseif ($response->serverError()) {
                throw new \Exception('At least one nutrient filter must be given.');
            }
        } catch (\Exception $e) {
            Log::error('Spoonacular API request failed', ['message' => $e->getMessage()]);
            return null;
        }
    }
}
