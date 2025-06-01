<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdviceSlipService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.advice.base_url', 'https://api.adviceslip.com');
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
}