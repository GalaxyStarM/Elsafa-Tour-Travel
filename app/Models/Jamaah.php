<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jamaah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jamaah';

    protected $fillable = [
        'nama_lengkap', 'kontak', 'paket_id', 'jenis_jamaah',
        'mitra_id', 'status_jamaah', 'kategori_usia', 'foto'
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class);
    }

    // Ambil dokumen tertentu, misal ->getDokumen('ktp')
    public function getDokumen(string $jenis): ?Dokumen
    {
        return $this->dokumen->firstWhere('jenis', $jenis);
    }

    // Cek kelengkapan dokumen berdasarkan kategori usia
    public function getDokumenLengkapAttribute(): bool
    {
        $dibutuhkan = Dokumen::dokumenDibutuhkan($this->kategori_usia);
        $dimiliki   = $this->dokumen->pluck('jenis')->toArray();
        return empty(array_diff($dibutuhkan, $dimiliki));
    }

    // Accessor pembayaran
    public function getTotalBayarAttribute(): int
    {
        return (int) $this->pembayaran->sum('jumlah');
    }

    public function getSisaBayarAttribute(): int
    {
        $harga = $this->paket?->harga ?? 0;
        return max(0, $harga - $this->total_bayar);
    }

    public function getStatusPembayaranAttribute(): string
    {
        if (!$this->paket) return 'belum_lunas';
        return $this->sisa_bayar <= 0 ? 'lunas' : 'belum_lunas';
    }
}