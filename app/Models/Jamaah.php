<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    use HasFactory;

    protected $table = 'jamaah';

    protected $fillable = [
        'nama_lengkap',
        'kontak',
        'paket_id',
        'jenis_jamaah',
        'mitra_id',
        'status_jamaah',
        'kategori_usia',
        'foto_profil',
    ];

    // ──────────────────────────────────────────
    // RELATIONS
    // ──────────────────────────────────────────

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

    /**
     * Semua dokumen jamaah (KTP/KK/Paspor/Akta).
     */
    public function dokumen()
    {
        return $this->hasMany(Dokumen::class);
    }

    /**
     * Ambil dokumen berdasarkan jenisnya.
     * Contoh: $jamaah->getDokumen('KTP')
     */
    public function getDokumen(string $jenis): ?Dokumen
    {
        return $this->dokumen->firstWhere('jenis_dokumen', $jenis);
    }

    // ──────────────────────────────────────────
    // COMPUTED ATTRIBUTES
    // ──────────────────────────────────────────

    /**
     * Daftar jenis dokumen yang diperlukan sesuai kategori usia.
     */
    public function getDokumenDiperlukanAttribute(): array
    {
        return Dokumen::getDokumenByKategori($this->kategori_usia);
    }

    /**
     * Cek apakah semua dokumen yang diperlukan sudah diupload.
     */
    public function getDokumenLengkapAttribute(): bool
    {
        $diperlukan  = $this->dokumen_diperlukan;
        $sudahAda    = $this->dokumen->pluck('jenis_dokumen')->toArray();

        return empty(array_diff($diperlukan, $sudahAda));
    }

    /**
     * Status pembayaran otomatis berdasarkan total bayar vs harga paket.
     */
    public function getStatusPembayaranAttribute(): string
    {
        if (!$this->paket) return 'Belum Lunas';
        $total = $this->pembayaran->sum('jumlah_bayar');
        return $total >= $this->paket->harga ? 'Lunas' : 'Belum Lunas';
    }

    public function getTotalPembayaranAttribute(): float
    {
        return (float) $this->pembayaran->sum('jumlah_bayar');
    }

    public function getKontakFormattedAttribute(): ?string
    {
        if (!$this->kontak) return null;
        $no = preg_replace('/\D/', '', $this->kontak);
        if (str_starts_with($no, '0')) {
            $no = '62' . substr($no, 1);
        }
        return '+' . $no;
    }
}