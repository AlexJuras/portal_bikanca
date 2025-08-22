<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'nome' => 'Banner Topo - Página Inicial',
                'posicao' => 'inicio_topo',
                'pagina' => 'inicio',
                'largura' => 728,
                'altura' => 90,
                'ativo' => true,
                'ordem' => 1,
                'descricao' => 'Banner superior da página inicial'
            ],
            [
                'nome' => 'Banner Meio - Página Inicial',
                'posicao' => 'inicio_meio',
                'pagina' => 'inicio',
                'largura' => 728,
                'altura' => 120,
                'ativo' => true,
                'ordem' => 1,
                'descricao' => 'Banner central da página inicial'
            ],
            [
                'nome' => 'Banner Principal - Lista de Notícias',
                'posicao' => 'noticias_principal',
                'pagina' => 'noticias',
                'largura' => 970,
                'altura' => 250,
                'ativo' => true,
                'ordem' => 1,
                'descricao' => 'Banner principal da página de notícias'
            ],
            [
                'nome' => 'Banner Horizontal - Lista de Notícias',
                'posicao' => 'noticias_horizontal',
                'pagina' => 'noticias',
                'largura' => 728,
                'altura' => 90,
                'ativo' => true,
                'ordem' => 1,
                'descricao' => 'Banner horizontal na lista de notícias'
            ],
            [
                'nome' => 'Banner Lateral - Lista de Notícias',
                'posicao' => 'noticias_lateral',
                'pagina' => 'noticias',
                'largura' => 300,
                'altura' => 250,
                'ativo' => true,
                'ordem' => 1,
                'descricao' => 'Banner lateral na lista de notícias'
            ],
            [
                'nome' => 'Banner Meio - Página da Notícia',
                'posicao' => 'noticia_meio',
                'pagina' => 'noticia',
                'largura' => 728,
                'altura' => 90,
                'ativo' => true,
                'ordem' => 1,
                'descricao' => 'Banner no meio do conteúdo da notícia'
            ],
            [
                'nome' => 'Banner Final - Página da Notícia',
                'posicao' => 'noticia_final',
                'pagina' => 'noticia',
                'largura' => 300,
                'altura' => 250,
                'ativo' => true,
                'ordem' => 1,
                'descricao' => 'Banner no final da notícia'
            ]
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
