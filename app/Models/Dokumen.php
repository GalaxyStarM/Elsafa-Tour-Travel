<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Model
{
    protected $table = 'dokumen';

    protected $fillable = [
        'jamaah_id',
        'jenis_dokumen',
        'file_path',
        'nama_file',
    ];

    // Jenis dokumen yang dibutuhkan berdasarkan kategori usia
    public const DOKUMEN_DEWASA = ['KTP', 'KK', 'Paspor'];
    public const DOKUMEN_ANAK   = ['Akta Kelahiran', 'KK', 'Paspor'];

    public static function getDokumenByKategori(string $kategori): array
    {
        return $kategori === 'Anak-anak'
            ? self::DOKUMEN_ANAK
            : self::DOKUMEN_DEWASA;
    }

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function deleteFile(): void
    {
        Storage::disk('public')->delete($this->file_path);
    }
}