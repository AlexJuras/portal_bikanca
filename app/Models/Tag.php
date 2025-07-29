<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
    ];

    public function noticias()
    {
        return $this->belongsToMany(Noticia::class, 'noticia_tag');
    }
}
