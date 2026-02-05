<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $karyawans = [
            [
                'nama' => 'Ahmad Suryadi',
                'jabatan' => 'Kepala Produksi',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Raya Cibaduyut No. 45, Bandung',
                'tanggal_masuk' => '2023-03-15',
            ],
            [
                'nama' => 'Budi Santoso',
                'jabatan' => 'Pengrajin Kulit',
                'no_hp' => '082198765432',
                'alamat' => 'Jl. Sukajadi No. 12, RT 03/RW 05, Bandung',
                'tanggal_masuk' => '2023-05-20',
            ],
            [
                'nama' => 'Cahya Permana',
                'jabatan' => 'Pengrajin Kulit',
                'no_hp' => '085312345678',
                'alamat' => 'Jl. Soekarno Hatta No. 78, Bandung',
                'tanggal_masuk' => '2023-08-10',
            ],
            [
                'nama' => 'Dedi Kurniawan',
                'jabatan' => 'Penjahit',
                'no_hp' => '087856781234',
                'alamat' => 'Jl. Pasirkaliki No. 33, Bandung',
                'tanggal_masuk' => '2024-01-08',
            ],
            [
                'nama' => 'Eko Prasetyo',
                'jabatan' => 'Penjahit',
                'no_hp' => '081345678912',
                'alamat' => 'Jl. Cipaganti No. 56, Bandung',
                'tanggal_masuk' => '2024-03-12',
            ],
            [
                'nama' => 'Fitri Handayani',
                'jabatan' => 'Quality Control',
                'no_hp' => '089912345678',
                'alamat' => 'Jl. Dago No. 21, Bandung',
                'tanggal_masuk' => '2024-06-01',
            ],
            [
                'nama' => 'Gunawan Wibowo',
                'jabatan' => 'Bagian Gudang',
                'no_hp' => '082267891234',
                'alamat' => 'Jl. Asia Afrika No. 90, Bandung',
                'tanggal_masuk' => '2023-11-15',
            ],
            [
                'nama' => 'Hendra Saputra',
                'jabatan' => 'Bagian Pemasaran',
                'no_hp' => '085678912345',
                'alamat' => 'Jl. Braga No. 15, Bandung',
                'tanggal_masuk' => '2024-02-20',
            ],
        ];

        foreach ($karyawans as $karyawan) {
            Karyawan::create($karyawan);
        }
    }
}
