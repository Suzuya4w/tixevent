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
        // Admin default untuk Filament
        if (!User::where('email', 'admin@tixevent.com')->exists()) {
            User::factory()->create([
                'name' => 'Super Admin',
                'email' => 'admin@tixevent.com',
                'password' => bcrypt('password'), // password: password
            ]);
        }
    }
}
