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
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('resumo')->nullable();
            $table->longtext('conteudo');
            
            // Status e metadados
            $table->enum('status', ['rascunho', 'publicada', 'arquivada', 'agendada'])->default('rascunho');
            $table->timestamp('publicada_em')->nullable();
            $table->unsignedBigInteger('visualizacoes')->default(0);
            $table->string('layout')->nullable();
            
            // Relacionamentos (certifique-se que estas tabelas existam)
            $table->foreignId('autor_id')->nullable()->constrained('autors')->nullOnDelete();
            $table->foreignId('imagem_capa')->nullable()->constrained('midias')->nullOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};
