<?php

namespace App\Http\Controllers;

use App\Models\Midia;
use App\Models\Noticia;
use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Tag;
use App\Models\Anuncio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class NoticiaController extends Controller
{
    public function index()
    {
        $noticias = Noticia::with('autor', 'categoria', 'tags', 'imagem_capa')
            ->publicadas()
            ->latest('publicada_em')
            ->paginate(10);

        // Buscar anúncios ativos para a página de notícias
        $anunciosNoticias = Anuncio::parasPagina('noticias', 3);

        return Inertia::render('Noticias/Index', [
            'noticias' => $noticias,
            'anuncios' => $anunciosNoticias,
        ]);
    }

    public function admin(Request $request)
    {
        $query = Noticia::with(['autor', 'categoria', 'imagem_capa'])
            ->latest('updated_at');

        // Aplicar filtro de pesquisa se fornecido
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('titulo', 'like', "%{$search}%");
        }

        // Aplicar filtro de status se fornecido
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $noticias = $query->paginate(15);

        return Inertia::render('Admin/Noticias/Index', [
            'noticias' => $noticias,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function checkSlug($slug)
    {
        $exists = Noticia::where('slug', $slug)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function create()
    {
        return Inertia::render('Admin/Noticias/Create', [
            'categorias' => Categoria::all(),
            'autores' => Autor::all(),
            'tags' => Tag::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:200',
            'slug' => 'required|unique:noticias,slug',
            'resumo' => 'required|max:300',
            'conteudo' => 'required',
            'status' => 'required|in:rascunho,publicada,arquivada,agendada',
            'categoria_id' => 'required|exists:categorias,id',
            'autor_id' => 'required|exists:autors,id',
            'imagem_capa' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'tag_ids' => 'nullable|array'
        ], [
            'titulo.required' => 'O título é obrigatório',
            'titulo.max' => 'O título não pode ter mais que 200 caracteres',
            'slug.required' => 'O slug é obrigatório',
            'slug.unique' => 'Este slug já está sendo usado por outra notícia',
            'resumo.required' => 'O resumo é obrigatório',
            'resumo.max' => 'O resumo não pode ter mais que 300 caracteres',
            'conteudo.required' => 'O conteúdo da notícia é obrigatório',
            'status.required' => 'O status é obrigatório',
            'status.in' => 'Status inválido',
            'categoria_id.required' => 'A categoria é obrigatória',
            'categoria_id.exists' => 'A categoria selecionada não existe',
            'autor_id.required' => 'O autor é obrigatório',
            'autor_id.exists' => 'O autor selecionado não existe',
            'imagem_capa.file' => 'A imagem deve ser um arquivo válido',
            'imagem_capa.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou gif',
            'imagem_capa.max' => 'A imagem não pode ser maior que 2MB',
            'tag_ids.array' => 'As tags devem ser um array válido'
        ]);

        try {
            if ($request->hasFile('imagem_capa')) {
                $arquivo = $request->file('imagem_capa');
                $nomeArquivo = $arquivo->store('midias', 'public');

                $midia = Midia::create([
                    'caminho' => asset('storage/' . $nomeArquivo),
                    'formato' => 'capa',
                    'legenda' => $validated['titulo'],
                ]);
                $validated['imagem_capa'] = $midia->id;
            }

            $noticia = Noticia::create($validated);

            if (!empty($validated['tag_ids'])) {
                $noticia->tags()->sync($validated['tag_ids']);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erro ao salvar a notícia: ' . $e->getMessage()])
                ->withInput();
        }

        return redirect()->route('admin.noticias.index')
            ->with('success', 'Notícia criada com sucesso!');
    }

    public function edit(Noticia $noticia)
    {
        return Inertia::render('Admin/Noticias/Edit', [
            'noticia' => $noticia->load('tags', 'categoria', 'autor', 'imagem_capa'),
            'categorias' => Categoria::all(),
            'autores' => Autor::all(),
            'tags' => Tag::all()
        ]);
    }

    public function update(Request $request, Noticia $noticia)
    {
        // Log para debug
        \Log::info('Update request received', [
            'method' => $request->method(),
            'data' => $request->all(),
            'noticia_id' => $noticia->id
        ]);
        
        // Se for apenas atualização de status (requisição PATCH)
        if ($request->isMethod('patch') && $request->has('status') && count($request->all()) == 1) {
            $request->validate([
                'status' => 'required|in:rascunho,publicada,arquivada,agendada',
            ]);

            $noticia->update([
                'status' => $request->status,
            ]);

            // Retornar resposta Inertia com dados atualizados
            return redirect()->back()->with('success', 'Status atualizado com sucesso!');
        }

        // Se for apenas atualização de destaque da homepage (requisição PATCH)
        if ($request->isMethod('patch') && $request->has('destaque_home') && count($request->all()) == 1) {
            $request->validate([
                'destaque_home' => 'required|boolean',
            ]);

            $noticia->update([
                'destaque_home' => $request->destaque_home,
            ]);

            return redirect()->back()->with('success', 'Destaque da homepage atualizado com sucesso!');
        }

        // Atualização completa da notícia
        $request->validate([
            'titulo' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:noticias,slug,' . $noticia->id,
            'resumo' => 'required|string|max:500',
            'conteudo' => 'required|string',
            'status' => 'required|in:rascunho,publicada,arquivada,agendada',
            'autor_id' => 'required|exists:autors,id',
            'categoria_id' => 'required|exists:categorias,id',
            'imagem_capa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'nullable|array',
            'layout' => 'nullable|string|max:50'
        ]);

        $slug = $request->slug ?: Str::slug($request->titulo);

        // Processar upload de imagem de capa se fornecida
        $imagemCapaId = $noticia->imagem_capa;
        if ($request->hasFile('imagem_capa')) {
            $midia = new Midia();
            $midia->tipo = 'imagem';
            $midia->formato = 'capa';
            $midia->legenda = $request->titulo;
            
            $arquivo = $request->file('imagem_capa');
            $nomeArquivo = $arquivo->store('midias', 'public');
            $midia->caminho = asset('storage/' . $nomeArquivo);
            $midia->save();
            
            $imagemCapaId = $midia->id;
        }

        $noticia->update([
            'titulo' => $request->titulo,
            'slug' => $slug,
            'resumo' => $request->resumo,
            'conteudo' => $request->conteudo,
            'status' => $request->status,
            'autor_id' => $request->autor_id,
            'categoria_id' => $request->categoria_id,
            'imagem_capa' => $imagemCapaId,
            'layout' => $request->layout,
        ]);

        // Processar tags
        if ($request->has('tags') && is_array($request->tags)) {
            $tagIds = [];
            foreach ($request->tags as $tagNome) {
                $tag = Tag::firstOrCreate(['nome' => $tagNome]);
                $tagIds[] = $tag->id;
            }
            $noticia->tags()->sync($tagIds);
        }

        return redirect()->route('admin.noticias.index')->with('success', 'Notícia atualizada com sucesso!');
    }

    public function destroy(Noticia $noticia)
    {
        $noticia->delete();

        return redirect()->route('admin.noticias.index')->with('success', 'Notícia removida com sucesso!');
    }

    public function categoria($categoria)
    {
        // Buscar categoria por slug ou ID
        $categoriaObj = Categoria::where('slug', $categoria)
            ->orWhere('id', $categoria)
            ->firstOrFail();

        $noticias = Noticia::with('autor', 'categoria', 'tags', 'imagem_capa')
            ->publicadas()
            ->where('categoria_id', $categoriaObj->id)
            ->latest('publicada_em')
            ->paginate(10);

        // Buscar anúncios ativos para a página de categoria
        $anunciosCategoria = Anuncio::parasPagina('categoria', 3);

        return Inertia::render('Noticias/Index', [
            'noticias' => $noticias,
            'categoria' => $categoriaObj,
            'anuncios' => $anunciosCategoria,
        ]);
    }

    public function show(Noticia $noticia)
    {
        // Verificar se a notícia está publicada
        if (!$noticia->isPublicada()) {
            abort(404, 'Notícia não encontrada ou não publicada.');
        }

        // Incrementar visualizações (apenas uma vez por sessão por notícia)
        $sessionKey = 'noticia_viewed_' . $noticia->id;
        if (!session()->has($sessionKey)) {
            $noticia->incrementarVisualizacoes();
            session()->put($sessionKey, true);
        }

        $noticiaComRelacionamentos = $noticia->load(['autor', 'categoria', 'tags', 'imagem_capa']);

        $noticiasRelacionadas = Noticia::with(['autor', 'categoria', 'imagem_capa'])
            ->publicadas()
            ->where('categoria_id', $noticia->categoria_id)
            ->where('id', '!=', $noticia->id)
            ->take(3)
            ->get();

        return Inertia::render('Noticias/Show', [
            'noticia' => $noticiaComRelacionamentos,
            'noticiasRelacionadas' => $noticiasRelacionadas
        ]);
    }

    /**
     * Incrementar contador de cliques
     */
    public function incrementarClique(Noticia $noticia)
    {
        // Controle anti-spam: máximo 1 clique por IP por notícia a cada 5 minutos
        $cacheKey = 'noticia_clicked_' . $noticia->id . '_' . request()->ip();
        
        if (!\Cache::has($cacheKey)) {
            $noticia->incrementarCliques();
            \Cache::put($cacheKey, true, now()->addMinutes(5));
            
            return response()->json(['success' => true, 'action' => 'incremented']);
        } else {
            return response()->json(['success' => true, 'action' => 'already_counted']);
        }
    }
}
