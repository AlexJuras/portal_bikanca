<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// ====== ROTAS DE AUTENTICAÇÃO ======
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ====== PÁGINA INICIAL ======
Route::get('/', [HomeController::class, 'index'])->name('home');

// ====== API ROUTES ======
Route::post('/api/videos/{midia}/view', [VideoController::class, 'incrementView'])->name('videos.increment-view');

// ====== PAINEL ADMINISTRATIVO ======
Route::prefix('admin')->name('admin.')->middleware(['admin.auth'])->group(function () {
    // Dashboard principal
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Perfil do usuário
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/remove-photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-photo');
    
    // Gerenciamento de Notícias (área restrita)
    Route::prefix('noticias')->name('noticias.')->group(function () {
        Route::get('/', [NoticiaController::class, 'admin'])->name('index');
        Route::get('/create', [NoticiaController::class, 'create'])->name('create');
        Route::post('/', [NoticiaController::class, 'store'])->name('store');
        Route::get('/{noticia}', [NoticiaController::class, 'show'])->name('show')->where('noticia', '[0-9]+');
        Route::get('/{noticia}/edit', [NoticiaController::class, 'edit'])->name('edit')->where('noticia', '[0-9]+');
        Route::put('/{noticia}', [NoticiaController::class, 'update'])->name('update')->where('noticia', '[0-9]+');
        Route::patch('/{noticia}', [NoticiaController::class, 'update'])->name('update')->where('noticia', '[0-9]+');
        Route::delete('/{noticia}', [NoticiaController::class, 'destroy'])->name('destroy')->where('noticia', '[0-9]+');
        
        // API para verificar slug único (apenas para admin)
        Route::get('/check-slug/{slug}', [NoticiaController::class, 'checkSlug'])->name('check-slug');
    });
    
    // Gerenciamento de Categorias (área restrita)
    Route::prefix('categorias')->name('categorias.')->group(function () {
        Route::get('/', [CategoriaController::class, 'admin'])->name('index');
        Route::get('/create', [CategoriaController::class, 'create'])->name('create');
        Route::post('/', [CategoriaController::class, 'store'])->name('store');
        Route::get('/{categoria}', [CategoriaController::class, 'show'])->name('show');
        Route::get('/{categoria}/edit', [CategoriaController::class, 'edit'])->name('edit');
        Route::put('/{categoria}', [CategoriaController::class, 'update'])->name('update');
        Route::delete('/{categoria}', [CategoriaController::class, 'destroy'])->name('destroy');
    });
    
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
        Route::get('/', [TagController::class, 'admin'])->name('index');
        Route::get('/create', [TagController::class, 'create'])->name('create');
        Route::post('/', [TagController::class, 'store'])->name('store');
        Route::get('/{tag}', [TagController::class, 'show'])->name('show');
        Route::get('/{tag}/edit', [TagController::class, 'edit'])->name('edit');
        Route::put('/{tag}', [TagController::class, 'update'])->name('update');
        Route::delete('/{tag}', [TagController::class, 'destroy'])->name('destroy');
    });
    
    // Gerenciamento de Vídeos (área restrita)
    Route::prefix('videos')->name('videos.')->group(function () {
        Route::get('/', [VideoController::class, 'admin'])->name('index');
        Route::get('/create', [VideoController::class, 'create'])->name('create');
        Route::post('/', [VideoController::class, 'store'])->name('store');
        Route::get('/{midia}', [VideoController::class, 'show'])->name('show');
        Route::get('/{midia}/edit', [VideoController::class, 'edit'])->name('edit');
        Route::put('/{midia}', [VideoController::class, 'update'])->name('update');
        Route::delete('/{midia}', [VideoController::class, 'destroy'])->name('destroy');
    });
});

// ====== ROTAS PÚBLICAS DE NOTÍCIAS ======
Route::prefix('noticias')->name('noticias.')->group(function () {
    // Páginas públicas
    Route::get('/', [NoticiaController::class, 'index'])->name('index');
    Route::get('/categoria/{categoria}', [NoticiaController::class, 'categoria'])->name('categoria');
    Route::get('/{noticia}', [NoticiaController::class, 'show'])->name('show');
});

// ====== ROTAS PÚBLICAS DE VÍDEOS ======
Route::prefix('videos')->name('videos.')->group(function () {
    // Páginas públicas
    Route::get('/', [VideoController::class, 'index'])->name('index');
    Route::get('/{midia}', [VideoController::class, 'show'])->name('show')->where('midia', '[0-9]+');
});