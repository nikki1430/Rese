<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Shop;

class FavoriteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 3,
            'shop_id' => function () {
                return Shop::inRandomOrder()->first()->id;
            },
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
