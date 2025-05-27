<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExerciseService
{
    protected $baseUrl = 'https://api.api-ninjas.com/v1/exercises';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.exercise.api_key');
    }

    public function getExercisesByMuscle(string $muscle = 'biceps')
    {
        try {
            $response = Http::withHeaders([
                    'X-Api-Key' => $this->apiKey
                ])
                ->timeout(5)
                ->get($this->baseUrl, ['muscle' => $muscle]);

            return $response->throw()->json();
        } catch (\Exception $e) {
            Log::error("Exercise API Error: ".$e->getMessage());
            return ['error' => 'Exercise data unavailable'];
        }
    }

    public function getExercisesByType(string $type)
    {
        try {
            $response = Http::withHeaders([
                    'X-Api-Key' => $this->apiKey
                ])
                ->timeout(5)
                ->get($this->baseUrl, ['type' => $type]);

            return $response->throw()->json();
        } catch (\Exception $e) {
            Log::error("Exercise API Error: ".$e->getMessage());
            return ['error' => 'Exercise data unavailable'];
        }
    }
}