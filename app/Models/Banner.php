<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nome
 * @property string $posicao
 * @property string $pagina
 * @property string|null $imagem
 * @property string|null $link
 * @property string|null $descricao
 * @property int|null $largura
 * @property int|null $altura
 * @property bool $ativo
 * @property int $ordem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $imagem_url
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner ativo()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner ordenado()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner porPagina($pagina)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner porPosicao($posicao)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereAltura($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereLargura($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner wherePagina($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner wherePosicao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
