<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Anuncio;

class AnuncioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anuncios = [
            [
                'nome' => 'Banner Topo - Home',
                'posicao' => 'banner-topo',
                'pagina' => 'home',
                'tipo' => 'html',
                'html_content' => '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; text-align: center; color: white; border-radius: 8px;">
                    <h2 style="margin: 0; font-size: 2em;">Espaço Publicitário Disponível</h2>
                    <p style="margin: 10px 0 0 0; opacity: 0.9;">970x250 - Banner Topo da Página Inicial</p>
                </div>',
                'dimensoes' => '970x250',
                'largura' => 970,
                'altura' => 250,
                'ativo' => true,
                'ordem' => 1,
            ],
            [
                'nome' => 'Banner Lateral Topo - Todas',
                'posicao' => 'banner-lateral-topo',
                'pagina' => 'todas',
                'tipo' => 'html',
                'html_content' => '<div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 30px; text-align: center; color: white; border-radius: 8px; height: 220px; display: flex; flex-direction: column; justify-content: center;">
                    <h3 style="margin: 0; font-size: 1.2em;">Anúncio Lateral</h3>
                    <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 0.9em;">300x250</p>
                </div>',
                'dimensoes' => '300x250',
                'largura' => 300,
                'altura' => 250,
                'ativo' => true,
                'ordem' => 1,
            ],
            [
                'nome' => 'Banner Meio Conteúdo - Notícias',
                'posicao' => 'banner-meio-conteudo',
                'pagina' => 'noticias',
                'tipo' => 'html',
                'html_content' => '<div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; text-align: center; color: white; border-radius: 8px;">
                    <h3 style="margin: 0; font-size: 1.4em;">Banner Meio do Conteúdo</h3>
                    <p style="margin: 10px 0 0 0; opacity: 0.9;">728x90 - Integrado ao conteúdo</p>
                </div>',
                'dimensoes' => '728x90',
                'largura' => 728,
                'altura' => 90,
                'ativo' => true,
                'ordem' => 1,
            ],
            [
                'nome' => 'Banner Rodapé - Todas',
                'posicao' => 'banner-rodape',
                'pagina' => 'todas',
                'tipo' => 'html',
                'html_content' => '<div style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); padding: 40px; text-align: center; color: #333; border-radius: 8px;">
                    <h2 style="margin: 0; font-size: 1.8em;">Banner Rodapé</h2>
                    <p style="margin: 10px 0 0 0; opacity: 0.8;">970x250 - Posicionado no rodapé</p>
                </div>',
                'dimensoes' => '970x250',
                'largura' => 970,
                'altura' => 250,
                'ativo' => true,
                'ordem' => 1,
            ],
            [
                'nome' => 'Widget Sidebar - Todas',
                'posicao' => 'sidebar-widget',
                'pagina' => 'todas',
                'tipo' => 'html',
                'html_content' => '<div style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); padding: 25px; text-align: center; color: #333; border-radius: 8px; border: 2px solid #f4a261;">
                    <h4 style="margin: 0 0 10px 0; font-size: 1.1em; color: #e76f51;">Widget Personalizado</h4>
                    <p style="margin: 0; font-size: 0.9em; line-height: 1.4;">Espaço flexível para conteúdo promocional na sidebar</p>
                </div>',
                'ativo' => true,
                'ordem' => 1,
            ],
        ];

        foreach ($anuncios as $anuncio) {
            Anuncio::create($anuncio);
        }
    }
}
