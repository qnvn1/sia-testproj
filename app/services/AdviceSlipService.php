<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdviceSlipService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.advice.base_uri', 'https://api.adviceslip.com'), '/');
    }

    public function getRandomAdvice()
    {
        try {
            $response = Http::timeout(5)
                ->retry(2, 100)
                ->get("{$this->baseUrl}/advice");
                
            return $response->throw()->json();
        } catch (\Exception $e) {
            Log::error('AdviceSlip API Error: '.$e->getMessage());
            return null;
        }
    }

    public function getAdviceById($id)
    {
        return Cache::remember("advice.{$id}", now()->addHour(), function() use ($id) {
            try {
                $response = Http::timeout(5)
                    ->retry(2, 100)
                    ->get("{$this->baseUrl}/advice/{$id}");
                    
                $data = $response->throw()->json();
                
                // Validate response structure
                if (!isset($data['slip'])) {
                    throw new \RuntimeException('Invalid API response structure');
                }
                
                return $data;
            } catch (\Exception $e) {
                Log::error("AdviceSlip API Error for ID {$id}: ".$e->getMessage());
                return null;
            }
        });
    }
}