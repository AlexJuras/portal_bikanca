<?php

namespace Database\Seeders;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Tag;
use App\Models\User;
use App\Models\Noticia;
use App\Models\Midia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@bikanca.com.br',
        ]);

        // Create predefined categories
        $categorias = [
            ['nome' => 'Política', 'slug' => 'politica', 'descricao' => 'Notícias sobre política nacional e local'],
            ['nome' => 'Economia', 'slug' => 'economia', 'descricao' => 'Mercado financeiro e economia'],
            ['nome' => 'Esportes', 'slug' => 'esportes', 'descricao' => 'Cobertura esportiva completa'],
            ['nome' => 'Tecnologia', 'slug' => 'tecnologia', 'descricao' => 'Inovações e tecnologia'],
            ['nome' => 'Saúde', 'slug' => 'saude', 'descricao' => 'Medicina e bem-estar'],
            ['nome' => 'Educação', 'slug' => 'educacao', 'descricao' => 'Ensino e educação'],
            ['nome' => 'Cultura', 'slug' => 'cultura', 'descricao' => 'Arte, música e entretenimento'],
            ['nome' => 'Internacional', 'slug' => 'internacional', 'descricao' => 'Notícias do mundo'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }

        // Create predefined tags
        $tags = [
            'Eleições', 'Congresso', 'Presidente', 'Reforma', 'COVID-19', 'Vacina', 
            'Olimpíadas', 'Copa do Mundo', 'Futebol', 'Basquete', 'Vôlei',
            'Inteligência Artificial', 'Smartphone', 'Internet', 'Aplicativo',
            'Universidade', 'ENEM', 'Professores', 'Ensino Superior',
            'Cinema', 'Música', 'Teatro', 'Festival', 'Arte',
            'Pesquisa', 'NASA', 'Medicina', 'Descoberta',
            'Sustentabilidade', 'Energia Renovável', 'Aquecimento Global',
            'Mercado', 'Bolsa', 'Dólar', 'Inflação', 'PIB',
            'Segurança Pública', 'Polícia', 'Crime',
            'Viagem', 'Turismo', 'Hotel', 'Destino'
        ];

        foreach ($tags as $tagNome) {
            Tag::create([
                'nome' => $tagNome,
                'slug' => Str::slug($tagNome),
                'descricao' => "Tag sobre {$tagNome}"
            ]);
        }

        // Create authors with realistic profiles
        $autores = [
            [
                'nome' => 'Maria Silva Santos',
                'email' => 'maria.santos@bikanca.com.br',
                'bio' => 'Jornalista especializada em política com 15 anos de experiência. Formada em Comunicação Social pela UFPI.',
                'foto' => 'autores/maria-santos.jpg'
            ],
            [
                'nome' => 'João Pedro Oliveira',
                'email' => 'joao.oliveira@bikanca.com.br',
                'bio' => 'Repórter esportivo há 8 anos, cobrindo principalmente futebol piauiense e nacional.',
                'foto' => 'autores/joao-oliveira.jpg'
            ],
            [
                'nome' => 'Ana Carolina Lima',
                'email' => 'ana.lima@bikanca.com.br',
                'bio' => 'Jornalista econômica com MBA em Finanças. Especialista em mercado financeiro e economia regional.',
                'foto' => 'autores/ana-lima.jpg'
            ],
            [
                'nome' => 'Carlos Eduardo Costa',
                'email' => 'carlos.costa@bikanca.com.br',
                'bio' => 'Correspondente internacional e especialista em política externa. Formado em Relações Internacionais.',
                'foto' => 'autores/carlos-costa.jpg'
            ],
            [
                'nome' => 'Fernanda Rocha',
                'email' => 'fernanda.rocha@bikanca.com.br',
                'bio' => 'Jornalista cultural com foco em arte, música e literatura. Mestranda em Comunicação.',
                'foto' => 'autores/fernanda-rocha.jpg'
            ]
        ];

        foreach ($autores as $autor) {
            Autor::create($autor);
        }

        // Create additional random authors
        Autor::factory(5)->create();

        // Get all created entities for relationships
        $categorias = Categoria::all();
        $autores = Autor::all();
        $tags = Tag::all();

        // Create a good amount of news articles
        echo "Criando notícias...\n";
        
        for ($i = 0; $i < 50; $i++) {
            $noticia = Noticia::factory()->create([
                'autor_id' => $autores->random()->id,
                'categoria_id' => $categorias->random()->id,
            ]);

            // Attach random tags (1-4 tags per article)
            $tagsParaNoticia = $tags->random(rand(1, 4));
            $noticia->tags()->attach($tagsParaNoticia->pluck('id'));
            
            if ($i % 10 == 0) {
                echo "Criadas " . ($i + 1) . " notícias...\n";
            }
        }

        echo "Seeding concluído! Criado:\n";
        echo "- " . User::count() . " usuários\n";
        echo "- " . Categoria::count() . " categorias\n";
        echo "- " . Tag::count() . " tags\n";
        echo "- " . Autor::count() . " autores\n";
        echo "- " . Noticia::count() . " notícias\n";
        echo "- " . Midia::count() . " mídias\n";
    }
}