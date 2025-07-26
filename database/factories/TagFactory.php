<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nome = fake()->unique()->words(rand(1, 3), true);
        return [
            'nome' => ucwords($nome),
            'slug' => Str::slug($nome),
            'descricao' => fake()->sentence(rand(8, 15)),
        ];
    }
}
