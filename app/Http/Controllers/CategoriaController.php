<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::latest()->paginate(10);

        return Inertia::render('Categorias/Index', [
            'categorias' => $categorias
        ]);
    }

    public function admin(Request $request)
    {
        $query = Categoria::withCount('noticias')
            ->latest('updated_at');

        // Aplicar filtro de pesquisa se fornecido
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nome', 'like', "%{$search}%");
        }

        $categorias = $query->paginate(15);

        return Inertia::render('Admin/Categorias/Index', [
            'categorias' => $categorias,
            'filters' => $request->only(['search'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Categorias/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categorias,slug',
            'descricao' => 'nullable|string'
        ]);

        Categoria::create($request->all());

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Categoria $categoria)
    {
        return Inertia::render('Admin/Categorias/Edit', [
            'categoria' => $categoria
        ]);
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categorias,slug,' . $categoria->id,
            'descricao' => 'nullable|string'
        ]);

        $categoria->update($request->all());

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria removida com sucesso!');
    }
}
