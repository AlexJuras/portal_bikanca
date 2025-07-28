<?php

namespace App\Http\Controllers;

use App\Models\Midia;
use App\Models\Categoria;
use App\Models\Autor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Midia::videos()
            ->publicadas()
            ->with(['categoria', 'autor'])
            ->orderBy('publicada_em', 'desc')
            ->paginate(12);

        $maisAssistidos = Midia::videos()
            ->publicadas()
            ->orderBy('visualizacoes', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Videos/Index', [
            'videos' => $videos,
            'maisAssistidos' => $maisAssistidos,
        ]);
    }

    public function show(Midia $midia)
    {
        // Verificar se é um vídeo
        if (!$midia->isVideo()) {
            abort(404);
        }

        // Incrementar visualizações
        $midia->increment('visualizacoes');

        // Carregar relacionamentos
        $midia->load(['categoria', 'autor']);

        // Buscar vídeos relacionados
        $videosRelacionados = Midia::videos()
            ->publicadas()
            ->where('id', '!=', $midia->id)
            ->where('categoria_id', $midia->categoria_id)
            ->with(['categoria', 'autor'])
            ->limit(6)
            ->get();

        return Inertia::render('Videos/Show', [
            'video' => $midia,
            'videosRelacionados' => $videosRelacionados,
        ]);
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();
        $autores = Autor::orderBy('nome')->get();

        return Inertia::render('Videos/Create', [
            'categorias' => $categorias,
            'autores' => $autores,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'url_externa' => 'required|url',
            'categoria_id' => 'required|exists:categorias,id',
            'autor_id' => 'required|exists:autors,id',
            'duracao' => 'nullable|string',
            'status' => 'required|in:rascunho,publicada',
        ]);

        // Definir tipo como vídeo
        $validated['tipo'] = 'video';
        $validated['visualizacoes'] = 0;

        // Definir data de publicação se status for publicada
        if ($validated['status'] === 'publicada') {
            $validated['publicada_em'] = now();
        }

        Midia::create($validated);

        return redirect()->route('videos.index')
            ->with('success', 'Vídeo criado com sucesso!');
    }

    public function edit(Midia $midia)
    {
        // Verificar se é um vídeo
        if (!$midia->isVideo()) {
            abort(404);
        }

        $categorias = Categoria::orderBy('nome')->get();
        $autores = Autor::orderBy('nome')->get();

        return Inertia::render('Videos/Edit', [
            'video' => $midia,
            'categorias' => $categorias,
            'autores' => $autores,
        ]);
    }

    public function update(Request $request, Midia $midia)
    {
        // Verificar se é um vídeo
        if (!$midia->isVideo()) {
            abort(404);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'url_externa' => 'required|url',
            'categoria_id' => 'required|exists:categorias,id',
            'autor_id' => 'required|exists:autors,id',
            'duracao' => 'nullable|string',
            'status' => 'required|in:rascunho,publicada',
        ]);

        // Definir data de publicação se status mudou para publicada
        if ($validated['status'] === 'publicada' && $midia->status !== 'publicada') {
            $validated['publicada_em'] = now();
        }

        $midia->update($validated);

        return redirect()->route('videos.show', $midia)
            ->with('success', 'Vídeo atualizado com sucesso!');
    }

    public function destroy(Midia $midia)
    {
        // Verificar se é um vídeo
        if (!$midia->isVideo()) {
            abort(404);
        }

        $midia->delete();

        return redirect()->route('videos.index')
            ->with('success', 'Vídeo excluído com sucesso!');
    }

    public function categoria($categoriaId)
    {
        $categoria = Categoria::findOrFail($categoriaId);
        
        $videos = Midia::videos()
            ->publicadas()
            ->where('categoria_id', $categoriaId)
            ->with(['categoria', 'autor'])
            ->orderBy('publicada_em', 'desc')
            ->paginate(12);

        return Inertia::render('Videos/Index', [
            'videos' => $videos,
            'categoria' => $categoria,
        ]);
    }
}
