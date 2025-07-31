<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory;

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function noticias()
    {
        return $this->hasMany(Noticia::class, 'categoria_id');
    }

    public function midias()
    {
        return $this->hasMany(Midia::class, 'categoria_id');
    }

    public function videos()
    {
        return $this->hasMany(Midia::class, 'categoria_id')->where('tipo', 'video');
    }
}
