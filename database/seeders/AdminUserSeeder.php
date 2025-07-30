<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador padrão
        User::firstOrCreate(
            ['email' => 'admin@bikanca.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456'),
                'tipo' => 'admin',
            ]
        );

        // Criar outros usuários admin se necessário
        User::firstOrCreate(
            ['email' => 'editor@bikanca.com'],
            [
                'name' => 'Editor',
                'password' => Hash::make('123456'),
                'tipo' => 'admin',
            ]
        );
    }
}
