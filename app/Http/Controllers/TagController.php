<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest()->paginate(10);

        return Inertia::render('Tags/Index', [
            'tags' => $tags
        ]);
    }

    public function create()
    {
        return Inertia::render('Tags/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug',
            'descricao' => 'nullable|string'
        ]);

        Tag::create($request->all());

        return redirect()->route('admin.tags.index')->with('success', 'Tag criada com sucesso!');
    }

    public function edit(Tag $tag)
    {
        return Inertia::render('Tags/Edit', [
            'tag' => $tag
        ]);
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,' . $tag->id,
            'descricao' => 'nullable|string'
        ]);

        $tag->update($request->all());

        return redirect()->route('admin.tags.index')->with('success', 'Tag atualizada com sucesso!');
    }

    public function show(Tag $tag)
    {
        // Carregar notícias com esta tag
        $noticias = $tag->noticias()
            ->with(['autor', 'categoria', 'imagemCapa'])
            ->where('status', 'publicada')
            ->latest('publicada_em')
            ->paginate(10);

        // Estatísticas da tag
        $estatisticas = [
            'total_noticias' => $tag->noticias()->count(),
            'noticias_publicadas' => $tag->noticias()->where('status', 'publicada')->count(),
            'noticias_recentes' => $tag->noticias()->where('status', 'publicada')->where('publicada_em', '>=', now()->subDays(30))->count(),
            'mais_visualizada' => $tag->noticias()->where('status', 'publicada')->orderBy('visualizacoes', 'desc')->first(),
        ];

        return Inertia::render('Tags/Show', [
            'tag' => $tag,
            'noticias' => $noticias,
            'estatisticas' => $estatisticas
        ]);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag removida com sucesso!');
    }
}
