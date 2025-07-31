<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    // Especifica que este modelo usa a tabela 'midias'
    protected $table = 'midias';

    protected $fillable = [
        'titulo',
        'descricao',
        'url_externa', // Campo para URL do YouTube
        'thumbnail',
        'duracao',
        'status',
        'categoria_id',
        'autor_id',
        'visualizacoes',
        'publicada_em',
        'caminho',
        'tipo',
        'formato',
        'legenda',
        'creditos'
    ];

    protected $casts = [
        'publicada_em' => 'datetime',
    ];

    // Adicionar um scope para filtrar apenas vídeos
    public function scopeVideos($query)
    {
        return $query->where('tipo', 'video');
    }

    // Relacionamentos
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    // Scopes
    public function scopePublicados($query)
    {
        return $query->where('status', 'publicada'); // Ajustado para 'publicada'
    }

    public function scopeDestaque($query)
    {
        return $query->where('destaque_home', true);
    }
}