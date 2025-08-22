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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('posicao'); // 'inicio_topo', 'inicio_meio', 'noticias_principal', 'noticias_horizontal', 'noticias_lateral', 'noticias_vertical', 'noticia_meio', 'noticia_final'
            $table->string('pagina'); // 'inicio', 'noticias', 'noticia', 'videos', 'video'
            $table->string('imagem')->nullable();
            $table->string('link')->nullable();
            $table->text('descricao')->nullable();
            $table->integer('largura');
            $table->integer('altura');
            $table->boolean('ativo')->default(true);
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
