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

    public function admin(Request $request)
    {
        $query = Midia::videos()
            ->with(['categoria', 'autor'])
            ->latest('updated_at');

        // Aplicar filtro de pesquisa se fornecido
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('titulo', 'like', "%{$search}%");
        }

        $videos = $query->paginate(15);

        return Inertia::render('Admin/Videos/Index', [
            'videos' => $videos,
            'filters' => $request->only(['search'])
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

        return Inertia::render('Admin/Videos/Create', [
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
            'thumbnail' => 'nullable|url',
        ]);

        // Definir tipo como vídeo
        $validated['tipo'] = 'video';
        $validated['visualizacoes'] = 0;

        // Definir data de publicação se status for publicada
        if ($validated['status'] === 'publicada') {
            $validated['publicada_em'] = now();
        }

        // Se não foi fornecida uma thumbnail personalizada, usar a padrão do YouTube
        if (empty($validated['thumbnail'])) {
            $youtubeId = $this->extractYoutubeId($validated['url_externa']);
            if ($youtubeId) {
                $validated['thumbnail'] = "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg";
            }
        }

        Midia::create($validated);

        return redirect()->route('admin.videos.index')
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

        return Inertia::render('Admin/Videos/Edit', [
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
            'thumbnail' => 'nullable|url',
        ]);

        // Definir data de publicação se status mudou para publicada
        if ($validated['status'] === 'publicada' && $midia->status !== 'publicada') {
            $validated['publicada_em'] = now();
        }

        // Se a URL do vídeo mudou e não há thumbnail personalizada, usar a padrão do YouTube
        if ($validated['url_externa'] !== $midia->url_externa && empty($validated['thumbnail'])) {
            $youtubeId = $this->extractYoutubeId($validated['url_externa']);
            if ($youtubeId) {
                $validated['thumbnail'] = "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg";
            }
        }

        $midia->update($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Vídeo atualizado com sucesso!');
    }

    public function destroy(Midia $midia)
    {
        // Verificar se é um vídeo
        if (!$midia->isVideo()) {
            abort(404);
        }

        $midia->delete();

        return redirect()->route('admin.videos.index')
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

    /**
     * Extrai o ID do vídeo YouTube da URL
     */
    private function extractYoutubeId($url)
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }
}
