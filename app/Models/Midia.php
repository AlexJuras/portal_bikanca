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
    ];

    public function getCaminhoAttribute($value)
    {
        // Se já é uma URL completa, retorna como está
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        
        // Caso contrário, adiciona o prefixo
        return asset('storage/' . $value);
    }

    public function noticias()
    {
        return $this->belongsToMany(Noticia::class, 'noticias_midia');
    }
}
