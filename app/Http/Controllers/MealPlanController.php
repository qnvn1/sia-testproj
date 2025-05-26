<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SpoonacularServices;
use App\Services\MealDbService;
use App\Services\FoodishService;
use App\Services\AdviceSlipService;
use App\Services\ExerciseService;
use Illuminate\Http\JsonResponse;

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

    /**
     * Display a listing of meals.
     */
    public function index(): JsonResponse
    {
        try {
            $meals = $this->spoonacularService->getMealsByCarbs(10, 50);
            return $this->successResponse($meals);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Search for meals by name.
     */
    public function searchMeal(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'meal' => 'required|string|min:2'
            ]);

            $results = $this->mealDbService->searchMeal($request->input('meal'));
            return $this->successResponse($results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get a random meal image.
     */
    public function showMealImage(): JsonResponse
    {
        try {
            $imageUrl = $this->foodishService->getImageUrl();
            return $this->successResponse(['image_url' => $imageUrl]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Get random advice.
     */
    public function showRandomAdvice(): JsonResponse
    {
        try {
            $advice = $this->adviceSlipService->getRandomAdvice();
            return $this->successResponse([
                'advice' => $advice['slip']['advice'] ?? 'No advice found'
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Get advice by ID.
     */
    public function showAdviceById($id): JsonResponse
    {
        try {
            $advice = $this->adviceSlipService->getAdviceById($id);
            return $this->successResponse([
                'advice' => $advice['slip']['advice'] ?? 'No advice found for this ID'
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Get exercises by muscle group.
     */
    public function showExercises(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'muscle' => 'sometimes|string|min:3'
            ]);

            $muscle = $request->input('muscle', 'biceps');
            $exercises = $this->exerciseService->getExercisesByMuscle($muscle);
            
            return $this->successResponse([
                'muscle' => $muscle,
                'exercises' => $exercises
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Save a meal to user favorites (protected route).
     */
    public function saveMeal(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'meal_id' => 'required|string',
                'meal_name' => 'required|string'
            ]);

            // Add your save logic here
            // $saved = $this->mealService->saveUserMeal(auth()->id(), $request->all());
            
            return $this->successResponse([], 'Meal saved successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Helper method for successful responses.
     */
    protected function successResponse($data = [], string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Helper method for error responses.
     */
    protected function errorResponse(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null
        ], $statusCode);
    }
}