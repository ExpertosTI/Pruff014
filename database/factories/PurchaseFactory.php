<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Client;
use App\Models\Placement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    // Las compras de prueba son como las transacciones del supermercado: realistas pero ficticias
    public function definition()
    {
        $quantity = fake()->numberBetween(1, 50);
        $unitPrice = fake()->randomFloat(2, 5, 500);
        
        return [
            'client_id' => Client::factory(),
            'article_id' => Article::factory(),
            'placement_id' => Placement::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $quantity * $unitPrice,
        ];
    }
}