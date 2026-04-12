<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'jamaah_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode',
        'catatan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar'  => 'decimal:2',
    ];

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function getJumlahBayarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }
}