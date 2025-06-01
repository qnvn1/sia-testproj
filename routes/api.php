<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MealPlanController;
use App\Services\FoodishService;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::prefix('meal')->group(function () {
    Route::get('/foodish/random', function (FoodishService $service) {
        try {
            $imageUrl = $service->getRandomImage();
            Log::info("Fetched random foodish image URL: $imageUrl");

            $imageResponse = Http::get($imageUrl);
            Log::info("Image response status: " . $imageResponse->status());

            if (!$imageResponse->successful()) {
                return response()->json([
                    'error' => 'Failed to fetch image from URL',
                    'status' => $imageResponse->status(),
                ], 500);
            }

            return Response::make(
                $imageResponse->body(),
                200,
                ['Content-Type' => $imageResponse->header('Content-Type')]
            );

        } catch (\Exception $e) {
            Log::error('Error loading random foodish image: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to load random image',
                'details' => $e->getMessage(),
            ], 500);
        }
    });
    
    Route::get('/foodish/burger', function (App\Services\FoodishService $service) {
    $imageUrl = $service->getSpecificImage('burger', 'burger1.jpg');
    $response = Http::get($imageUrl);

    if (!$response->successful()) {
        abort(500, 'Failed to fetch the image.');
    }

    return Response::make(
        $response->body(),
        200,
        ['Content-Type' => $response->header('Content-Type')]
    );
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
    
    Route::get('/advice/{id}', function (App\Services\AdviceSlipService $service, $id) {
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

Route::get('/foodish/test', function () {
    try {
        $imageUrl = 'https://foodish-api.com/images/pizza/pizza1.jpg';
        $imageResponse = Http::get($imageUrl);

        return Response::make(
            $imageResponse->body(),
            200,
            ['Content-Type' => $imageResponse->header('Content-Type')]
        );
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Manual test failed',
            'details' => $e->getMessage()
        ], 500);
    }
});