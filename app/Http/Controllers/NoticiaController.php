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
        $noticias = Noticia::with('autor', 'categoria', 'tags', 'imagemCapa')->latest()->paginate(10);
        
        // Forçar serialização do relacionamento imagemCapa
        $noticias->getCollection()->transform(function($noticia) {
            $array = $noticia->toArray();
            if ($noticia->imagemCapa) {
                $array['imagemCapa'] = $noticia->imagemCapa->toArray();
            }
            return $array;
        });

        $categorias = Categoria::all();
        $maisLidas = Noticia::with('autor', 'categoria', 'imagemCapa')
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
            'categorias' => $categorias,
            'maisLidas' => $maisLidas
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
            'categorias' => Categoria::all(),
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
            'categorias' => Categoria::all(),
            'tags' => Tag::all()
        ]);
    }

    public function update(Request $request, Noticia $noticia)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:noticias,slug,' . $noticia->id,
            'conteudo' => 'required|string',
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

    public function show(Noticia $noticia)
    {
        $noticiaComRelacionamentos = $noticia->load(['autor', 'categoria', 'tags', 'imagemCapa']);
        
        // Forçar a serialização do relacionamento imagemCapa
        $noticiaArray = $noticiaComRelacionamentos->toArray();
        if ($noticiaComRelacionamentos->imagemCapa) {
            $noticiaArray['imagemCapa'] = $noticiaComRelacionamentos->imagemCapa->toArray();
        }

        return Inertia::render('Noticias/Show', [
            'noticia' => $noticiaArray,
            'noticiasRelacionadas' => Noticia::with(['autor', 'categoria', 'imagemCapa'])
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
