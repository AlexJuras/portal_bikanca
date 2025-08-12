<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Atualizar todos os usuários com tipo "user" para "editor"
        DB::table('users')
            ->where('tipo', 'user')
            ->update(['tipo' => 'editor']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter todos os usuários com tipo "editor" para "user"
        // (apenas se não existir nenhum "editor" original)
        DB::table('users')
            ->where('tipo', 'editor')
            ->update(['tipo' => 'user']);
    }
};
