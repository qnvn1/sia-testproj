<?php

class MealDB {
    private const API_BASE_URL = 'https://www.themealdb.com/api/json/v1/1/';
    
    public function searchMeals(string $mealName): ?array {
        try {
            $response = Http::get(self::API_BASE_URL . 'search.php', [
                's' => $mealName
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            if ($response->clientError()) {
                throw new Exception('no data found: ' . $response->status());
            }

        } catch (Exception $e) {
            error_log('Meal search request failed: ' . $e->getMessage());
            return null;
        }
    }
}

$mealDB = new MealDB();
$results = $mealDB->searchMeals('pasta');