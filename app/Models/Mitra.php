<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mitra extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'kontak',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relationship: Mitra has many Jamaah
    // public function jamaah()
    // {
    //     return $this->hasMany(Jamaah::class);
    // }

    // Count jamaah
    public function getJumlahJamaahAttribute()
    {
        return $this->jamaah()->count();
    }

    // Scope: aktif only
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Format kontak: strip leading 0, add +62
    public function getKontakFormattedAttribute()
    {
        if (!$this->kontak) return null;
        $number = ltrim($this->kontak, '0');
        return '+62' . $number;
    }
}