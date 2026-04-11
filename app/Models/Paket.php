<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama', 'jenis', 'deskripsi', 'harga',
        'tanggal_berangkat', 'tanggal_kembali', 'kuota', 'status'
    ];

    protected $casts = [
        'tanggal_berangkat' => 'date',
        'tanggal_kembali'   => 'date',
    ];

    public function jamaah()
    {
        return $this->hasMany(Jamaah::class);
    }
}