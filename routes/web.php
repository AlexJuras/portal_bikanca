<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\NoticiaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Primeiro a URL, depois o nome do componente e depois os dados que serão passados para o componente

Route::inertia('/', 'Inicio', ['user' => 'Bikanca']);
Route::inertia('/sobre', 'Sobre', ['user' => 'Liam']);
Route::inertia('/contato', 'Contato', ['contato' => '(86) 9 9493-7797']);
Route::inertia('/noticias', 'Noticia');
Route::inertia('/categorias', 'Categoria');
Route::inertia('/tags', 'Tag');
Route::inertia('/noticias/edit', 'Noticias/Edit');
Route::inertia('/noticias/create', 'Noticias/Create', ['autores'=> \App\Models\Autor::all()]);


//Rotas CRUD para Notícia
Route::resource('categorias', CategoriaController::class);
Route::post('/noticias', [NoticiaController::class, 'store'])
    ->name('noticias.store');