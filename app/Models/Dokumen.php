<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = ['jamaah_id', 'jenis', 'file'];

    // Label display per jenis
    public static function labelJenis(): array
    {
        return [
            'ktp'       => 'KTP',
            'kk'        => 'Kartu Keluarga',
            'paspor'    => 'Paspor',
            'akta_lahir'=> 'Akta Lahir',
        ];
    }

    // Dokumen yang dibutuhkan per kategori usia
    public static function dokumenDibutuhkan(string $kategori): array
    {
        return match($kategori) {
            'dewasa' => ['ktp', 'kk', 'paspor'],
            'anak'   => ['akta_lahir', 'kk', 'paspor'],
            default  => [],
        };
    }

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }
}