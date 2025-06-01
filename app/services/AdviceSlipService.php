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

        \Log::info("Calling Advice API: {$url}");
        \Log::info("Status: " . $response->status());
        \Log::info("Body: " . $response->body());

        if (!$response->successful()) {
            throw new \Exception("Non-success status code: " . $response->status());
        }

        $json = json_decode($response->body(), true);

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