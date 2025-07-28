<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function noticias()
    {
        return $this->belongsToMany(Noticia::class, 'noticias_midia');
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
            return "https://img.youtube.com/vi/{$this->youtube_id}/maxresdefault.jpg";
        }
        
        return '/images/video-placeholder.png';
    }

    public function getEmbedUrlAttribute()
    {
        if ($this->isVideo() && $this->youtube_id) {
            return "https://www.youtube.com/embed/{$this->youtube_id}?rel=0";
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