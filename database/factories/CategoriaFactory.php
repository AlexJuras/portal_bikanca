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
        $categorias = [
            'Política' => 'Notícias sobre política nacional e internacional',
            'Economia' => 'Mercado financeiro, negócios e economia',
            'Esportes' => 'Cobertura esportiva e resultados',
            'Tecnologia' => 'Inovações tecnológicas e gadgets',
            'Saúde' => 'Medicina, bem-estar e saúde pública',
            'Educação' => 'Ensino, universidades e política educacional',
            'Cultura' => 'Arte, música, cinema e literatura',
            'Ciência' => 'Descobertas científicas e pesquisas',
            'Meio Ambiente' => 'Sustentabilidade e questões ambientais',
            'Internacional' => 'Notícias do exterior e relações internacionais',
            'Segurança' => 'Segurança pública e criminalidade',
            'Turismo' => 'Destinos turísticos e viagem',
            'Gastronomia' => 'Culinária e restaurantes',
            'Moda' => 'Tendências e indústria da moda',
            'Automóveis' => 'Carros, motos e setor automotivo'
        ];

        $categoria = fake()->unique()->randomElement(array_keys($categorias));
        $descricao = $categorias[$categoria];

        return [
            'nome' => $categoria,
            'slug' => Str::slug($categoria),
            'descricao' => $descricao,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
