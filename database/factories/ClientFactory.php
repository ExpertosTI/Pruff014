<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    // Los clientes falsos son como los amigos imaginarios: útiles para practicar
    public function definition()
    {
        return [
            'name' => fake()->company(),
            'phone_number' => fake()->phoneNumber(),
            'client_type' => fake()->randomElement(['regular', 'premium']),
        ];
    }
}
