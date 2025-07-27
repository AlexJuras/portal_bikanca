<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use App\Models\Categoria;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compartilhar categorias globalmente com Inertia
        Inertia::share([
            'categorias' => function () {
                return Categoria::orderBy('nome')->get(['id', 'nome', 'slug'])->toArray();
            },
        ]);
    }
}
