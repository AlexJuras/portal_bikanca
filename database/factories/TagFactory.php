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
        $tags = [
            'Eleições', 'Congresso', 'Presidente', 'Reforma', 'COVID-19', 'Vacina', 
            'Olimpíadas', 'Copa do Mundo', 'Futebol', 'Basquete', 'Vôlei',
            'Inteligência Artificial', 'Smartphone', 'Internet', 'Aplicativo', 'Software',
            'Universidade', 'ENEM', 'Professores', 'Ensino Superior', 'Bolsa de Estudos',
            'Cinema', 'Música', 'Teatro', 'Festival', 'Arte', 'Literatura',
            'Pesquisa', 'NASA', 'Medicina', 'Descoberta', 'Estudo',
            'Sustentabilidade', 'Reciclagem', 'Energia Renovável', 'Aquecimento Global',
            'Mercado', 'Bolsa', 'Dólar', 'Inflação', 'PIB', 'Emprego',
            'Segurança Pública', 'Polícia', 'Crime', 'Violência',
            'Viagem', 'Turismo', 'Hotel', 'Destino', 'Férias',
            'Receita', 'Restaurante', 'Chef', 'Culinária',
            'Tendência', 'Fashion Week', 'Estilo', 'Moda',
            'Carro', 'Moto', 'Elétrico', 'Combustível'
        ];

        $nome = fake()->unique()->randomElement($tags);
        
        return [
            'nome' => $nome,
            'slug' => Str::slug($nome),
            'descricao' => fake()->sentence(rand(8, 15)),
        ];
    }
}
