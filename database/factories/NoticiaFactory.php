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
        $tiposTitulos = [
            'Governo anuncia nova medida para',
            'Estudo revela que',
            'Empresa brasileira desenvolve',
            'Especialistas alertam sobre',
            'Nova pesquisa mostra',
            'Autoridades investigam',
            'Brasil registra aumento de',
            'Projeto visa melhorar',
            'Descoberta pode revolucionar',
            'Campanha pretende conscientizar sobre'
        ];
        
        $temas = [
            'educação pública', 'tecnologia 5G', 'mudanças climáticas', 'economia digital',
            'saúde mental', 'energias renováveis', 'inteligência artificial', 'sustentabilidade',
            'inclusão social', 'inovação tecnológica', 'segurança pública', 'turismo nacional'
        ];
        
        $inicioTitulo = fake()->randomElement($tiposTitulos);
        $tema = fake()->randomElement($temas);
        $titulo = $inicioTitulo . ' ' . $tema;
        
        // 70% publicadas, 20% rascunho, 10% arquivadas
        $statusDistribuicao = array_merge(
            array_fill(0, 7, 'publicada'),
            array_fill(0, 2, 'rascunho'),
            array_fill(0, 1, 'arquivada')
        );
        
        $status = fake()->randomElement($statusDistribuicao);
        $publicadaEm = $status === 'publicada' ? fake()->dateTimeBetween('-6 months', 'now') : null;
        
        return [
            'titulo' => $titulo,
            'slug' => Str::slug($titulo) . '-' . fake()->numberBetween(100000, 999999),
            'resumo' => fake()->paragraph(3),
            'conteudo' => $this->gerarConteudoRealista(),
            'status' => $status,
            'publicada_em' => $publicadaEm,
            'visualizacoes' => $status === 'publicada' ? fake()->numberBetween(50, 5000) : 0,
            'layout' => fake()->randomElement(['padrao', 'destaque', 'imagem-grande']),
            'imagem_capa' => Midia::factory(),
        ];
    }
    
    private function gerarConteudoRealista(): string
    {
        $paragrafos = [];
        
        // Parágrafo de abertura
        $paragrafos[] = fake()->paragraph(4);
        
        // Corpo da notícia (3-5 parágrafos)
        for ($i = 0; $i < fake()->numberBetween(3, 5); $i++) {
            $paragrafos[] = fake()->paragraph(5);
        }
        
        // Citação ou depoimento
        $especialista = fake()->name();
        $cargo = fake()->randomElement([
            'professor da Universidade', 'diretor da empresa', 'especialista em',
            'coordenador do projeto', 'pesquisador do instituto'
        ]);
        $paragrafos[] = '"' . fake()->sentence(10) . '", explica ' . $especialista . ', ' . $cargo . '.';
        
        // Parágrafos finais
        for ($i = 0; $i < fake()->numberBetween(2, 3); $i++) {
            $paragrafos[] = fake()->paragraph(4);
        }
        
        return implode("\n\n", $paragrafos);
    }

    public function configure()
    {
        return $this->afterCreating(function ($noticia) {
            // Não criamos tags aqui pois isso será feito no seeder
            // para evitar dependências circulares
        });
    }
}
