<?php

namespace App\Http\Controllers;

use App\Models\Midia;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Storage;

class MidiaController extends Controller
{
    public function index()
    {
        $midias = Midia::latest()->paginate(12);

        return Inertia::render('Midias/Index', [
            'midias' => $midias
        ]);
    }

    public function create()
    {
        return Inertia::render('Midias/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi|max:10240', // 10MB
            'tipo' => 'required|string',
        ]);

        if ($request->hasFile('arquivo')) {
            $path = $request->file('arquivo')->store('midias', 'public');

            Midia::create([
                'caminho' => $path,
                'tipo' => $request->tipo,
                'descricao' => $request->descricao,
                'noticia_id' => $request->noticia_id, // se estiver associando à notícia
            ]);
        }

        return redirect()->route('midias.index')->with('success', 'Arquivo enviado com sucesso!');
    }

    public function edit(Midia $midia)
    {
        return Inertia::render('Midias/Edit', [
            'midia' => $midia
        ]);
    }

    public function update(Request $request, Midia $midia)
    {
        $request->validate([
            'descricao' => 'nullable|string',
        ]);

        $midia->update($request->only(['descricao']));

        return redirect()->route('midias.index')->with('success', 'Mídia atualizada com sucesso!');
    }

    public function destroy(Midia $midia)
    {
        if (Storage::disk('public')->exists($midia->caminho)) {
            Storage::disk('public')->delete($midia->caminho);
        }

        $midia->delete();

        return redirect()->route('midias.index')->with('success', 'Mídia removida com sucesso!');
    }
}
