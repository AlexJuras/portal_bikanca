<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\NoticiaController;
use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Midia;
use App\Models\Noticia;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Primeiro a URL, depois o nome do componente e depois os dados que serão passados para o componente

// Páginas Estáticas
Route::inertia('/', 'Inicio', ['user' => 'Bikanca']);
Route::inertia('/sobre', 'Sobre', ['user' => 'Liam']);
Route::inertia('/contato', 'Contato', ['contato' => '(86) 9 9493-7797']);

// Rotas de Notícias
Route::prefix('noticias')->group(function () {
    Route::get('/check-slug/{slug}', function($slug) {
        return response()->json(['exists' => Noticia::where('slug', $slug)->exists()]);
    })->name('noticias.check-slug');
    Route::inertia('/', 'Noticias/Index', [
        'noticias' => Noticia::with(['autor', 'categoria', 'tags', 'imagemCapa'])
        ->latest()
        ->paginate(9),
        'categorias' => Categoria::withCount('noticias')->get(),
        'maisLidas' => Noticia::orderBy('visualizacoes', 'desc')
        ->take(5)
        ->get(),
    ])->name('noticias.index');
    
    Route::inertia('/create', 'Noticias/Create', [
        'autores' => Autor::all(),
        'midias' => Midia::all(),
        'categorias' => Categoria::all(),
        'tags' => Tag::all(),
    ])->name('noticias.create');
    
    Route::inertia('/edit', 'Noticias/Edit', [
        'autores' => Autor::all(),
        'midias' => Midia::all(),
        'categorias' => Categoria::all(),
        'tags' => Tag::all(),
    ])->name('noticias.edit');
    
    Route::post('/', [NoticiaController::class, 'store'])->name('noticias.store');
    
    // Rota para exibir uma notícia específica
    Route::get('/{noticia}', function(Noticia $noticia) {
        return Inertia::render('Noticias/Show', [
            'noticia' => $noticia->load('autor', 'categoria', 'tags'),
            'noticiasRelacionadas' => Noticia::where('categoria_id', $noticia->categoria_id)
                ->where('id', '!=', $noticia->id)
                ->take(3)
                ->get(),
        ]);
    })->name('noticias.show');
});

// Rotas de Categorias
Route::prefix('categorias')->group(function () {
    Route::inertia('/', 'Categoria')->name('categorias.index');
    // Uncomment when needed
    // Route::resource('/', CategoriaController::class);
});

// Rotas de Tags
Route::prefix('tags')->group(function () {
    Route::inertia('/', 'Tag')->name('tags.index');
});