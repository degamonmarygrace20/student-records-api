<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::UpdateOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password123'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            ['name' => 'Regular User', 'password' => Hash::make('password123'), 'role' => 'user']
        );
    }
}
