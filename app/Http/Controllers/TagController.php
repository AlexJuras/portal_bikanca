<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{
    public function admin(Request $request)
    {
        $query = Tag::query()->withCount('noticias');

        // Se há um termo de busca, filtrar por nome
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nome', 'like', "%{$search}%");
        }

        $tags = $query->latest()->paginate(10);

        return Inertia::render('Admin/Tags/Index', [
            'tags' => $tags,
            'search' => $request->search
        ]);
    }

    public function index()
    {
        $tags = Tag::latest()->paginate(10);

        return Inertia::render('Tags/Index', [
            'tags' => $tags
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Tags/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100|unique:tags,nome',
            'slug' => 'required|string|max:120|unique:tags,slug',
        ]);

        Tag::create($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag criada com sucesso!');
    }

    public function show(Tag $tag)
    {
        // Carregar as notícias relacionadas a esta tag
        $tag->load(['noticias' => function ($query) {
            $query->with(['categoria', 'autor', 'midias'])
                  ->where('status', 'publicado')
                  ->latest()
                  ->take(10);
        }]);

        return Inertia::render('Admin/Tags/Show', [
            'tag' => $tag
        ]);
    }

    public function edit(Tag $tag)
    {
        return Inertia::render('Admin/Tags/Edit', [
            'tag' => $tag
        ]);
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100|unique:tags,nome,' . $tag->id,
            'slug' => 'required|string|max:120|unique:tags,slug,' . $tag->id,
        ]);

        $tag->update($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag atualizada com sucesso!');
    }

    public function destroy(Tag $tag)
    {
        // Verificar se a tag tem notícias associadas
        if ($tag->noticias()->count() > 0) {
            return redirect()->route('admin.tags.index')
                ->with('error', 'Não é possível excluir esta tag pois ela possui notícias associadas.');
        }

        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag excluída com sucesso!');
    }
}
