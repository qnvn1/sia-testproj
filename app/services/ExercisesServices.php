<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExerciseService{
     private const API_BASE_URL = 'https://api.api-ninjas.com/v1/exercises?';
 
    public function getExercisesByMuscle(string $muscle = 'biceps'): ?array
    {
        try {
            $response = Http::withHeaders([
                'X-Api-Key' => config('services.exercise.api_key'),
            ])->get(config('services.exercise.base_url'), [
                'muscle' => $muscle,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            // Log errors (client 4xx or server 5xx)
            \Log::error('Exercise API request failed', [
                'status' => $response->status(),
                'error'  => $response->body(),
            ]);

            return null;

        } catch (\Exception $e) {
            \Log::error('Exercise API exception: ' . $e->getMessage());
            return null;
        }
    }
}