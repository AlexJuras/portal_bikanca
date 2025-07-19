<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Primeiro a URL, depois o nome do componente e depois os dados que serão passados para o componente

Route::inertia('/', 'Inicio', ['user' => 'Bikanca']);
Route::inertia('/sobre', 'Sobre', ['user' => 'Liam']);
Route::inertia('/contato', 'Contato', ['contato' => '(86) 9 9493-7797']);
Route::inertia('/noticias', 'Noticia');
Route::inertia('/categorias', 'Categoria');
