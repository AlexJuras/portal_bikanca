<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Anuncio;
use App\Models\AnuncioPagina;

class AnunciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar anúncios de teste
        $anuncios = [
            [
                'nome' => 'Banner Home Topo',
                'imagem' => 'https://via.placeholder.com/728x90/007bff/ffffff?text=Anúncio+Home+Topo',
                'link' => 'https://example.com/home-topo',
                'ativo_global' => true,
            ],
            [
                'nome' => 'Banner Home Meio',
                'imagem' => 'https://via.placeholder.com/728x120/28a745/ffffff?text=Anúncio+Home+Meio',
                'link' => 'https://example.com/home-meio',
                'ativo_global' => true,
            ],
            [
                'nome' => 'Banner Home Rodapé',
                'imagem' => 'https://via.placeholder.com/728x120/ffc107/000000?text=Anúncio+Home+Rodapé',
                'link' => 'https://example.com/home-rodape',
                'ativo_global' => true,
            ],
            [
                'nome' => 'Banner Notícias Principal',
                'imagem' => 'https://via.placeholder.com/970x250/dc3545/ffffff?text=Anúncio+Notícias+Principal',
                'link' => 'https://example.com/noticias-principal',
                'ativo_global' => true,
            ],
            [
                'nome' => 'Banner Notícias Lateral',
                'imagem' => 'https://via.placeholder.com/300x250/6610f2/ffffff?text=Anúncio+Notícias+Lateral',
                'link' => 'https://example.com/noticias-lateral',
                'ativo_global' => true,
            ],
            [
                'nome' => 'Banner Vídeos',
                'imagem' => 'https://via.placeholder.com/728x90/fd7e14/ffffff?text=Anúncio+Vídeos',
                'link' => 'https://example.com/videos',
                'ativo_global' => true,
            ],
        ];

        foreach ($anuncios as $anuncioData) {
            $anuncio = Anuncio::create($anuncioData);
        }

        // Atribuir anúncios às páginas
        $anunciosIds = Anuncio::pluck('id')->toArray();

        // Página Home - 3 anúncios
        AnuncioPagina::create([
            'anuncio_id' => $anunciosIds[0], // Banner Home Topo
            'pagina' => 'home',
            'ordem' => 1,
            'ativo' => true,
        ]);

        AnuncioPagina::create([
            'anuncio_id' => $anunciosIds[1], // Banner Home Meio
            'pagina' => 'home',
            'ordem' => 2,
            'ativo' => true,
        ]);

        AnuncioPagina::create([
            'anuncio_id' => $anunciosIds[2], // Banner Home Rodapé
            'pagina' => 'home',
            'ordem' => 3,
            'ativo' => true,
        ]);

        // Página Notícias - 2 anúncios
        AnuncioPagina::create([
            'anuncio_id' => $anunciosIds[3], // Banner Notícias Principal
            'pagina' => 'noticias',
            'ordem' => 1,
            'ativo' => true,
        ]);

        AnuncioPagina::create([
            'anuncio_id' => $anunciosIds[4], // Banner Notícias Lateral
            'pagina' => 'noticias',
            'ordem' => 2,
            'ativo' => true,
        ]);

        // Página Vídeos - 1 anúncio
        AnuncioPagina::create([
            'anuncio_id' => $anunciosIds[5], // Banner Vídeos
            'pagina' => 'videos',
            'ordem' => 1,
            'ativo' => true,
        ]);

        echo "✅ Anúncios de teste criados com sucesso!\n";
        echo "📊 Total de anúncios: " . count($anuncios) . "\n";
        echo "🏠 Home: 3 anúncios\n";
        echo "📰 Notícias: 2 anúncios\n";
        echo "🎥 Vídeos: 1 anúncio\n";
        echo "📂 Categoria: 0 anúncios (para testar páginas sem anúncios)\n";
    }
}
