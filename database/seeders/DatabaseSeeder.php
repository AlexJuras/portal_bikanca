<?php

namespace Database\Seeders;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Midia;
use App\Models\Noticia;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Autor::factory(5)->create();
        Categoria::factory(5)->create();
        Midia::factory(10)->create();
        Tag::factory(10)->create();
        Noticia::factory(10)->create();

        // Noticia::factory()
        //     ->count(10)
        //     ->create();
    }
}
