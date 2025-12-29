<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelians';

    protected $fillable = [
        'pembelian_id',
        'bahan_baku_id',
        'jumlah',
        'harga',
        'subtotal',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relasi ke Pembelian
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    /**
     * Relasi ke Bahan Baku
     */
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }
}