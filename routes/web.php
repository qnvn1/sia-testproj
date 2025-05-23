<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MealPlanController;

Route::get('/meals', [MealPlanController::class, 'showMeals']);
Route::get('/search-meal', [MealPlanController::class, 'searchMeal']);
Route::get('/meal-image', [MealPlanController::class, 'showMealImage']);
Route::get('/advice', [MealPlanController::class, 'showRandomAdvice']);
Route::get('/advice/{id}', [MealPlanController::class, 'showAdviceById']);
