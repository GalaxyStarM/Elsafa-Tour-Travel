<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\Mitra;
use App\Models\Paket;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── STAT CARDS ──────────────────────────────────────────
        $totalJamaah = Jamaah::count();

        // Lunas: total bayar >= harga paket
        $jamaahLunas = Jamaah::with(['paket', 'pembayaran'])
            ->get()
            ->filter(fn($j) => $j->status_pembayaran === 'Lunas')
            ->count();

        $jamaahBelumLunas = $totalJamaah - $jamaahLunas;

        // ── RINGKASAN PEMBAYARAN ─────────────────────────────────
        $totalPembayaran  = Pembayaran::sum('jumlah_bayar');

        // Tunggakan: total harga paket - total yang sudah dibayar
        $totalHargaPaket  = Jamaah::join('pakets', 'jamaah.paket_id', '=', 'pakets.id')
            ->sum('pakets.harga');
        $tunggakan = max(0, $totalHargaPaket - $totalPembayaran);

        // Progress bar persentase
        $progressPersen = $totalHargaPaket > 0
            ? min(100, round(($totalPembayaran / $totalHargaPaket) * 100))
            : 0;

        // ── ALERT PERHATIAN ──────────────────────────────────────

        // 1. Dokumen tidak lengkap
        $dokumenTidakLengkap = Jamaah::with('dokumen')
            ->get()
            ->filter(fn($j) => !$j->dokumen_lengkap)
            ->count();

        // 2. Belum ada pembayaran sama sekali
        $belumAdaPembayaran = Jamaah::doesntHave('pembayaran')->count();

        // 3. Cicilan terakhir > 30 hari yang lalu
        $cicilan30Hari = Jamaah::whereHas('pembayaran', function ($q) {
            // Ada pembayaran, tapi pembayaran terakhirnya > 30 hari lalu
            $q->where('tanggal_bayar', '>=', now()->subDays(30));
        }, '<', 1)
        ->whereHas('pembayaran') // pastikan ada pembayaran (bukan belum bayar sama sekali)
        ->whereHas('paket')
        ->get()
        ->filter(fn($j) => $j->status_pembayaran !== 'Lunas') // hanya yang belum lunas
        ->count();

        // ── PAKET AKTIF ──────────────────────────────────────────
        $paketAktif = Paket::where('status', 'Aktif')
            ->withCount('jamaah')
            ->orderBy('jamaah_count', 'desc')
            ->get();

        return view('dashboard.index', compact(
            'totalJamaah',
            'jamaahLunas',
            'jamaahBelumLunas',
            'totalPembayaran',
            'tunggakan',
            'progressPersen',
            'dokumenTidakLengkap',
            'belumAdaPembayaran',
            'cicilan30Hari',
            'paketAktif',
        ));
    }
}