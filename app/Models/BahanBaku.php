<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    public const BATAS_STOK_MENIPIS = 50;

    protected $table = 'bahan_bakus';

    protected $fillable = [
        'nama_bahan',
        'satuan',
        'stok',
        'harga_satuan',
        'keterangan',
    ];

    protected $casts = [
        'stok' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
    ];

    /**
     * Scope bahan baku dengan stok menipis.
     */
    public function scopeStokMenipis(Builder $query): Builder
    {
        return $query->where('stok', '<=', self::BATAS_STOK_MENIPIS);
    }

    public function getStokMenipisAttribute(): bool
    {
        return (float) $this->stok <= self::BATAS_STOK_MENIPIS;
    }

    /**
     * Relasi ke Detail Pembelian
     * 1 bahan baku bisa muncul di banyak pembelian
     */
    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelian::class, 'bahan_baku_id');
    }

    /**
     * Relasi ke Detail Produksi
     * 1 bahan baku bisa dipakai di banyak produksi
     */
    public function detailProduksis()
    {
        return $this->hasMany(DetailProduksi::class, 'bahan_baku_id');
    }

}
