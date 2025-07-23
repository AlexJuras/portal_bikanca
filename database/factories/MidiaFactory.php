<?php

namespace Database\Factories;

use App\Models\Midia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Midia>
 */
class MidiaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $formato = fake()->randomElement(array_keys(Midia::FORMATOS));

        $dimensoes = Midia::FORMATOS[$formato];
        return [
            'caminho' => 'midias/imagem-' . fake()->numberBetween(1, 100) . '.jpg',
            'formato' => $formato,
            'legenda' => fake()->sentence(),
            'creditos' => fake()->name(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
