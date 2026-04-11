<?php
namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\Dokumen;
use App\Models\Paket;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJamaah = Jamaah::count();

        $jamaahAll       = Jamaah::with(['paket', 'pembayaran', 'dokumen'])->get();
        $jamaahLunas     = $jamaahAll->filter(fn($j) => $j->status_pembayaran === 'lunas')->count();
        $jamaahBelumLunas = $totalJamaah - $jamaahLunas;

        $totalPembayaran = Pembayaran::sum('jumlah');

        $tunggakan = $jamaahAll
            ->filter(fn($j) => $j->status_pembayaran === 'belum_lunas')
            ->sum(fn($j) => $j->sisa_bayar);

        // Dokumen tidak lengkap
        $dokumenTidakLengkap = $jamaahAll->filter(fn($j) => !$j->dokumen_lengkap)->count();

        // Belum ada pembayaran sama sekali
        $belumAdaPembayaran = Jamaah::whereDoesntHave('pembayaran')->count();

        // Cicilan terakhir > 30 hari (punya pembayaran tapi sudah lama)
        $cicilan30Hari = Jamaah::whereHas('pembayaran')
            ->whereDoesntHave('pembayaran', fn($q) =>
                $q->where('tanggal', '>=', now()->subDays(30))
            )->count();

        $paketAktif = Paket::where('status', 'aktif')
            ->withCount('jamaah')
            ->orderByDesc('jamaah_count')
            ->get();

        return view('dashboard.index', compact(
            'totalJamaah', 'jamaahLunas', 'jamaahBelumLunas',
            'totalPembayaran', 'tunggakan',
            'dokumenTidakLengkap', 'belumAdaPembayaran', 'cicilan30Hari',
            'paketAktif'
        ));
    }
}