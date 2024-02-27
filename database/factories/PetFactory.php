<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pet;

class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'weight' => $this->faker->randomFloat(2),
            'size' => 'SMALL',
            'age' => $this->faker->randomNumber()
        ];
    }
}

