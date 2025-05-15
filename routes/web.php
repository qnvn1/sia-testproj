<?php
use App\Http\Controllers\MealPlanController;

Route::get('/mealplan', [MealPlanController::class, 'index']);
