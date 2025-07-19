<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('midias', function (Blueprint $table) {
            $table->id();

            $table->string('caminho'); // Ex: 'midias/imagem.jpg'
            $table->string('tipo')->default('imagem'); // imagem, video, documento, etc.
            $table->string('formato')->nullable(); // Ex: 'capa', 'galeria', 'thumb', etc.
            $table->string('legenda')->nullable();
            $table->string('creditos')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('midias');
    }
};
