<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MealPlanController;
use App\Services\FoodishService;
use App\Services\AdviceSlipService;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


 Route::prefix('meal')->group(function () {
    Route::get('/foodish/random', function () {
    try {
        $json = Http::get('https://foodish-api.com/api/')->json();
        $imageUrl = $json['image'] ?? null;

        if (!$imageUrl) {
            throw new \Exception('No image URL returned from Foodish API.');
        }

        // Fetch the actual image content
        $imageResponse = Http::get($imageUrl);

        if (!$imageResponse->successful()) {
            throw new \Exception('Failed to fetch image from URL.');
        }

        // Get image content and content type
        $imageContent = $imageResponse->body();
        $contentType = $imageResponse->header('Content-Type');

        // Return image directly
        return Response::make($imageContent, 200, ['Content-Type' => $contentType]);

    } catch (\Throwable $e) {
        return response("Error: " . $e->getMessage(), 500);
    }
});
    Route::get('/foodish/burger', function () {
    try {
        // Call Foodish API for a random burger image URL
        $json = Http::get('https://foodish-api.com/api/images/burger')->json();

        $imageUrl = $json['image'] ?? null;

        if (!$imageUrl) {
            throw new \Exception('No image URL returned from Foodish API.');
        }

        // Fetch the actual image binary content
        $imageResponse = Http::get($imageUrl);

        if (!$imageResponse->successful()) {
            throw new \Exception('Failed to fetch image from URL.');
        }

        $imageContent = $imageResponse->body();
        $contentType = $imageResponse->header('Content-Type') ?: 'image/jpeg';

        // Return the image directly to browser/client
        return Response::make($imageContent, 200, ['Content-Type' => $contentType]);

    } catch (\Throwable $e) {
        return response("Error: " . $e->getMessage(), 500);
    }
});

    Route::get('/test-postman', function () {
    return response()->json(['message' => 'Postman test works']);
});

    // Exercise API Tests
    Route::get('/exercises/biceps', function (App\Services\ExerciseService $service) {
        return $service->getExercisesByMuscle('biceps');
    });
    
    Route::get('/exercises/cardio', function (App\Services\ExerciseService $service) {
        return $service->getExercisesByType('cardio');
    });
    
    // AdviceSlip API Tests
    Route::get('/advice/random', function (App\Services\AdviceSlipService $service) {
        return $service->getRandomAdvice();
    });
    
    Route::get('/meal/advice/{id}', function (AdviceSlipService $service, $id) {
    return $service->getAdviceById($id);
    });
    
    // MealDB API Tests
    Route::get('/meals/search', function (App\Services\MealDbService $service) {
        return $service->searchMeals(request()->query('q', 'pasta'));
    });
    
    // Spoonacular API Tests
    Route::get('/meal-plan/weekly', function (App\Services\SpoonacularService $service) {
        return $service->generateWeeklyPlan();
    });
});

// Main API endpoints (via controller)
Route::prefix('meals')->group(function () {
    Route::get('/', [MealPlanController::class, 'index']);
    Route::get('/search', [MealPlanController::class, 'searchMeal']);
    Route::get('/random-image', [MealPlanController::class, 'showMealImage']);
    Route::get('/random-advice', [MealPlanController::class, 'showRandomAdvice']);
    Route::get('/advice/{id}', [MealPlanController::class, 'showAdviceById']);
    Route::get('/exercises', [MealPlanController::class, 'showExercises']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::prefix('meals')->group(function () {
        Route::post('/save', [MealPlanController::class, 'saveMeal']);
        Route::get('/meal-plan', [MealPlanController::class, 'generateMealPlan']);
        Route::post('/meal-plan/items', [MealPlanController::class, 'saveMealItem']);
    });
});