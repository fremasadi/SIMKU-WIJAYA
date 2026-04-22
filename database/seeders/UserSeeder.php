<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Owner',
            'email' => 'owner2@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);

        // Admin Keuangan
        User::create([
            'name' => 'Admin Keuangan',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }
}
