<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Buscar notícias em destaque (marcadas para aparecer na home)
        $noticiasDestaque = Noticia::with(['categoria', 'autor'])
            ->where('status', 'publicada')
            ->where('destaque_home', true)
            ->orderBy('publicada_em', 'desc')
            ->take(6)
            ->get();

        // Buscar notícias mais recentes para o carrossel (primeiras 4 em destaque)
        $noticiasCarrossel = $noticiasDestaque->take(4);

        // Buscar notícias recentes por categoria para as seções laterais
        $noticiasPolitica = Noticia::with(['categoria', 'autor'])
            ->whereHas('categoria', function ($query) {
                $query->where('nome', 'like', '%Política%');
            })
            ->where('status', 'publicada')
            ->orderBy('publicada_em', 'desc')
            ->take(4)
            ->get();

        $noticiasEconomia = Noticia::with(['categoria', 'autor'])
            ->whereHas('categoria', function ($query) {
                $query->where('nome', 'like', '%Economia%');
            })
            ->where('status', 'publicada')
            ->orderBy('publicada_em', 'desc')
            ->take(4)
            ->get();

        $noticiasEsporte = Noticia::with(['categoria', 'autor'])
            ->whereHas('categoria', function ($query) {
                $query->where('nome', 'like', '%Esporte%');
            })
            ->where('status', 'publicada')
            ->orderBy('publicada_em', 'desc')
            ->take(4)
            ->get();

        // Buscar notícias mais lidas (baseado em visualizações)
        $noticiasMaisLidas = Noticia::with(['categoria', 'autor'])
            ->where('status', 'publicada')
            ->orderBy('visualizacoes', 'desc')
            ->take(5)
            ->get();

        // Buscar todas as categorias para exibição
        $categorias = Categoria::orderBy('nome')->get();

        return Inertia::render('Inicio', [
            'noticiasDestaque' => $noticiasDestaque,
            'noticiasCarrossel' => $noticiasCarrossel,
            'noticiasPolitica' => $noticiasPolitica,
            'noticiasEconomia' => $noticiasEconomia,
            'noticiasEsporte' => $noticiasEsporte,
            'noticiasMaisLidas' => $noticiasMaisLidas,
            'categorias' => $categorias,
        ]);
    }
}
