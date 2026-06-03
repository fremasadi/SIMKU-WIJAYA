<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyesuaianStokBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'penyesuaian_stok_bahan_bakus';

    protected $fillable = [
        'bahan_baku_id',
        'user_id',
        'tanggal',
        'jenis',
        'jumlah',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
