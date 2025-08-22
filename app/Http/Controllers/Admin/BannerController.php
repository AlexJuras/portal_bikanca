<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('pagina')
            ->orderBy('posicao')
            ->orderBy('ordem')
            ->get();

        return Inertia::render('Admin/Banners/Index', [
            'banners' => $banners
        ]);
    }

    public function create()
    {
        $posicoes = [
            'inicio' => [
                'inicio_topo' => 'Banner Topo (728x90)',
                'inicio_meio' => 'Banner Meio (728x120)',
            ],
            'noticias' => [
                'noticias_principal' => 'Banner Principal (970x250)',
                'noticias_horizontal' => 'Banner Horizontal (728x90)',
                'noticias_lateral' => 'Banner Lateral (300x250)',
                'noticias_vertical' => 'Banner Vertical (300x600)',
            ],
            'noticia' => [
                'noticia_meio' => 'Banner Meio da Notícia (728x90)',
                'noticia_final' => 'Banner Final da Notícia (300x250)',
            ],
            'videos' => [
                'videos_topo' => 'Banner Topo Vídeos (728x90)',
                'videos_lateral' => 'Banner Lateral Vídeos (300x250)',
            ],
            'video' => [
                'video_meio' => 'Banner Meio do Vídeo (728x90)',
                'video_final' => 'Banner Final do Vídeo (300x250)',
            ]
        ];

        return Inertia::render('Admin/Banners/Create', [
            'posicoes' => $posicoes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'posicao' => 'required|string',
            'pagina' => 'required|string',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'descricao' => 'nullable|string',
            'largura' => 'required|integer|min:1',
            'altura' => 'required|integer|min:1',
            'ativo' => 'boolean',
            'ordem' => 'integer|min:0'
        ]);

        $banner = new Banner($request->all());

        if ($request->hasFile('imagem')) {
            $banner->imagem = $request->file('imagem')->store('banners', 'public');
        }

        $banner->save();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner criado com sucesso!');
    }

    public function show(Banner $banner)
    {
        return Inertia::render('Admin/Banners/Show', [
            'banner' => $banner
        ]);
    }

    public function edit(Banner $banner)
    {
        $posicoes = [
            'inicio' => [
                'inicio_topo' => 'Banner Topo (728x90)',
                'inicio_meio' => 'Banner Meio (728x120)',
            ],
            'noticias' => [
                'noticias_principal' => 'Banner Principal (970x250)',
                'noticias_horizontal' => 'Banner Horizontal (728x90)',
                'noticias_lateral' => 'Banner Lateral (300x250)',
                'noticias_vertical' => 'Banner Vertical (300x600)',
            ],
            'noticia' => [
                'noticia_meio' => 'Banner Meio da Notícia (728x90)',
                'noticia_final' => 'Banner Final da Notícia (300x250)',
            ],
            'videos' => [
                'videos_topo' => 'Banner Topo Vídeos (728x90)',
                'videos_lateral' => 'Banner Lateral Vídeos (300x250)',
            ],
            'video' => [
                'video_meio' => 'Banner Meio do Vídeo (728x90)',
                'video_final' => 'Banner Final do Vídeo (300x250)',
            ]
        ];

        return Inertia::render('Admin/Banners/Edit', [
            'banner' => $banner,
            'posicoes' => $posicoes
        ]);
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'posicao' => 'required|string',
            'pagina' => 'required|string',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'descricao' => 'nullable|string',
            'largura' => 'required|integer|min:1',
            'altura' => 'required|integer|min:1',
            'ativo' => 'boolean',
            'ordem' => 'integer|min:0'
        ]);

        $banner->fill($request->all());

        if ($request->hasFile('imagem')) {
            // Remove imagem anterior se existir
            if ($banner->imagem) {
                Storage::disk('public')->delete($banner->imagem);
            }
            $banner->imagem = $request->file('imagem')->store('banners', 'public');
        }

        $banner->save();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner atualizado com sucesso!');
    }

    public function destroy(Banner $banner)
    {
        // Remove imagem se existir
        if ($banner->imagem) {
            Storage::disk('public')->delete($banner->imagem);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner removido com sucesso!');
    }
}
