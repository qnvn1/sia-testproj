<?php

Route::get('/test-apis', function() {
    // Test each service directly
    $advice = app(App\Services\AdviceSlipService::class)->getRandomAdvice();
    $exercise = app(App\Services\ExerciseService::class)->getExercisesByMuscle('biceps');
    
    return response()->json([
        'configurations' => [
            'advice' => config('services.advice'),
            'exercise' => config('services.exercise'),
            'foodish' => config('services.foodish'),
            'mealdb' => config('services.mealdb'),
            'spoonacular' => config('services.spoonacular')
        ],
        'api_tests' => [
            'advice' => $advice,
            'exercise' => $exercise
        ]
    ]);
    Route::get('/test-postman', function () {
    return response()->json(['message' => 'Postman test works']);
});
});
