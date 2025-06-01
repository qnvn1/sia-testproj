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
        Log::info("Calling URL: $url");
        $response = Http::timeout(3)->get($url);
        $data = $response->json();
        Log::info('Response data:', $data);
        return $data;
    } catch (\Exception $e) {
        Log::error("AdviceSlip API Error for ID {$id}: ".$e->getMessage());
        return ['error' => 'Failed to fetch advice', 'details' => $e->getMessage()];
    }
    }
}