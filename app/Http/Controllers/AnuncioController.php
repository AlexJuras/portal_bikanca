<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AnuncioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anuncios = Anuncio::orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Anuncios/Index', [
            'anuncios' => $anuncios,
            'tipos' => Anuncio::getTipos(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Anuncios/CreateSimple', [
            'tipos' => Anuncio::getTipos(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log dos dados recebidos para debug
        \Log::info('Dados recebidos para criar anúncio:', $request->all());

        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'tipo' => 'nullable|in:imagem,html,script',
                'tipo_fonte' => 'nullable|in:url,upload,adsense',
                'imagem_url' => 'nullable|string',
                'imagem_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'adsense_code' => 'nullable|string',
                'link' => 'nullable|url',
                'nova_aba' => 'nullable|boolean',
                'ativo' => 'nullable|boolean',
                'ativo_global' => 'nullable|boolean',
            ]);

            // Processar diferentes tipos de conteúdo
            if ($validated['tipo_fonte'] === 'upload' && $request->hasFile('imagem_file')) {
                // Upload de arquivo
                $validated['imagem'] = $request->file('imagem_file')->store('anuncios', 'public');
                $validated['tipo'] = 'imagem';
            } elseif ($validated['tipo_fonte'] === 'url' && !empty($validated['imagem_url'])) {
                // URL da imagem
                $validated['imagem'] = $validated['imagem_url'];
                $validated['tipo'] = 'imagem';
            } elseif ($validated['tipo_fonte'] === 'adsense' && !empty($validated['adsense_code'])) {
                // Código AdSense
                $validated['script_content'] = $validated['adsense_code'];
                $validated['tipo'] = 'script';
                $validated['link'] = null; // AdSense não precisa de link
                $validated['nova_aba'] = null;
            }

            // Limpar campos não utilizados
            unset($validated['tipo_fonte'], $validated['imagem_url'], $validated['imagem_file'], $validated['adsense_code']);

            // Definir valores padrão
            $validated['tipo'] = $validated['tipo'] ?? 'imagem';
            $validated['nova_aba'] = $validated['nova_aba'] ?? true;
            $validated['ativo'] = $validated['ativo'] ?? true;
            $validated['ativo_global'] = $validated['ativo_global'] ?? true;
            $validated['impressoes'] = 0;
            $validated['cliques'] = 0;

            \Log::info('Dados validados:', $validated);

            $anuncio = Anuncio::create($validated);

            \Log::info('Anúncio criado com sucesso:', ['id' => $anuncio->id]);

            return redirect()->route('admin.anuncios.index')
                ->with('success', 'Anúncio criado com sucesso!');
                
        } catch (\Exception $e) {
            \Log::error('Erro ao criar anúncio:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['erro' => 'Erro ao criar anúncio: ' . $e->getMessage()]);
        }
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
        // Carregar as páginas associadas ao anúncio
        $anuncio->load('paginas');
        
        // Determinar o tipo_fonte baseado no tipo
        $tipo_fonte = 'url';
        if ($anuncio->tipo === 'script') {
            $tipo_fonte = 'adsense';
        } elseif ($anuncio->imagem && !filter_var($anuncio->imagem, FILTER_VALIDATE_URL)) {
            $tipo_fonte = 'upload';
        }
        
        return Inertia::render('Admin/Anuncios/EditSimple', [
            'anuncio' => array_merge($anuncio->toArray(), [
                'tipo_fonte' => $tipo_fonte,
                'imagem_url' => $anuncio->imagem && filter_var($anuncio->imagem, FILTER_VALIDATE_URL) ? $anuncio->imagem : null,
            ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anuncio $anuncio)
    {
        Log::info('Update anuncio - dados recebidos:', $request->all());
        
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:imagem,html,script',
            'tipo_fonte' => 'nullable|string',
            'imagem_url' => 'nullable|url',
            'imagem_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'adsense_code' => 'nullable|string',
            'link' => 'nullable|url',
            'nova_aba' => 'boolean',
            'ativo' => 'boolean',
            'ativo_global' => 'boolean',
        ]);

        // Processar tipo de anúncio
        if ($validated['tipo'] === 'script' && $request->filled('adsense_code')) {
            $validated['script_content'] = $request->adsense_code;
            $validated['imagem'] = null;
        } elseif ($validated['tipo'] === 'imagem') {
            // Upload de arquivo
            if ($request->hasFile('imagem_file')) {
                // Deletar imagem antiga se existir
                if ($anuncio->imagem && !filter_var($anuncio->imagem, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($anuncio->imagem)) {
                    Storage::disk('public')->delete($anuncio->imagem);
                }
                $validated['imagem'] = $request->file('imagem_file')->store('anuncios', 'public');
                Log::info('Imagem do arquivo salva em:', ['path' => $validated['imagem']]);
            }
            // URL de imagem
            elseif ($request->filled('imagem_url')) {
                // Se tinha imagem local antiga, deletar
                if ($anuncio->imagem && !filter_var($anuncio->imagem, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($anuncio->imagem)) {
                    Storage::disk('public')->delete($anuncio->imagem);
                }
                $validated['imagem'] = $request->imagem_url;
                Log::info('Imagem da URL salva:', ['url' => $validated['imagem']]);
            }
            $validated['script_content'] = null;
        }

        // Remover campos temporários da validação
        unset($validated['tipo_fonte'], $validated['imagem_url'], $validated['imagem_file'], $validated['adsense_code']);

        Log::info('Dados validados para atualizar:', $validated);

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

        // Remover todas as atribuições do anúncio às páginas
        $anuncio->paginas()->delete();

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

    /**
     * Registrar impressão de anúncio
     */
    public function registrarImpressao(Anuncio $anuncio)
    {
        $anuncio->increment('impressoes');
        
        return response()->json(['success' => true]);
    }

    /**
     * Registrar clique de anúncio
     */
    public function registrarClique(Anuncio $anuncio)
    {
        $anuncio->increment('cliques');
        
        return response()->json(['success' => true]);
    }
}
