<?php

return [
    // ...

    'advice' => [
        'base_uri' => env('ADVICE_API_URL', 'https://api.adviceslip.com'),
    ],

    'exercise' => [
        'base_url' => env('EXERCISE_API_URL', 'https://api.api-ninjas.com/v1/exercises'),
        'api_key' => env('EXERCISE_API_KEY'),
    ],

    'foodish' => [
        'base_url' => env('FOODISH_API_URL', 'https://foodish-api.herokuapp.com/api/'),
    ],

    'mealdb' => [
        'base_uri' => env('MEALDB_API_URL', 'https://www.themealdb.com/api/json/v1/1/'),
    ],

    'spoonacular' => [
        'base_uri' => env('SPOONACULAR_BASE_URI', 'https://api.spoonacular.com/'),
        'key' => env('SPOONACULAR_KEY'),
        'hash' => env('SPOONACULAR_HASH'),
    ],
];
