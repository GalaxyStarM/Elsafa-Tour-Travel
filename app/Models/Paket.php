<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'jenis',
        'harga',
        'status',
        'tanggal_keberangkatan',
        'kuota',
    ];

    protected $casts = [
        'tanggal_keberangkatan' => 'date',
        'harga' => 'integer',
        'kuota' => 'integer',
    ];

    // Relationship: Paket has many Jamaah
    // public function jamaah()
    // {
    //     return $this->hasMany(Jamaah::class);
    // }

    // Count jamaah
    public function getJumlahJamaahAttribute()
    {
        return $this->jamaah()->count();
    }

    // Scope: aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Format harga to Rupiah
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Format tanggal
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal_keberangkatan
            ? $this->tanggal_keberangkatan->format('d M Y')
            : '-';
    }
}