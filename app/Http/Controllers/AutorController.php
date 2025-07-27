<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AutorController extends Controller
{
    
    public function index()
    {
        $autores = Autor::latest()->paginate(10);

        return Inertia::render('Autores/Index', [
            'autores' => $autores
        ]);
    }

    public function create()
    {
        return Inertia::render('Autores/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|unique:autores,email',
            'bio' => 'nullable|string',
            'foto_perfil' => 'nullable|string'
        ]);

        Autor::create($request->all());

        return redirect()->route('autores.index')->with('success', 'Autor criado com sucesso!');
    }

    public function edit(Autor $autor)
    {
        return Inertia::render('Autores/Edit', [
            'autor' => $autor
        ]);
    }

    public function update(Request $request, Autor $autor)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|unique:autores,email,' . $autor->id,
            'bio' => 'nullable|string',
            'foto_perfil' => 'nullable|string'
        ]);

        $autor->update($request->all());

        return redirect()->route('autores.index')->with('success', 'Autor atualizado com sucesso!');
    }

    public function show(Autor $autor)
    {
        // Carregar notícias do autor
        $noticias = $autor->noticias()
            ->with(['categoria', 'tags', 'imagemCapa'])
            ->where('status', 'publicada')
            ->latest('publicada_em')
            ->paginate(10);

        // Estatísticas do autor
        $estatisticas = [
            'total_noticias' => $autor->noticias()->count(),
            'noticias_publicadas' => $autor->noticias()->where('status', 'publicada')->count(),
            'noticias_recentes' => $autor->noticias()->where('status', 'publicada')->where('publicada_em', '>=', now()->subDays(30))->count(),
            'mais_visualizada' => $autor->noticias()->where('status', 'publicada')->orderBy('visualizacoes', 'desc')->first(),
        ];

        return Inertia::render('Autores/Show', [
            'autor' => $autor,
            'noticias' => $noticias,
            'estatisticas' => $estatisticas
        ]);
    }

    public function destroy(Autor $autor)
    {
        $autor->delete();

        return redirect()->route('autores.index')->with('success', 'Autor removido com sucesso!');
    }

}
