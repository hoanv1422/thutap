<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 1, 100),
            'stock' => fake()->numberBetween(1, 100),
            'description' => fake()->sentence(10),
            'image' => fake()->imageUrl(),
            'is_active' => fake()->boolean(),
        ];
    }
}
