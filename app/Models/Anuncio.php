<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
}
