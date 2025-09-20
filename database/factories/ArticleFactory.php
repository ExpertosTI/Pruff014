<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    // Los artículos de prueba son como los juguetes de demostración: no son reales pero sirven
    public function definition()
    {
        return [
            'barcode' => fake()->unique()->ean13(),
            'description' => fake()->sentence(4),
            'manufacturer' => fake()->company(),
        ];
    }
}
