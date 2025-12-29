<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjualan_id',
        'nama_produk',
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
     * Relasi ke Penjualan
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}