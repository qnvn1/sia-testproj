<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MealPlanController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Meal routes (public)
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
    // Auth routes
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Protected meal routes
    Route::prefix('meals')->group(function () {
        Route::post('/save', [MealPlanController::class, 'saveMeal']);
        // Add other protected meal endpoints here
    });
});