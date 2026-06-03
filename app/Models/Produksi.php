<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    public const STATUS_PROSES = 'proses';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_GAGAL = 'gagal';
    public const STATUS_BATAL = 'batal';

    protected $fillable = ['produk_id', 'kode_produksi', 'tanggal_produksi', 'jumlah_produksi', 'status'];

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

    public function getStatusLabelAttribute(): string
    {
        return [
            self::STATUS_PROSES => 'Proses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_GAGAL => 'Gagal',
            self::STATUS_BATAL => 'Batal',
        ][$this->status] ?? ucfirst((string) $this->status);
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
