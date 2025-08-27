<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Categoria;
use App\Models\Video;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Buscar notícias em destaque para o carrossel (marcadas para aparecer na home)
        $noticiasCarrossel = Noticia::with(['categoria', 'autor', 'imagem_capa'])
            ->where('status', 'publicada')
            ->where('destaque_home', true)
            ->orderBy('publicada_em', 'desc')
            ->take(5)
            ->get();

        // Buscar as últimas 12 notícias para a seção principal
        $ultimasNoticias = Noticia::with(['categoria', 'autor', 'imagem_capa'])
            ->where('status', 'publicada')
            ->orderBy('publicada_em', 'desc')
            ->take(12)
            ->get();

        // Buscar os 3 vídeos mais recentes registrados no portal
        $videosDestaque = Video::with(['categoria', 'autor'])
            ->videos() // Filtrar apenas registros do tipo 'video'
            ->where('status', 'publicada')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Buscar todas as categorias que têm notícias publicadas
        $categorias = Categoria::whereHas('noticias', function ($query) {
                $query->where('status', 'publicada');
            })
            ->with(['noticias' => function ($query) {
                $query->with(['autor', 'imagem_capa'])
                    ->where('status', 'publicada')
                    ->orderBy('publicada_em', 'desc')
                    ->take(5); // 5 notícias por categoria
            }])
            ->orderBy('nome')
            ->get();

        // Criar um array com notícias por categoria para facilitar o acesso no frontend
        $noticiasPorCategoria = [];
        foreach ($categorias as $categoria) {
            $noticiasPorCategoria[strtolower($categoria->nome)] = $categoria->noticias;
        }

        return Inertia::render('Inicio', [
            'ultimasNoticias' => $ultimasNoticias,
            'noticiasCarrossel' => $noticiasCarrossel,
            'videosDestaque' => $videosDestaque,
            'categorias' => $categorias,
            'noticiasPorCategoria' => $noticiasPorCategoria,
        ]);
    }
}
