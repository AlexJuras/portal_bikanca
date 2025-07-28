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
        Schema::table('midias', function (Blueprint $table) {
            // Campos específicos para vídeos
            $table->string('titulo')->nullable();
            $table->text('descricao')->nullable();
            $table->string('url_externa')->nullable(); // Para URLs do YouTube
            $table->string('thumbnail')->nullable(); // URL da thumbnail
            $table->string('duracao')->nullable(); // Duração do vídeo
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onDelete('set null');
            $table->foreignId('autor_id')->nullable()->constrained('autors')->onDelete('set null');
            $table->integer('visualizacoes')->default(0);
            $table->enum('status', ['rascunho', 'publicada'])->default('rascunho');
            $table->timestamp('publicada_em')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('midias', function (Blueprint $table) {
            $table->dropColumn([
                'titulo', 
                'descricao',
                'url_externa',
                'thumbnail',
                'duracao',
                'categoria_id',
                'autor_id',
                'visualizacoes',
                'status',
                'publicada_em'
            ]);
        });
    }
};
