<?php

namespace App\Http\Controllers;

use App\Services\AdviceSlipService;
use App\Services\ExerciseService;
use App\Services\FoodishService;
use App\Services\MealDbService;
use App\Services\SpoonacularServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealPlanController extends Controller
{
    protected $adviceService;
    protected $exerciseService;
    protected $foodishService;
    protected $mealDbService;
    protected $spoonacularService;

    public function __construct(
        AdviceSlipService $adviceService,
        ExerciseService $exerciseService,
        FoodishService $foodishService,
        MealDbService $mealDbService,
        SpoonacularServices $spoonacularService
    ) {
        $this->adviceService = $adviceService;
        $this->exerciseService = $exerciseService;
        $this->foodishService = $foodishService;
        $this->mealDbService = $mealDbService;
        $this->spoonacularService = $spoonacularService;
    }

    // Main endpoint
    public function index()
    {
        return response()->json([
            'message' => 'Meal Plan API',
            'endpoints' => [
                '/search' => 'Search meals',
                '/random-image' => 'Get random food image',
                '/random-advice' => 'Get random advice',
                '/advice/{id}' => 'Get specific advice',
                '/exercises' => 'Get exercises by muscle'
            ]
        ]);
    }

    // Search meals (MealDB)
    public function searchMeal(Request $request)
    {
        $request->validate(['q' => 'sometimes|string']);
        $mealName = $request->query('q', 'pasta');
        return $this->mealDbService->searchMeals($mealName);
    }

    // Random food image (Foodish)
    public function showMealImage()
    {
        return response()->json([
            'image_url' => $this->foodishService->getImageUrl('pizza')
        ]);
    }

    // Random advice (AdviceSlip)
    public function showRandomAdvice()
    {
        return $this->adviceService->getRandomAdvice();
    }

    // Specific advice (AdviceSlip)
    public function showAdviceById($id)
    {
        return $this->adviceService->getAdviceById($id) ?? 
               response()->json(['error' => 'Advice not found'], 404);
    }

    // Exercises (Exercise API)
    public function showExercises(Request $request)
    {
        $request->validate(['muscle' => 'sometimes|string']);
        $muscle = $request->query('muscle', 'biceps');
        return $this->exerciseService->getExercisesByMuscle($muscle) ?? 
               response()->json(['error' => 'Exercises not found'], 404);
    }

    // Protected: Save meal plan (Spoonacular)
    public function saveMeal(Request $request)
    {
        $validated = $request->validate([
            'meal_id' => 'required',
            'meal_name' => 'required',
            'date' => 'required|date'
        ]);

        // Get user-specific data
        $username = Auth::user()->username; // Assuming you have username
        $hash = config('services.spoonacular.hash');

        // Call Spoonacular API
        $response = $this->spoonacularService->saveMeal(
            $username,
            $hash,
            $validated
        );

        return $response ?? response()->json(['error' => 'Failed to save meal'], 500);
    }
}