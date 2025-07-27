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
        // Estatísticas básicas
        $estatisticas = [
            'total_noticias' => Noticia::count(),
            'noticias_publicadas' => Noticia::where('status', 'publicada')->count(),
            'noticias_rascunho' => Noticia::where('status', 'rascunho')->count(),
            'total_categorias' => Categoria::count(),
            'total_tags' => Tag::count(),
            'total_autores' => Autor::count(),
            'noticias_hoje' => 0,
            'noticias_semana' => 0,
            'noticias_mes' => 0,
        ];

        // Últimas notícias
        $ultimasNoticias = Noticia::orderBy('created_at', 'desc')->take(5)->get();

        // Notícias populares
        $noticiasPopulares = Noticia::where('status', 'publicada')
            ->orderBy('visualizacoes', 'desc')
            ->take(5)
            ->get();

        // Categorias
        $categoriasMaisUsadas = Categoria::take(5)->get();

        // Tags
        $tagsMaisUsadas = Tag::take(10)->get();

        // Autores
        $autoresMaisAtivos = Autor::take(5)->get();

        // Dados do gráfico
        $noticiasPorMes = collect([
            ['mes' => 'Jan/2025', 'total' => 0],
            ['mes' => 'Fev/2025', 'total' => 0],
            ['mes' => 'Mar/2025', 'total' => 0],
            ['mes' => 'Abr/2025', 'total' => 0],
            ['mes' => 'Mai/2025', 'total' => 0],
            ['mes' => 'Jun/2025', 'total' => 0],
        ]);

        return Inertia::render('Admin/Dashboard', [
            'estatisticas' => $estatisticas,
            'ultimasNoticias' => $ultimasNoticias,
            'noticiasPopulares' => $noticiasPopulares,
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
