<?php

namespace App\Http\Controllers;

use App\Models\Midia;
use App\Models\Noticia;
use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class NoticiaController extends Controller
{
    public function index()
    {
        $noticias = Noticia::with('autor', 'categoria', 'tags', 'imagemCapa')
            ->publicadas()
            ->latest('publicada_em')
            ->paginate(10);
        
        // Forçar serialização do relacionamento imagemCapa
        $noticias->getCollection()->transform(function($noticia) {
            $array = $noticia->toArray();
            if ($noticia->imagemCapa) {
                $array['imagemCapa'] = $noticia->imagemCapa->toArray();
            }
            return $array;
        });

        $maisLidas = Noticia::with('autor', 'categoria', 'imagemCapa')
            ->publicadas()
            ->orderBy('visualizacoes', 'desc')
            ->limit(5)
            ->get()
            ->map(function($noticia) {
                $array = $noticia->toArray();
                if ($noticia->imagemCapa) {
                    $array['imagemCapa'] = $noticia->imagemCapa->toArray();
                }
                return $array;
            });

        return Inertia::render('Noticias/Index', [
            'noticias' => $noticias,
            'maisLidas' => $maisLidas
        ]);
    }

    public function admin(Request $request)
    {
        $query = Noticia::with(['autor', 'categoria', 'imagemCapa'])
            ->latest('updated_at');

        // Aplicar filtro de pesquisa se fornecido
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('titulo', 'like', "%{$search}%");
        }

        $noticias = $query->paginate(15);

        return Inertia::render('Admin/Noticias/Index', [
            'noticias' => $noticias,
            'filters' => $request->only(['search'])
        ]);
    }

    public function checkSlug($slug)
    {
        $exists = Noticia::where('slug', $slug)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function create()
    {
        return Inertia::render('Noticias/Create', [
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
                $path = $request->file('imagem_capa')->store('midias', 'public');

                $midia = Midia::create([
                    'caminho' => $path,
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

        return redirect()->route('noticias.index')
            ->with('success', 'Notícia criada com sucesso!');
    }

    public function edit(Noticia $noticia)
    {
        return Inertia::render('Noticias/Edit', [
            'noticia' => $noticia->load('tags'),
            'autores' => Autor::all(),
            'tags' => Tag::all()
        ]);
    }

    public function update(Request $request, Noticia $noticia)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:noticias,slug,' . $noticia->id,
            'conteudo' => 'required|string',
            'status' => 'required|in:rascunho,publicada,arquivada,agendada',
            'autor_id' => 'required|exists:autores,id',
            'categoria_id' => 'required|exists:categorias,id',
            'tags' => 'nullable|array',
            'layout' => 'nullable|string|max:50'
        ]);

        $slug = $request->slug ?: Str::slug($request->titulo);

        $noticia->update([
            'titulo' => $request->titulo,
            'slug' => $slug,
            'conteudo' => $request->conteudo,
            'status' => $request->status,
            'autor_id' => $request->autor_id,
            'categoria_id' => $request->categoria_id,
            'layout' => $request->layout,
        ]);

        $noticia->tags()->sync($request->tags ?? []);

        return redirect()->route('noticias.index')->with('success', 'Notícia atualizada com sucesso!');
    }

    public function destroy(Noticia $noticia)
    {
        $noticia->delete();

        return redirect()->route('noticias.index')->with('success', 'Notícia removida com sucesso!');
    }

    public function categoria($categoria)
    {
        // Buscar categoria por slug ou ID
        $categoriaObj = Categoria::where('slug', $categoria)
            ->orWhere('id', $categoria)
            ->firstOrFail();

        $noticias = Noticia::with('autor', 'categoria', 'tags', 'imagemCapa')
            ->publicadas()
            ->where('categoria_id', $categoriaObj->id)
            ->latest('publicada_em')
            ->paginate(10);
        
        // Forçar serialização do relacionamento imagemCapa
        $noticias->getCollection()->transform(function($noticia) {
            $array = $noticia->toArray();
            if ($noticia->imagemCapa) {
                $array['imagemCapa'] = $noticia->imagemCapa->toArray();
            }
            return $array;
        });

        $maisLidas = Noticia::with('autor', 'categoria', 'imagemCapa')
            ->publicadas()
            ->where('categoria_id', $categoriaObj->id)
            ->orderBy('visualizacoes', 'desc')
            ->limit(5)
            ->get()
            ->map(function($noticia) {
                $array = $noticia->toArray();
                if ($noticia->imagemCapa) {
                    $array['imagemCapa'] = $noticia->imagemCapa->toArray();
                }
                return $array;
            });

        return Inertia::render('Noticias/Index', [
            'noticias' => $noticias,
            'categoria' => $categoriaObj,
            'maisLidas' => $maisLidas
        ]);
    }

    public function show(Noticia $noticia)
    {
        // Verificar se a notícia está publicada
        if (!$noticia->isPublicada()) {
            abort(404, 'Notícia não encontrada ou não publicada.');
        }

        $noticiaComRelacionamentos = $noticia->load(['autor', 'categoria', 'tags', 'imagemCapa']);
        
        // Forçar a serialização do relacionamento imagemCapa
        $noticiaArray = $noticiaComRelacionamentos->toArray();
        if ($noticiaComRelacionamentos->imagemCapa) {
            $noticiaArray['imagemCapa'] = $noticiaComRelacionamentos->imagemCapa->toArray();
        }

        return Inertia::render('Noticias/Show', [
            'noticia' => $noticiaArray,
            'noticiasRelacionadas' => Noticia::with(['autor', 'categoria', 'imagemCapa'])
                ->publicadas()
                ->where('categoria_id', $noticia->categoria_id)
                ->where('id', '!=', $noticia->id)
                ->take(3)
                ->get()
                ->map(function($n) {
                    $array = $n->toArray();
                    if ($n->imagemCapa) {
                        $array['imagemCapa'] = $n->imagemCapa->toArray();
                    }
                    return $array;
                })
        ]);
    }
}
