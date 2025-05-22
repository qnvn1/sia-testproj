<?php

namespace App\Http\Controllers;

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
                throw new \Exception('Server error');
            }
        } catch (\Exception $e) {
            Log::error('Spoonacular API request failed', ['message' => $e->getMessage()]);
            return null;
        }
    }
}

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

class FoodishService
{
    public function getImageUrl($category = 'butter-chicken', $image = 'butter-chicken16.jpg')
    {
        return config('services.foodish.base_uri') . "$category/$image";
    }
}
