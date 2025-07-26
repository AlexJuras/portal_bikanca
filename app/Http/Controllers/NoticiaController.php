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

        return Inertia::render('Noticias/Index', [
            'noticias' => $noticias
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
}
