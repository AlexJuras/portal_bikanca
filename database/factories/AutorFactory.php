<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Autor>
 */
class AutorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'bio' => fake()->paragraphs(1, true),
            'foto' => 'autores/autor-' . fake()->numberBetween(1, 10) . '.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
