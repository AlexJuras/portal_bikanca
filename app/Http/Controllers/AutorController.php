<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AutorController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Autor::query();

        // Aplicar filtro de busca
        if ($request->filled('busca')) {
            $busca = $request->get('busca');
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('email', 'like', "%{$busca}%")
                  ->orWhere('bio', 'like', "%{$busca}%");
            });
        }

        // Aplicar ordenação
        $ordenacao = $request->get('ordenacao', 'nome_asc');
        switch ($ordenacao) {
            case 'nome_desc':
                $query->orderBy('nome', 'desc');
                break;
            case 'data_desc':
                $query->latest();
                break;
            case 'data_asc':
                $query->oldest();
                break;
            case 'noticias_desc':
                $query->withCount('noticias')->orderBy('noticias_count', 'desc');
                break;
            default: // nome_asc
                $query->orderBy('nome', 'asc');
                break;
        }

        $autores = $query->withCount('noticias')
                         ->with(['noticias' => function($query) {
                             $query->select('autor_id', 'visualizacoes');
                         }])
                         ->paginate(10)->withQueryString();

        // Mapear noticias_count para total_noticias e calcular total_visualizacoes
        $autores->getCollection()->transform(function ($autor) {
            $autor->total_noticias = $autor->noticias_count;
            $autor->total_visualizacoes = $autor->noticias->sum('visualizacoes');
            return $autor;
        });

        // Calcular estatísticas gerais
        $estatisticas = [
            'total_noticias' => Noticia::count(),
            'total_visualizacoes' => Noticia::sum('visualizacoes') ?? 0,
        ];

        return Inertia::render('Admin/Autores/Index', [
            'autores' => $autores,
            'estatisticas' => $estatisticas,
            'filtros' => [
                'busca' => $request->get('busca', ''),
                'ordenacao' => $ordenacao,
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Autores/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|unique:autors,email',
            'bio' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['nome', 'email', 'bio']);

        // Upload da foto se fornecida
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('autores', 'public');
            $data['foto'] = $fotoPath;
        }

        Autor::create($data);

        return redirect()->route('admin.autores.index')->with('success', 'Autor criado com sucesso!');
    }

    public function edit(Autor $autor)
    {
        return Inertia::render('Admin/Autores/Edit', [
            'autor' => $autor
        ]);
    }

    public function update(Request $request, Autor $autor)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|unique:autors,email,' . $autor->id,
            'bio' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['nome', 'email', 'bio']);

        // Upload da nova foto se fornecida
        if ($request->hasFile('foto')) {
            // Deletar foto antiga se existir
            if ($autor->foto && Storage::disk('public')->exists($autor->foto)) {
                Storage::disk('public')->delete($autor->foto);
            }
            
            $fotoPath = $request->file('foto')->store('autores', 'public');
            $data['foto'] = $fotoPath;
        }

        $autor->update($data);

        return redirect()->route('admin.autores.index')->with('success', 'Autor atualizado com sucesso!');
    }

    public function show(Autor $autor)
    {
        // Carregar notícias do autor
        $noticias = $autor->noticias()
            ->with(['categoria', 'tags', 'imagem_capa'])
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

        return Inertia::render('Admin/Autores/Show', [
            'autor' => $autor,
            'noticias' => $noticias,
            'estatisticas' => $estatisticas
        ]);
    }

    public function destroy(Autor $autor)
    {
        // Deletar foto se existir
        if ($autor->foto && Storage::disk('public')->exists($autor->foto)) {
            Storage::disk('public')->delete($autor->foto);
        }

        // Verificar se há notícias associadas (opcional - manter ou não)
        $totalNoticias = $autor->noticias()->count();
        
        // Deletar o autor
        $autor->delete();

        return redirect()->route('admin.autores.index')->with('success', 'Autor removido com sucesso!');
    }

}
