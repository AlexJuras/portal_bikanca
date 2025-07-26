<?php

namespace Database\Seeders;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create tags
        $tags = [
            ['nome' => 'Tecnologia', 'slug' => 'tecnologia', 'descricao' => 'Notícias sobre inovações e tecnologia'],
            ['nome' => 'Esportes', 'slug' => 'esportes', 'descricao' => 'Cobertura esportiva'],
            ['nome' => 'Política', 'slug' => 'politica', 'descricao' => 'Cenário político'],
            ['nome' => 'Cultura', 'slug' => 'cultura', 'descricao' => 'Arte e manifestações culturais'],
            ['nome' => 'Educação', 'slug' => 'educacao', 'descricao' => 'Temas educacionais'],
            ['nome' => 'Saúde', 'slug' => 'saude', 'descricao' => 'Bem-estar e medicina'],
            ['nome' => 'Economia', 'slug' => 'economia', 'descricao' => 'Mercado e finanças'],
            ['nome' => 'Meio Ambiente', 'slug' => 'meio-ambiente', 'descricao' => 'Sustentabilidade e ecologia'],
            ['nome' => 'Ciência', 'slug' => 'ciencia', 'descricao' => 'Descobertas científicas'],
            ['nome' => 'Internacional', 'slug' => 'internacional', 'descricao' => 'Notícias do mundo'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }

        // Create categories
        $categorias = [
            ['nome' => 'Notícias', 'slug' => 'noticias', 'descricao' => 'Últimas notícias e atualizações'],
            ['nome' => 'Reportagens', 'slug' => 'reportagens', 'descricao' => 'Conteúdo aprofundado'],
            ['nome' => 'Entrevistas', 'slug' => 'entrevistas', 'descricao' => 'Conversas e depoimentos'],
            ['nome' => 'Opinião', 'slug' => 'opiniao', 'descricao' => 'Artigos de opinião'],
            ['nome' => 'Especiais', 'slug' => 'especiais', 'descricao' => 'Coberturas especiais'],
            ['nome' => 'Editorial', 'slug' => 'editorial', 'descricao' => 'Posicionamento do jornal'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }

        // Create sample authors
        Autor::factory(5)->create();
    }
}