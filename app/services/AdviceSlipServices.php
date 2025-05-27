<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AdviceSlipService
{
    protected $baseUrl = 'https://api.adviceslip.com/advice'

    public function __construct()
    {
        
        $this->baseUrl = config('services.advice.base_uri');
    }

    public function getAdviceById($id)
    {
        $response = Http::get("{$this->baseUrl}/{$id}");

        if ($response->successful()) {
            return $response->json(); // You might want to extract only 'slip' if needed
        }

        return null;
    }

    public function getRandomAdvice()
    {
        $response = Http::get($this->baseUrl);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
