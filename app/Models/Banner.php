<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'posicao',
        'pagina',
        'imagem',
        'link',
        'descricao',
        'largura',
        'altura',
        'ativo',
        'ordem'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'largura' => 'integer',
        'altura' => 'integer',
        'ordem' => 'integer',
    ];

    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorPagina($query, $pagina)
    {
        return $query->where('pagina', $pagina);
    }

    public function scopePorPosicao($query, $posicao)
    {
        return $query->where('posicao', $posicao);
    }

    public function scopeOrdenado($query)
    {
        return $query->orderBy('ordem');
    }

    public function getImagemUrlAttribute()
    {
        return $this->imagem ? asset('storage/' . $this->imagem) : null;
    }
}
