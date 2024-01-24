<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Categorie;
use App\Models\InformationComplementaire;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::factory(3)
            ->has(User::factory()->count(2))
            ->create();
        
        Categorie::factory(2)
            ->has(Video::factory()->count(2))
            ->create();

        Post::factory(2)
            ->create();
        
        InformationComplementaire::factory(2)
            ->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
