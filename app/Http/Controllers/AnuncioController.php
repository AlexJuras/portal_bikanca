<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AnuncioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anuncios = Anuncio::orderBy('ordem', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Anuncios/Index', [
            'anuncios' => $anuncios,
            'posicoes' => Anuncio::getPosicoes(),
            'paginas' => Anuncio::getPaginas(),
            'tipos' => Anuncio::getTipos(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Anuncios/Create', [
            'posicoes' => Anuncio::getPosicoes(),
            'paginas' => Anuncio::getPaginas(),
            'tipos' => Anuncio::getTipos(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'posicao' => 'required|string',
            'pagina' => 'required|string',
            'tipo' => 'required|in:imagem,html,script',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'html_content' => 'nullable|string',
            'script_content' => 'nullable|string',
            'link' => 'nullable|url',
            'nova_aba' => 'boolean',
            'dimensoes' => 'nullable|string',
            'largura' => 'nullable|integer|min:1|max:2000',
            'altura' => 'nullable|integer|min:1|max:2000',
            'ativo' => 'boolean',
            'ordem' => 'nullable|integer|min:0',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'observacoes' => 'nullable|string',
        ]);

        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('anuncios', 'public');
        }

        // Se não foi definida uma ordem, usar a próxima disponível
        if (!isset($validated['ordem'])) {
            $validated['ordem'] = Anuncio::where('posicao', $validated['posicao'])
                ->where('pagina', $validated['pagina'])
                ->max('ordem') + 1;
        }

        $anuncio = Anuncio::create($validated);

        return redirect()->route('admin.anuncios.index')
            ->with('success', 'Anúncio criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Anuncio $anuncio)
    {
        return Inertia::render('Admin/Anuncios/Show', [
            'anuncio' => $anuncio,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anuncio $anuncio)
    {
        return Inertia::render('Admin/Anuncios/Edit', [
            'anuncio' => $anuncio,
            'posicoes' => Anuncio::getPosicoes(),
            'paginas' => Anuncio::getPaginas(),
            'tipos' => Anuncio::getTipos(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anuncio $anuncio)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'posicao' => 'required|string',
            'pagina' => 'required|string',
            'tipo' => 'required|in:imagem,html,script',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'html_content' => 'nullable|string',
            'script_content' => 'nullable|string',
            'link' => 'nullable|url',
            'nova_aba' => 'boolean',
            'dimensoes' => 'nullable|string',
            'largura' => 'nullable|integer|min:1|max:2000',
            'altura' => 'nullable|integer|min:1|max:2000',
            'ativo' => 'boolean',
            'ordem' => 'nullable|integer|min:0',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'observacoes' => 'nullable|string',
        ]);

        if ($request->hasFile('imagem')) {
            // Deletar imagem antiga se existir
            if ($anuncio->imagem && Storage::disk('public')->exists($anuncio->imagem)) {
                Storage::disk('public')->delete($anuncio->imagem);
            }
            $validated['imagem'] = $request->file('imagem')->store('anuncios', 'public');
        }

        $anuncio->update($validated);

        return redirect()->route('admin.anuncios.index')
            ->with('success', 'Anúncio atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anuncio $anuncio)
    {
        // Deletar imagem se existir
        if ($anuncio->imagem && Storage::disk('public')->exists($anuncio->imagem)) {
            Storage::disk('public')->delete($anuncio->imagem);
        }

        $anuncio->delete();

        return redirect()->route('admin.anuncios.index')
            ->with('success', 'Anúncio excluído com sucesso!');
    }

    /**
     * Toggle ativo status
     */
    public function toggleAtivo(Anuncio $anuncio)
    {
        $anuncio->update(['ativo' => !$anuncio->ativo]);

        return redirect()->back()
            ->with('success', 'Status do anúncio atualizado!');
    }

    /**
     * Increment impressão
     */
    public function incrementarImpressao(Anuncio $anuncio)
    {
        $anuncio->incrementarImpressao();
        
        return response()->json(['success' => true]);
    }

    /**
     * Increment clique
     */
    public function incrementarClique(Anuncio $anuncio)
    {
        $anuncio->incrementarClique();
        
        return response()->json(['success' => true]);
    }

    /**
     * Get anúncios for public display
     */
    public function getAnunciosPublicos($posicao, $pagina)
    {
        $anuncios = Anuncio::ativo()
            ->valido()
            ->porPosicao($posicao)
            ->where(function($query) use ($pagina) {
                $query->porPagina($pagina)
                      ->orWhere('pagina', 'todas');
            })
            ->ordenado()
            ->get();

        return response()->json($anuncios);
    }
}
