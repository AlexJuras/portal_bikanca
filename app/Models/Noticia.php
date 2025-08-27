<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Noticia extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'slug',
        'resumo',
        'conteudo',
        'status',
        'destaque_home',
        'visualizacoes',
        'cliques',
        'layout',
        'autor_id',
        'categoria_id',
        'imagem_capa'
    ];

    protected $casts = [
        'publicada_em' => 'datetime',
        'destaque_home' => 'boolean',
    ];

    /**
     * Configurar route model binding para usar slug nas rotas públicas
     * e ID nas rotas admin
     */
    public function getRouteKeyName()
    {
        if (request()->is('admin/*')) {
            return 'id';
        }
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($noticia) {
            // Se o status está sendo alterado para 'publicada'
            if ($noticia->status === 'publicada') {
                // Se é uma nova notícia ou se o status estava diferente de 'publicada'
                if (!$noticia->exists || $noticia->getOriginal('status') !== 'publicada') {
                    $noticia->publicada_em = Carbon::now();
                }
            }
            // Se o status não é 'publicada', limpar a data de publicação
            elseif ($noticia->status !== 'publicada') {
                $noticia->publicada_em = null;
            }
        });
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(Autor::class, 'autor_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'noticia_tag');
    }

    public function capa(): BelongsTo
    {
        return $this->belongsTo(Midia::class, 'imagem_capa');
    }

    public function imagem_capa(): BelongsTo
    {
        return $this->belongsTo(Midia::class, 'imagem_capa');
    }

    /**
     * Verifica se a notícia está publicada
     */
    public function isPublicada(): bool
    {
        return $this->status === 'publicada';
    }

    /**
     * Scope para buscar apenas notícias publicadas
     */
    public function scopePublicadas($query)
    {
        return $query->where('status', 'publicada');
    }

    /**
     * Incrementar contador de visualizações
     */
    public function incrementarVisualizacoes()
    {
        $this->increment('visualizacoes');
    }

    /**
     * Incrementar contador de cliques
     */
    public function incrementarCliques()
    {
        $this->increment('cliques');
    }
}
