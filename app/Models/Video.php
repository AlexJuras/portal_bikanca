<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $titulo
 * @property string|null $descricao
 * @property string|null $url_externa
 * @property string|null $thumbnail
 * @property string|null $duracao
 * @property string $status
 * @property int|null $categoria_id
 * @property int|null $autor_id
 * @property int $visualizacoes
 * @property \Illuminate\Support\Carbon|null $publicada_em
 * @property string|null $caminho
 * @property string $tipo
 * @property string|null $formato
 * @property string|null $legenda
 * @property string|null $creditos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Categoria|null $categoria
 * @property-read \App\Models\Autor|null $autor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video destaque()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video publicados()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video videos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereAutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereCaminho($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereCreditos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereDuracao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereFormato($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereLegenda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video wherePublicadaEm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereUrlExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereVisualizacoes($value)
 * @mixin \Eloquent
 */
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