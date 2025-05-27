<?php

return [

    'spoonacular' => [
        'key' => env('SPOONACULAR_API_KEY'),
        'base_uri' => 'https://api.spoonacular.com/',
    ],

    'advice' => [
        'base_uri' => env('ADVICE_API_URL'),
    ],

    'foodish' => [
        'base_uri' => env('FOODISH_API_URL'),
    ],

    'mealdb' => [
        'base_uri' => env('MEALDB_API_URL'),
    ],

    'exercise' => [
        'base_uri' => env('EXERCISE_API_URL'),
        'key' => env('API_NINJAS_KEY'),
    ],

];

