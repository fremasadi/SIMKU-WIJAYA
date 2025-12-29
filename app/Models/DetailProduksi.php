<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProduksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'produksi_id',
        'bahan_baku_id',
        'jumlah_bahan',
    ];

    protected $casts = [
        'jumlah_bahan' => 'decimal:2',
    ];

    /**
     * Relasi ke Produksi
     */
    public function produksi()
    {
        return $this->belongsTo(Produksi::class);
    }

    /**
     * Relasi ke BahanBaku
     */
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }
}