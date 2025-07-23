<?php

namespace Database\Factories;

use App\Models\Autor;
use App\Models\Midia;
use App\Models\Tag;
use App\Models\Categoria;
use App\Models\Noticia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $titulo = fake()->sentence(6);
        return [
            'titulo' => $titulo,
            'slug' => Str::slug($titulo),
            'resumo' => fake()->paragraph(),
            'conteudo' => fake()->paragraphs(5, true),
            'status' => fake()->randomElement(['rascunho', 'publicada', 'arquivada', 'agendada']),
            'publicada_em' => fake()->dateTimeBetween('-1 year', 'now'),
            'visualizacoes' => fake()->numberBetween(0, 1000),
            'layout' => fake()->randomElement(['padrao', 'destaque', 'imagem-grande']),
            'autor_id' => Autor::factory(),
            'categoria_id' => Categoria::factory(),
            'imagem_capa' => Midia::factory(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($noticia) {
            // Anexa 1 a 5 tags aleatórias à notícia
            $noticia->tags()->attach(
                Tag::factory(fake()->numberBetween(1, 5))->create()
            );
        });
    }
}
