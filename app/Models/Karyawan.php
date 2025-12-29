<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jabatan',
        'no_hp',
        'alamat',
        'tanggal_masuk',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    /**
     * Relasi ke Presensi
     */
    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'id_karyawan');
    }

    /**
     * Relasi ke Gaji
     */
    public function gajis()
    {
        return $this->hasMany(Gaji::class, 'id_karyawan');
    }
}