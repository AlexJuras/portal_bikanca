<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// Páginas Estáticas
Route::inertia('/', 'Inicio', ['user' => 'Bikanca']);
Route::inertia('/sobre', 'Sobre', ['user' => 'Liam']);
Route::inertia('/contato', 'Contato', ['contato' => '(86) 9 9493-7797']);

// ====== PAINEL ADMINISTRATIVO ======
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard principal
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gerenciamento de Autores (área restrita)
    Route::prefix('autores')->name('autores.')->group(function () {
        Route::get('/', [AutorController::class, 'index'])->name('index');
        Route::get('/create', [AutorController::class, 'create'])->name('create');
        Route::post('/', [AutorController::class, 'store'])->name('store');
        Route::get('/{autor}', [AutorController::class, 'show'])->name('show');
        Route::get('/{autor}/edit', [AutorController::class, 'edit'])->name('edit');
        Route::put('/{autor}', [AutorController::class, 'update'])->name('update');
        Route::delete('/{autor}', [AutorController::class, 'destroy'])->name('destroy');
    });
    
    // Gerenciamento de Tags (área restrita)
    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::get('/create', [TagController::class, 'create'])->name('create');
        Route::post('/', [TagController::class, 'store'])->name('store');
        Route::get('/{tag}', [TagController::class, 'show'])->name('show');
        Route::get('/{tag}/edit', [TagController::class, 'edit'])->name('edit');
        Route::put('/{tag}', [TagController::class, 'update'])->name('update');
        Route::delete('/{tag}', [TagController::class, 'destroy'])->name('destroy');
    });
    
    // Redirecionamentos para outras páginas administrativas
    Route::get('/noticias', [NoticiaController::class, 'admin'])->name('noticias');
    
    Route::get('/categorias', function () {
        return redirect()->route('categorias.index');
    })->name('categorias');
});

// ====== ROTAS DE NOTÍCIAS ======
Route::prefix('noticias')->name('noticias.')->group(function () {
    // API para verificar slug único
    Route::get('/check-slug/{slug}', [NoticiaController::class, 'checkSlug']);
    
    // Páginas públicas
    Route::get('/', [NoticiaController::class, 'index'])->name('index');
    Route::get('/categoria/{categoria}', [NoticiaController::class, 'categoria'])->name('categoria');
    Route::get('/{noticia}', [NoticiaController::class, 'show'])->name('show');
    
    // Páginas administrativas
    Route::get('/admin/create', [NoticiaController::class, 'create'])->name('create');
    Route::get('/admin/{noticia}/edit', [NoticiaController::class, 'edit'])->name('edit');
    Route::post('/', [NoticiaController::class, 'store'])->name('store');
    Route::put('/{noticia}', [NoticiaController::class, 'update'])->name('update');
    Route::delete('/{noticia}', [NoticiaController::class, 'destroy'])->name('destroy');
});

// ====== ROTAS DE CATEGORIAS ======
Route::prefix('categorias')->name('categorias.')->group(function () {
    // Páginas públicas
    Route::get('/', [CategoriaController::class, 'index'])->name('index');
    Route::get('/{categoria}', [CategoriaController::class, 'show'])->name('show');
    
    // Páginas administrativas
    Route::get('/admin/create', [CategoriaController::class, 'create'])->name('create');
    Route::get('/admin/{categoria}/edit', [CategoriaController::class, 'edit'])->name('edit');
    Route::post('/', [CategoriaController::class, 'store'])->name('store');
    Route::put('/{categoria}', [CategoriaController::class, 'update'])->name('update');
    Route::delete('/{categoria}', [CategoriaController::class, 'destroy'])->name('destroy');
});

// ====== ROTAS DE VÍDEOS ======
Route::prefix('videos')->name('videos.')->group(function () {
    // Páginas públicas
    Route::get('/', [VideoController::class, 'index'])->name('index');
    Route::get('/categoria/{categoria}', [VideoController::class, 'categoria'])->name('categoria');
    Route::get('/{midia}', [VideoController::class, 'show'])->name('show')->where('midia', '[0-9]+');
    
    // Páginas administrativas
    Route::get('/admin/create', [VideoController::class, 'create'])->name('create');
    Route::get('/admin/{midia}/edit', [VideoController::class, 'edit'])->name('edit');
    Route::post('/', [VideoController::class, 'store'])->name('store');
    Route::put('/{midia}', [VideoController::class, 'update'])->name('update');
    Route::delete('/{midia}', [VideoController::class, 'destroy'])->name('destroy');
});