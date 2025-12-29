<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);

        // Admin Keuangan
        User::create([
            'name' => 'Admin Keuangan',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }
}
