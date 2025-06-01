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

    public function getAdviceById(int|string $id): ?array
{
    try {
        $response = Http::timeout(10)->get("{$this->baseUrl}/advice/{$id}");
        $response->throw();

        $data = $response->json();

        if (!isset($data['slip'])) {
            Log::warning("AdviceSlip API unexpected structure for ID {$id}");
            return null;
        }

        return $data;

    } catch (\Throwable $e) {
        Log::error("AdviceSlip API error for ID {$id}: " . $e->getMessage(), ['exception' => $e]);
        return null;
    }
    }
}