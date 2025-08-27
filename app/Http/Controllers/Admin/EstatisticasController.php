<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EstatisticasController extends Controller
{
    public function noticias(Request $request)
    {
        $query = Noticia::with(['autor', 'categoria', 'imagem_capa'])
            ->select('id', 'titulo', 'slug', 'visualizacoes', 'cliques', 'publicada_em', 'autor_id', 'categoria_id', 'imagem_capa', 'status');

        // Filtro por período
        if ($request->filled('periodo')) {
            $periodo = $request->periodo;
            switch ($periodo) {
                case '7d':
                    $query->where('publicada_em', '>=', now()->subDays(7));
                    break;
                case '30d':
                    $query->where('publicada_em', '>=', now()->subDays(30));
                    break;
                case '90d':
                    $query->where('publicada_em', '>=', now()->subDays(90));
                    break;
                case '1y':
                    $query->where('publicada_em', '>=', now()->subYear());
                    break;
            }
        }

        // Ordenação
        $ordenacao = $request->get('ordem', 'visualizacoes');
        if (in_array($ordenacao, ['visualizacoes', 'cliques', 'publicada_em'])) {
            $query->orderBy($ordenacao, 'desc');
        }

        $noticias = $query->paginate(20);

        // Estatísticas gerais
        $estatisticas = [
            'total_visualizacoes' => Noticia::sum('visualizacoes'),
            'total_cliques' => Noticia::sum('cliques'),
            'total_noticias' => Noticia::count(),
            'media_visualizacoes' => Noticia::avg('visualizacoes'),
            'media_cliques' => Noticia::avg('cliques'),
        ];

        // Top 10 notícias mais visualizadas
        $topVisualizacoes = Noticia::with(['autor', 'categoria'])
            ->orderBy('visualizacoes', 'desc')
            ->take(10)
            ->get();

        // Top 10 notícias mais clicadas
        $topCliques = Noticia::with(['autor', 'categoria'])
            ->orderBy('cliques', 'desc')
            ->take(10)
            ->get();

        return Inertia::render('Admin/Estatisticas/Noticias', [
            'noticias' => $noticias,
            'estatisticas' => $estatisticas,
            'topVisualizacoes' => $topVisualizacoes,
            'topCliques' => $topCliques,
            'filters' => $request->only(['periodo', 'ordem'])
        ]);
    }
}
