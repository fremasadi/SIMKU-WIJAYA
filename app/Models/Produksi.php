<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $fillable = ['produk_id', 'kode_produksi', 'tanggal_produksi', 'jumlah_produksi'];

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

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->kode_produksi) {
                $lastId = self::max('id') + 1;

                $model->kode_produksi = 'SIMKUPRD-' . now()->format('Ymd') . '-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

}
