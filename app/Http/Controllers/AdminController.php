<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Noticia;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Exibe o dashboard administrativo
     */
    public function dashboard()
    {
        $hoje = Carbon::today();
        $inicioSemana = Carbon::now()->startOfWeek();
        $inicioMes = Carbon::now()->startOfMonth();

        // Estatísticas básicas
        $estatisticas = [
            'total_noticias' => Noticia::count(),
            'noticias_publicadas' => Noticia::where('status', 'publicada')->count(),
            'noticias_rascunho' => Noticia::where('status', 'rascunho')->count(),
            'total_categorias' => Categoria::count(),
            'total_tags' => Tag::count(),
            'total_autores' => Autor::count(),
            'noticias_hoje' => Noticia::whereDate('created_at', $hoje)->count(),
            'noticias_semana' => Noticia::where('created_at', '>=', $inicioSemana)->count(),
            'noticias_mes' => Noticia::where('created_at', '>=', $inicioMes)->count(),
        ];

        // Últimas notícias com relacionamentos
        $ultimasNoticias = Noticia::with(['autor', 'categoria', 'imagem_capa'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Categorias mais usadas com contagem de notícias
        $categoriasMaisUsadas = Categoria::withCount('noticias')
            ->orderBy('noticias_count', 'desc')
            ->take(8)
            ->get();

        // Tags mais usadas com contagem de notícias
        $tagsMaisUsadas = Tag::withCount('noticias')
            ->orderBy('noticias_count', 'desc')
            ->take(12)
            ->get();

        // Autores mais ativos
        $autoresMaisAtivos = Autor::withCount('noticias')
            ->orderBy('noticias_count', 'desc')
            ->take(5)
            ->get();

        // Dados de notícias por mês (últimos 6 meses)
        $noticiasPorMes = collect();
        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $count = Noticia::whereYear('created_at', $mes->year)
                           ->whereMonth('created_at', $mes->month)
                           ->count();
            
            $noticiasPorMes->push([
                'mes' => $mes->format('M/Y'),
                'total' => $count
            ]);
        }

        return Inertia::render('Admin/Dashboard', [
            'estatisticas' => $estatisticas,
            'ultimasNoticias' => $ultimasNoticias,
            'categoriasMaisUsadas' => $categoriasMaisUsadas,
            'tagsMaisUsadas' => $tagsMaisUsadas,
            'autoresMaisAtivos' => $autoresMaisAtivos,
            'noticiasPorMes' => $noticiasPorMes,
        ]);
    }

    /**
     * Exibe relatórios e estatísticas detalhadas
     */
    public function relatorios()
    {
        // Implementar relatórios detalhados se necessário
        return Inertia::render('Admin/Relatorios');
    }

    /**
     * Configurações do sistema
     */
    public function configuracoes()
    {
        // Implementar configurações do sistema se necessário
        return Inertia::render('Admin/Configuracoes');
    }
}
