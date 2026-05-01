<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@tixevent.com')->exists()) {
            User::factory()->create([
                'name' => 'Super Admin',
                'email' => 'admin@tixevent.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        if (!User::where('email', 'petugas@tixevent.com')->exists()) {
            User::factory()->create([
                'name' => 'Petugas',
                'email' => 'petugas@tixevent.com',
                'password' => bcrypt('password'),
                'role' => 'petugas',
            ]);
        }

        if (!User::where('email', 'user@tixevent.com')->exists()) {
            User::factory()->create([
                'name' => 'User',
                'email' => 'user@tixevent.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]);
        }
    }
}
