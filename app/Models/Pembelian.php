<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians';

    protected $fillable = [
        'tanggal_pembelian',
        'total',
        'user_id',
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'total' => 'decimal:2',
    ];

    /**
     * Relasi ke User (pembuat transaksi)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Detail Pembelian
     * 1 pembelian memiliki banyak detail
     */
    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelian::class);
    }
}