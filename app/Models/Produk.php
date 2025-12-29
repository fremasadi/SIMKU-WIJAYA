<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = [
        'nama_produk',
        'stok',
        'satuan',
    ];

    protected $casts = [
        'stok' => 'decimal:2',
    ];

    /**
     * Relasi ke Produksi
     * 1 produk bisa diproduksi berkali-kali
     */
    public function produksis()
    {
        return $this->hasMany(Produksi::class, 'produk_id');
    }
}