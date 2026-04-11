<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembayaran';

    protected $fillable = ['jamaah_id', 'tanggal', 'jumlah', 'metode', 'catatan'];

    protected $casts = ['tanggal' => 'date'];

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }
}