<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anuncios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('posicao'); // banner-topo, banner-lateral, banner-meio, banner-rodape, etc.
            $table->string('pagina'); // home, noticias, categoria, noticia-individual, videos
            $table->enum('tipo', ['imagem', 'html', 'script'])->default('imagem');
            $table->string('imagem')->nullable(); // caminho da imagem
            $table->text('html_content')->nullable(); // conteúdo HTML personalizado
            $table->text('script_content')->nullable(); // scripts de terceiros (Google Ads, etc.)
            $table->string('link')->nullable(); // URL de destino
            $table->boolean('nova_aba')->default(true); // abrir em nova aba
            $table->string('dimensoes')->nullable(); // ex: 728x90, 300x250
            $table->integer('largura')->nullable();
            $table->integer('altura')->nullable();
            $table->boolean('ativo')->default(true);
            $table->integer('ordem')->default(0); // para ordenação
            $table->datetime('data_inicio')->nullable(); // agendamento
            $table->datetime('data_fim')->nullable();
            $table->integer('impressoes')->default(0); // contador de visualizações
            $table->integer('cliques')->default(0); // contador de cliques
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            // Índices para performance
            $table->index(['posicao', 'pagina', 'ativo']);
            $table->index(['data_inicio', 'data_fim']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anuncios');
    }
};
