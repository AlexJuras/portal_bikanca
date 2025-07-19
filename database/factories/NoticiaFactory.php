<?php

namespace Database\Factories;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Noticia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Noticia>
 */
class NoticiaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Noticia::class;

    public function definition(): array
    {
        $titulo = $this->faker->sentence(6, true);
        $slug = Str::slug($titulo);

        return [
            'titulo' => $titulo,
            'slug' => $slug,
            'resumo' => $this->faker->paragraph,
            'conteudo' => $this->faker->paragraphs(5, true),

            'status' => $this->faker->randomElement(['rascunho', 'publicada', 'agendada']),
            'publicada_em' => now()->subDays(rand(0, 30)),
            'visualizacoes' => rand(0, 1000),

            'layout' => $this->faker->randomElement(['padrao', 'com-galeria', 'destaque']),

            // Associações
            'autor_id' => Autor::factory(),
            'categoria_id' => Categoria::factory(),
        ];
    }
}
