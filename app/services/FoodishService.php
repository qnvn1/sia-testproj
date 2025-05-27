<?php

namespace App\Services;

class FoodishService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://foodish-api.com/'; // Directly using the correct URL
    }

    public function getRandomImage($category = null)
    {
        return $this->baseUrl . 'api/' . ($category ? "images/$category" : '');
    }

    public function getSpecificImage($category, $imageName)
    {
        return $this->baseUrl . "api/images/$category/$imageName";
    }
}