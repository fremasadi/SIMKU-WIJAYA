<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode_awal',
        'periode_akhir',
        'jumlah_gaji',
        'tanggal_bayar',
        'status',
        'karyawan_id',
    ];

    protected $casts = [
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
        'tanggal_bayar' => 'date',
        'jumlah_gaji' => 'decimal:2',
    ];

    /**
     * Relasi ke Karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}