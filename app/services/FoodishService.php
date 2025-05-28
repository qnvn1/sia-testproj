<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FoodishService
{
    protected string $baseUrl = 'https://foodish-api.com/api/';

    public function getRandomImage(): string
    {
        $response = Http::get($this->baseUrl);

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch image URL.');
        }

        $json = $response->json();

        if (!isset($json['image'])) {
            throw new \Exception('Image URL not found in response.');
        }

        return $json['image'];
    }

    public function getSpecificImage($category, $imageName)
{
    return "https://foodish-api.com/images/{$category}/{$imageName}";
    }
}    