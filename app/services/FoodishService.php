<?php
class FoodishService
{
    public function getImageUrl($category = 'butter-chicken', $image = 'butter-chicken16.jpg')
    {
        return config('services.foodish.base_uri') . "$category/$image";
    }
}
