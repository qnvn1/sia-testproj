<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MealDbService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.mealdb.base_uri');
    }

    public function searchMeals(string $mealName)
    {
        try {
            $response = Http::timeout(3)
                ->get("{$this->baseUrl}search.php", ['s' => $mealName]);

            $data = $response->throw()->json();
            return $data['meals'] ?? [];
        } catch (\Exception $e) {
            Log::error("MealDB Search Error for {$mealName}: ".$e->getMessage());
            return ['error' => 'Meal data unavailable'];
        }
    }
}