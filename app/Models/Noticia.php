<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    /** @use HasFactory<\Database\Factories\NoticiaFactory> */
    use HasFactory;

    protected $fillable = [
        'titulo',
        'resumo',
        'conteudo',
        'slug',
        'publicada_em',
        'autor_id',
        'categoria_id',
        'status',
        'visualizacoes',
        'imagem_capa',
    ];

    public function autor()
    {
        return $this->belongsTo(Autor::class, 'autor_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function tag()
    {
        return $this->hasMany(Tag::class, 'noticia_id');
    }

}
