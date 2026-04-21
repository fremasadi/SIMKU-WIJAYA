<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiPotongan extends Model
{
    use HasFactory;

    protected $fillable = [
        'gaji_id',
        'presensi_id',
        'tanggal',
        'bulan',
        'tahun',
        'jenis',
        'keterangan',
        'jumlah_potongan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_potongan' => 'decimal:2',
    ];

    /**
     * Relasi ke Gaji
     */
    public function gaji()
    {
        return $this->belongsTo(Gaji::class);
    }

    /**
     * Relasi ke Presensi
     */
    public function presensi()
    {
        return $this->belongsTo(Presensi::class);
    }
}
