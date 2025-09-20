<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Placement>
 */
class PlacementFactory extends Factory
{
    // Las colocaciones de prueba son como los espacios de exhibición: organizados y con precio
    public function definition()
    {
        return [
            'article_id' => Article::factory(),
            'name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 10, 1000),
            'location' => fake()->address(),
        ];
    }
}