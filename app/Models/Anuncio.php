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
        'posicao',
        'pagina',
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
        'ordem',
        'data_inicio',
        'data_fim',
        'impressoes',
        'cliques',
        'observacoes'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'nova_aba' => 'boolean',
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'impressoes' => 'integer',
        'cliques' => 'integer',
        'ordem' => 'integer',
        'largura' => 'integer',
        'altura' => 'integer',
    ];

    // Scopes
    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorPosicao($query, $posicao)
    {
        return $query->where('posicao', $posicao);
    }

    public function scopePorPagina($query, $pagina)
    {
        return $query->where('pagina', $pagina);
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

    public function scopeOrdenado($query)
    {
        return $query->orderBy('ordem', 'asc')->orderBy('created_at', 'desc');
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

    // Static methods para posições e páginas disponíveis
    public static function getPosicoes()
    {
        return [
            'banner-topo' => 'Banner Topo (970x250)',
            'banner-lateral-topo' => 'Banner Lateral Topo (300x250)',
            'banner-lateral-meio' => 'Banner Lateral Meio (300x250)',
            'banner-lateral-rodape' => 'Banner Lateral Rodapé (300x600)',
            'banner-meio-conteudo' => 'Banner Meio do Conteúdo (728x90)',
            'banner-rodape' => 'Banner Rodapé (970x250)',
            'banner-flutuante' => 'Banner Flutuante',
            'sidebar-widget' => 'Widget Sidebar',
        ];
    }

    public static function getPaginas()
    {
        return [
            'home' => 'Página Inicial',
            'noticias' => 'Listagem de Notícias',
            'noticia-individual' => 'Página Individual de Notícia',
            'categoria' => 'Páginas de Categoria',
            'videos' => 'Página de Vídeos',
            'todas' => 'Todas as Páginas',
        ];
    }

    public static function getTipos()
    {
        return [
            'imagem' => 'Imagem com Link',
            'html' => 'Código HTML Personalizado',
            'script' => 'Script de Terceiros (Google Ads, etc.)',
        ];
    }
}
