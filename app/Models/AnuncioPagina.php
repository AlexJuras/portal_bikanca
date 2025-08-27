<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnuncioPagina extends Model
{
    protected $table = 'anuncio_pagina';
    
    protected $fillable = [
        'anuncio_id',
        'pagina',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function anuncio(): BelongsTo
    {
        return $this->belongsTo(Anuncio::class);
    }

    // Páginas disponíveis
    public static function getPaginasDisponiveis()
    {
        return [
            'home' => 'Página Inicial',
            'noticias' => 'Página de Notícias',
            'categoria' => 'Páginas de Categoria',
            'videos' => 'Página de Vídeos',
        ];
    }

    // Máximo de anúncios por página
    public static function getMaxAnunciosPorPagina()
    {
        return 3;
    }
}
