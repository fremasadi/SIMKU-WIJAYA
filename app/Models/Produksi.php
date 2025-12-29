<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_id',
        'tanggal_produksi',
        'jumlah_produksi',
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
        'jumlah_produksi' => 'decimal:2',
    ];

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Relasi ke DetailProduksi
     */
    public function detailProduksis()
    {
        return $this->hasMany(DetailProduksi::class);
    }
}