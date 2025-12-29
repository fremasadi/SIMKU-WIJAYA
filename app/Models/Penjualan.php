<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_penjualan',
        'total',
        'user_id',
    ];

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
}