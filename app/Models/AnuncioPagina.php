<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $anuncio_id
 * @property string $pagina
 * @property int $ordem
 * @property bool $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Anuncio $anuncio
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnuncioPagina newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnuncioPagina newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnuncioPagina query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnuncioPagina whereAnuncioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnuncioPagina whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnuncioPagina whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnuncioPagina whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnuncioPagina whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnuncioPagina wherePagina($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnuncioPagina whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
