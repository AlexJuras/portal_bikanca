<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    /** @use HasFactory<\Database\Factories\AutorFactory> */
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'bio',
        'foto',
        // 'usuario_id'
    ];

    public function noticias()
    {
        return $this->hasMany(Noticia::class, 'autor_id');
    }
}
