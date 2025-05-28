<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MealPlanController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::prefix('test')->group(function () {
    Route::get('/foodish/random', function (App\Services\FoodishService $service) {
    return redirect($service->getRandomImage());
    });
    
    Route::get('/foodish/burger', function (App\Services\FoodishService $service) {
        return $service->getSpecificImage('burger', 'burger1.jpg');
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