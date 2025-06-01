<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdviceSlipService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.advice.base_uri', 'https://api.adviceslip.com');
    }

    public function getRandomAdvice()
    {
        try {
            $response = Http::timeout(3)->get("{$this->baseUrl}/advice");
            return $response->throw()->json();
        } catch (\Exception $e) {
            Log::error('AdviceSlip API Error: '.$e->getMessage());
            return ['error' => 'Failed to get advice', 'details' => $e->getMessage()];
        }
    }

    public function getAdviceById($id)
{
    try {
        $url = "{$this->baseUrl}/advice/{$id}";
        $response = Http::timeout(5)->get($url);

        if ($response->status() == 404) {
            return response()->json([
                'error' => true,
                'message' => "Advice with ID {$id} not found."
            ], 404);
        }

        $response->throw(); // throws exceptions for other HTTP errors

        $json = $response->json();

        return response()->json([
            'data' => $json
        ]);

    } catch (\Throwable $e) {
        \Log::error("Advice API Exception: " . $e->getMessage());

        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 500);
    }
}
}