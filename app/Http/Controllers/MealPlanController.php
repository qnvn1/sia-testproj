<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SpoonacularServices;
use App\Services\MealDbService;
use App\Services\FoodishService;
use App\Services\AdviceSlipService;
use App\Services\ExerciseService; 

class MealPlanController extends Controller
{
    protected $spoonacularService;
    protected $mealDbService;
    protected $foodishService;
    protected $adviceSlipService;
    protected $exerciseService; 

    public function __construct(
        SpoonacularServices $spoonacularService,
        MealDbService $mealDbService,
        FoodishService $foodishService,
        AdviceSlipService $adviceSlipService,
        ExerciseService $exerciseService 
    ) {
        $this->spoonacularService = $spoonacularService;
        $this->mealDbService = $mealDbService;
        $this->foodishService = $foodishService;
        $this->adviceSlipService = $adviceSlipService;
        $this->exerciseService = $exerciseService; 
    }

    public function showMeals()
    {
        $meals = $this->spoonacularService->getMealsByCarbs(10, 50);
        return view('meals.index', compact('meals'));
    }

    public function searchMeal(Request $request)
    {
        $results = $this->mealDbService->searchMeal($request->input('meal'));
        return view('meals.search', compact('results'));
    }

    public function showMealImage()
    {
        $imageUrl = $this->foodishService->getImageUrl();
        return view('meals.image', compact('imageUrl'));
    }

    public function showRandomAdvice()
    {
        $advice = $this->adviceSlipService->getRandomAdvice();
        return view('meals.advice', ['advice' => $advice['slip']['advice'] ?? 'No advice found.']);
    }

    public function showAdviceById($id)
    {
        $advice = $this->adviceSlipService->getAdviceById($id);
        return view('meals.advice', ['advice' => $advice['slip']['advice'] ?? 'No advice found.']);
    }

    public function showExercises(Request $request)
    {
        $muscle = $request->input('muscle', 'biceps');
        $exercises = $this->exerciseService->getExercisesByMuscle($muscle);

        return view('meals.exercises', compact('exercises', 'muscle'));
    }
}
