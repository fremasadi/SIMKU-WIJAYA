<?php

namespace Database\Seeders;

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
        $this->call([
            UserSeeder::class,
            KaryawanSeeder::class,
            BahanBakuSeeder::class,
            ProdukSeeder::class,
            PembelianSeeder::class,
            ProduksiSeeder::class,
            PenjualanSeeder::class,
            PresensiSeeder::class,
            GajiSeeder::class,
        ]);
    }
}
