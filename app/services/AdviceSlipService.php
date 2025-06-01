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

        \Log::info("API CALL to: $url");
        \Log::info("Response Body: " . $response->body());

        if (!$response->successful()) {
            throw new \Exception("Failed with status code {$response->status()}");
        }

        return response()->json([
            'from_api' => json_decode($response->body(), true)
        ]);

    } catch (\Exception $e) {
        \Log::error("Advice API Error: " . $e->getMessage());

        return response()->json([
            'error' => 'Something went wrong',
            'message' => $e->getMessage()
        ], 500);
    }
}
}