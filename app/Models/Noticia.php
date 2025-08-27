<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string $titulo
 * @property string $slug
 * @property string|null $resumo
 * @property string $conteudo
 * @property string $status
 * @property bool $destaque_home
 * @property int $visualizacoes
 * @property int $cliques
 * @property string|null $layout
 * @property int|null $autor_id
 * @property int|null $categoria_id
 * @property string|null $imagem_capa
 * @property \Illuminate\Support\Carbon|null $publicada_em
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Autor|null $autor
 * @property-read \App\Models\Categoria|null $categoria
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read \App\Models\Midia|null $capa
 * @property-read int|null $tags_count
 * @method static \Database\Factories\NoticiaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia publicadas()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereAutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereCliques($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereConteudo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereDestaqueHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereImagemCapa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia wherePublicadaEm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereResumo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereVisualizacoes($value)
 * @mixin \Eloquent
 */
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
