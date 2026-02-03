<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = ['kode_penjualan', 'tanggal_penjualan', 'total', 'user_id'];

    protected $casts = [
        'tanggal_penjualan' => 'date',
        'total' => 'decimal:2',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke DetailPenjualan
     */
    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($penjualan) {
            $last = self::orderBy('id', 'desc')->first();

            if (!$last) {
                $number = 1;
            } else {
                // ambil angka terakhir
                $lastNumber = (int) substr($last->kode_penjualan, -5);
                $number = $lastNumber + 1;
            }

            $penjualan->kode_penjualan = 'INV-SK-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }
}
