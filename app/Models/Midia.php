<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Midia extends Model
{
    /** @use HasFactory<\Database\Factories\MidiaFactory> */
    use HasFactory;

    protected $fillable = [
        'caminho',
        'descricao',
        'caminho',
        'tipo',
        'creditos',
    ];

    public function noticias()
    {
        return $this->belongsToMany(Noticia::class, 'noticias_midia');
    }
}
