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
        $especialidades = [
            'jornalismo investigativo', 'política', 'economia', 'esportes', 'tecnologia',
            'cultura', 'internacional', 'saúde', 'educação', 'meio ambiente'
        ];
        
        $especialidade = fake()->randomElement($especialidades);
        $anosExperiencia = fake()->numberBetween(2, 25);
        
        return [
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'bio' => "Jornalista especializado em {$especialidade} com {$anosExperiencia} anos de experiência. " . 
                     fake()->sentence(rand(10, 20)),
            'foto' => 'autores/autor-' . fake()->numberBetween(1, 10) . '.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
