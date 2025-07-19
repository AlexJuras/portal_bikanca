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

            $table->enum('status', ['rascunho', 'publicada', 'arquivada', 'agendada'])->default('rascunho');
            $table->timestamp('publicada_em')->nullable();
            $table->unsignedBigInteger('visualizacoes')->default(0);
            $table->foreignId('imagem_capa')->constrained('midia')->onDelete('cascade');

            //Relacionamentos
            // $table->foreignId('autor_id')->constrained('autor')->onDelete('cascade');
            // $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();

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
