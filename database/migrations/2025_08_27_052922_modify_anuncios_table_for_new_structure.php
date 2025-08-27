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
        Schema::table('anuncios', function (Blueprint $table) {
            // Remover campos que não serão mais usados
            $table->dropColumn(['posicao', 'pagina', 'ordem']);
            
            // Adicionar campo para indicar se o anúncio está globalmente ativo
            $table->boolean('ativo_global')->default(true)->after('ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anuncios', function (Blueprint $table) {
            // Restaurar campos removidos
            $table->string('posicao')->after('nome');
            $table->string('pagina')->after('posicao');
            $table->integer('ordem')->default(1)->after('ativo');
            
            // Remover campo adicionado
            $table->dropColumn('ativo_global');
        });
    }
};
