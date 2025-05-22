<?php

class MealDbService
{
    public function searchMeal($mealName)
    {
        try {
            $response = Http::timeout(5)->retry(3, 100)->get(
                config('services.mealdb.base_uri') . 'search.php',
                [
                    's' => $mealName
                ]
            );

            if ($response->successful()) {
                return $response->json();
            } elseif ($response->clientError()) {
                throw new \Exception('Client error');
            } elseif ($response->serverError()) {
                throw new \Exception('Server error');
            }
        } catch (\Exception $e) {
            Log::error('MealDB API request failed', ['message' => $e->getMessage()]);
            return null;
        }
    }
}