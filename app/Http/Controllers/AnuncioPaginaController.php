<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\AnuncioPagina;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnuncioPaginaController extends Controller
{
    /**
     * Mostrar configuração de anúncios por página
     */
    public function index()
    {
        $paginas = AnuncioPagina::getPaginasDisponiveis();
        $configuracoes = [];
        
        foreach ($paginas as $paginaKey => $paginaNome) {
            $anunciosAtribuidos = AnuncioPagina::with('anuncio')
                ->where('pagina', $paginaKey)
                ->orderBy('ordem')
                ->get();
            
            $configuracoes[$paginaKey] = [
                'nome' => $paginaNome,
                'anuncios' => $anunciosAtribuidos,
                'slots_disponiveis' => AnuncioPagina::getMaxAnunciosPorPagina() - $anunciosAtribuidos->count()
            ];
        }
        
        return Inertia::render('Admin/AnunciosPagina/Index', [
            'configuracoes' => $configuracoes,
            'anunciosDisponiveis' => Anuncio::where('ativo_global', true)->get(['id', 'nome', 'tipo']),
            'maxAnunciosPorPagina' => AnuncioPagina::getMaxAnunciosPorPagina()
        ]);
    }

    /**
     * Atribuir anúncio a uma página
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anuncio_id' => 'required|exists:anuncios,id',
            'pagina' => 'required|string|in:home,noticias,categoria,videos',
            'ordem' => 'required|integer|min:1|max:3'
        ]);

        // Verificar se a posição já está ocupada
        $existeNaPosicao = AnuncioPagina::where('pagina', $validated['pagina'])
            ->where('ordem', $validated['ordem'])
            ->exists();

        if ($existeNaPosicao) {
            return back()->withErrors(['ordem' => 'Esta posição já está ocupada nesta página.']);
        }

        // Verificar se o anúncio já está atribuído a esta página
        $jaAtribuido = AnuncioPagina::where('anuncio_id', $validated['anuncio_id'])
            ->where('pagina', $validated['pagina'])
            ->exists();

        if ($jaAtribuido) {
            return back()->withErrors(['anuncio_id' => 'Este anúncio já está atribuído a esta página.']);
        }

        AnuncioPagina::create($validated);

        return redirect()->route('admin.anuncios-pagina.index')
            ->with('success', 'Anúncio atribuído com sucesso!');
    }

    /**
     * Remover anúncio de uma página
     */
    public function destroy(AnuncioPagina $anuncioPagina)
    {
        $anuncioPagina->delete();

        return redirect()->route('admin.anuncios-pagina.index')
            ->with('success', 'Anúncio removido da página!');
    }

    /**
     * Toggle status ativo
     */
    public function toggleAtivo(AnuncioPagina $anuncioPagina)
    {
        $anuncioPagina->update(['ativo' => !$anuncioPagina->ativo]);

        return redirect()->back()
            ->with('success', 'Status atualizado!');
    }

    /**
     * Reordenar anúncios em uma página
     */
    public function reordenar(Request $request)
    {
        $validated = $request->validate([
            'pagina' => 'required|string',
            'anuncios' => 'required|array',
            'anuncios.*.id' => 'required|exists:anuncio_pagina,id',
            'anuncios.*.ordem' => 'required|integer|min:1|max:3'
        ]);

        foreach ($validated['anuncios'] as $anuncio) {
            AnuncioPagina::where('id', $anuncio['id'])
                ->update(['ordem' => $anuncio['ordem']]);
        }

        return redirect()->back()
            ->with('success', 'Ordem atualizada!');
    }
}
