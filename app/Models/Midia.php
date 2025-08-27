<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $caminho
 * @property string|null $legenda
 * @property string|null $formato
 * @property string|null $creditos
 * @property string $tipo
 * @property string|null $titulo
 * @property string|null $descricao
 * @property string|null $url_externa
 * @property string|null $thumbnail
 * @property string|null $duracao
 * @property int|null $categoria_id
 * @property int|null $autor_id
 * @property int $visualizacoes
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $publicada_em
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Categoria|null $categoria
 * @property-read \App\Models\Autor|null $autor
 * @property-read mixed $embed_url
 * @property-read mixed $embed_url_standard
 * @property-read mixed $thumbnail_url
 * @property-read mixed $youtube_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Noticia> $noticiasCapa
 * @property-read int|null $noticias_capa_count
 * @method static \Database\Factories\MidiaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia imagens()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia publicadas()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia videos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereAutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereCaminho($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereCreditos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereDuracao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereFormato($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereLegenda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia wherePublicadaEm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereUrlExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Midia whereVisualizacoes($value)
 * @mixin \Eloquent
 */
class Midia extends Model
{
    /** @use HasFactory<\Database\Factories\MidiaFactory> */
    use HasFactory;

    const FORMATOS = [
        'capa' => [800, 400],
        'thumb' => [300, 300],
        'destaque' => [1200, 600],
        'galeria' => [600, 400]
    ];
    
    protected $fillable = [
        'caminho',
        'legenda',
        'formato',
        'creditos',
        'tipo',
        'titulo',
        'descricao',
        'url_externa',
        'thumbnail',
        'duracao',
        'categoria_id',
        'autor_id',
        'visualizacoes',
        'status',
        'publicada_em',
    ];

    protected $casts = [
        'publicada_em' => 'datetime',
        'visualizacoes' => 'integer'
    ];

    // Relacionamentos
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    // Relacionamento inverso para notícias que usam esta mídia como capa
    public function noticiasCapa()
    {
        return $this->hasMany(Noticia::class, 'imagem_capa');
    }

    // Scopes
    public function scopeVideos($query)
    {
        return $query->where('tipo', 'video');
    }

    public function scopeImagens($query)
    {
        return $query->where('tipo', 'imagem');
    }

    public function scopePublicadas($query)
    {
        return $query->where('status', 'publicada');
    }

    // Métodos auxiliares
    public function isVideo()
    {
        return $this->tipo === 'video';
    }

    public function getYoutubeIdAttribute()
    {
        if (!$this->url_externa) return null;
        
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $this->url_externa, $matches);
        
        return $matches[1] ?? null;
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return $this->thumbnail;
        }
        
        if ($this->isVideo() && $this->youtube_id) {
            // Usar hqdefault.jpg que é mais confiável que maxresdefault.jpg
            return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
        }
        
        return '/images/video-placeholder.svg';
    }

    public function getEmbedUrlAttribute()
    {
        if ($this->isVideo() && $this->youtube_id) {
            // Parâmetros para melhor compatibilidade
            $params = [
                'rel=0',           // Não mostrar vídeos relacionados de outros canais
                'modestbranding=1', // Remover logo do YouTube
                'fs=1',            // Permitir fullscreen
                'cc_load_policy=0', // Não carregar legendas por padrão
                'iv_load_policy=3', // Não mostrar anotações
                'autoplay=0',      // Não reproduzir automaticamente
            ];
            
            $paramString = implode('&', $params);
            
            // Usar youtube-nocookie.com para melhor compatibilidade em alguns casos
            return "https://www.youtube-nocookie.com/embed/{$this->youtube_id}?{$paramString}";
        }
        
        return null;
    }

    public function getEmbedUrlStandardAttribute()
    {
        if ($this->isVideo() && $this->youtube_id) {
            $params = [
                'rel=0',
                'modestbranding=1',
                'fs=1',
                'cc_load_policy=0',
                'iv_load_policy=3',
                'autoplay=0',
            ];
            
            $paramString = implode('&', $params);
            return "https://www.youtube.com/embed/{$this->youtube_id}?{$paramString}";
        }
        
        return null;
    }

    public function getCaminhoAttribute($value)
    {
        // Se já é uma URL completa, retorna como está
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        
        // Caso contrário, adiciona o prefixo
        return asset('storage/' . $value);
    }
}   