<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string $nome
 * @property string $tipo
 * @property string|null $imagem
 * @property string|null $html_content
 * @property string|null $script_content
 * @property string|null $link
 * @property bool $nova_aba
 * @property string|null $dimensoes
 * @property int|null $largura
 * @property int|null $altura
 * @property bool $ativo
 * @property bool $ativo_global
 * @property \Illuminate\Support\Carbon|null $data_inicio
 * @property \Illuminate\Support\Carbon|null $data_fim
 * @property int $impressoes
 * @property int $cliques
 * @property int $ordem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AnuncioPagina> $paginas
 * @property string|null $observacoes
 * @property-read mixed $dimensoes_formatada
 * @property-read mixed $imagem_url
 * @property-read int|null $paginas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AnuncioPagina> $paginasAtivas
 * @property-read int|null $paginas_ativas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio ativo()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio valido()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereAltura($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereAtivoGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereCliques($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereDataFim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereDataInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereDimensoes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereHtmlContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereImpressoes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereLargura($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereNovaAba($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereObservacoes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereScriptContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anuncio whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Anuncio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'tipo',
        'imagem',
        'html_content',
        'script_content',
        'link',
        'nova_aba',
        'dimensoes',
        'largura',
        'altura',
        'ativo',
        'ativo_global',
        'data_inicio',
        'data_fim',
        'impressoes',
        'cliques',
        'observacoes'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'ativo_global' => 'boolean',
        'nova_aba' => 'boolean',
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'impressoes' => 'integer',
        'cliques' => 'integer',
        'largura' => 'integer',
        'altura' => 'integer',
    ];

    // Relacionamentos
    public function paginas()
    {
        return $this->hasMany(AnuncioPagina::class);
    }

    public function paginasAtivas()
    {
        return $this->hasMany(AnuncioPagina::class)->where('ativo', true)->orderBy('ordem');
    }

    // Scopes
    public function scopeAtivo($query)
    {
        return $query->where('ativo', true)->where('ativo_global', true);
    }

    public function scopeValido($query)
    {
        $now = Carbon::now();
        return $query->where(function ($q) use ($now) {
            $q->where(function ($subQuery) use ($now) {
                $subQuery->whereNull('data_inicio')
                         ->orWhere('data_inicio', '<=', $now);
            })->where(function ($subQuery) use ($now) {
                $subQuery->whereNull('data_fim')
                         ->orWhere('data_fim', '>=', $now);
            });
        });
    }

    // Métodos para obter anúncios de uma página específica
    public static function parasPagina($pagina, $limite = 3)
    {
        return static::ativo()
            ->valido()
            ->whereHas('paginasAtivas', function ($query) use ($pagina) {
                $query->where('pagina', $pagina);
            })
            ->with(['paginasAtivas' => function ($query) use ($pagina) {
                $query->where('pagina', $pagina)->orderBy('ordem');
            }])
            ->take($limite)
            ->get();
    }

    // Accessors
    public function getImagemUrlAttribute()
    {
        if (!$this->imagem) {
            return null;
        }

        if (filter_var($this->imagem, FILTER_VALIDATE_URL)) {
            return $this->imagem;
        }

        return Storage::url($this->imagem);
    }

    public function getDimensoesFormatadaAttribute()
    {
        if ($this->largura && $this->altura) {
            return "{$this->largura}x{$this->altura}";
        }
        return $this->dimensoes;
    }

    // Methods
    public function incrementarImpressao()
    {
        $this->increment('impressoes');
    }

    public function incrementarClique()
    {
        $this->increment('cliques');
    }

    public function isVigente()
    {
        $now = Carbon::now();
        
        $inicioValido = !$this->data_inicio || $this->data_inicio <= $now;
        $fimValido = !$this->data_fim || $this->data_fim >= $now;
        
        return $this->ativo && $inicioValido && $fimValido;
    }

    public function getCtr()
    {
        if ($this->impressoes === 0) {
            return 0;
        }
        return round(($this->cliques / $this->impressoes) * 100, 2);
    }

    // Static methods
    public static function getTipos()
    {
        return [
            'imagem' => 'Imagem com Link',
            'html' => 'Código HTML Personalizado',
            'script' => 'Script de Terceiros (Google Ads, etc.)',
        ];
    }

    public static function getPosicoes()
    {
        return [
            'topo' => 'Topo da Página',
            'lateral-direita' => 'Lateral Direita',
            'lateral-esquerda' => 'Lateral Esquerda',
            'meio-conteudo' => 'Meio do Conteúdo',
            'rodape' => 'Rodapé',
            'popup' => 'Pop-up',
            'banner-principal' => 'Banner Principal',
            'entre-noticias' => 'Entre Notícias',
        ];
    }

    public static function getPaginas()
    {
        return [
            'inicio' => 'Página Inicial',
            'noticias' => 'Listagem de Notícias',
            'noticia-single' => 'Página de Notícia Individual',
            'videos' => 'Listagem de Vídeos',
            'video-single' => 'Página de Vídeo Individual',
            'categorias' => 'Página de Categorias',
            'todas' => 'Todas as Páginas',
        ];
    }
}
