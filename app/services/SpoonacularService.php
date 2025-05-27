<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpoonacularService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.spoonacular.base_uri');
        $this->apiKey = config('services.spoonacular.key');
    }

    public function generateWeeklyPlan()
    {
        try {
            $response = Http::timeout(10)
                ->retry(3, 100)
                ->get("{$this->baseUrl}mealplanner/generate", [
                    'timeFrame' => 'week',
                    'apiKey' => $this->apiKey
                ]);

            if ($response->failed()) {
                throw new \Exception("API request failed with status: {$response->status()}");
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Spoonacular generateWeeklyPlan error: ' . $e->getMessage());
            throw $e;
        }
    }
}