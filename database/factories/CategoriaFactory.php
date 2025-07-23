<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nome = fake()->unique()->words(1, true);
        return [
            'nome' => ucwords($nome),
            'slug' => Str::slug($nome),
            'descricao' => fake()->sentence(3),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
