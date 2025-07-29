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
        
        // URLs de imagens de exemplo do Unsplash para diferentes categorias
        $imagensCategoria = [
            'politica' => 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=800&h=600',
            'tecnologia' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=800&h=600',
            'esportes' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&h=600',
            'saude' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=800&h=600',
            'economia' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&h=600',
            'cultura' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&h=600',
            'ciencia' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=800&h=600',
            'educacao' => 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=800&h=600',
            'ambiente' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600',
            'geral' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800&h=600'
        ];
        
        $categoria = fake()->randomElement(array_keys($imagensCategoria));
        $caminho = $imagensCategoria[$categoria];
        
        $legendas = [
            'Imagem ilustrativa sobre o tema da notícia',
            'Foto: arquivo/reprodução',
            'Registro feito durante o evento',
            'Imagem de divulgação',
            'Foto: agência de notícias',
            'Arquivo pessoal',
            'Reprodução/Internet'
        ];

        return [
            'caminho' => $caminho,
            'formato' => $formato,
            'legenda' => fake()->randomElement($legendas),
            'creditos' => fake()->randomElement(['Agência Brasil', 'Reuters', 'AFP', 'Arquivo Pessoal', 'Divulgação']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
