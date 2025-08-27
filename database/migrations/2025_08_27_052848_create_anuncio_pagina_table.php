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
        Schema::create('anuncio_pagina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anuncio_id')->constrained('anuncios')->onDelete('cascade');
            $table->string('pagina'); // home, noticias, categoria, videos
            $table->integer('ordem')->default(1); // 1, 2 ou 3 (máximo 3 por página)
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            
            // Garantir que não há mais de 3 anúncios por página
            $table->unique(['pagina', 'ordem']);
            // Índice para consultas rápidas
            $table->index(['pagina', 'ativo', 'ordem']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anuncio_pagina');
    }
};
