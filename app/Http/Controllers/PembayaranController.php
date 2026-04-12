<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /**
     * Store a new pembayaran for a jamaah.
     */
    public function store(Request $request, Jamaah $jamaah)
    {
        $validated = $request->validate([
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar'  => 'required|numeric|min:1000',
            'metode'        => 'required|in:Transfer Bank,Tunai,QRIS,Lainnya',
            'catatan'       => 'nullable|string|max:255',
        ]);

        $validated['jamaah_id'] = $jamaah->id;

        Pembayaran::create($validated);

        return redirect()->route('jamaah.show', $jamaah)
            ->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Update pembayaran.
     */
    public function update(Request $request, Jamaah $jamaah, Pembayaran $pembayaran)
    {
        abort_if($pembayaran->jamaah_id !== $jamaah->id, 403);

        $validated = $request->validate([
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar'  => 'required|numeric|min:1000',
            'metode'        => 'required|in:Transfer Bank,Tunai,QRIS,Lainnya',
            'catatan'       => 'nullable|string|max:255',
        ]);

        $pembayaran->update($validated);

        return redirect()->route('jamaah.show', $jamaah)
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    /**
     * Delete pembayaran.
     */
    public function destroy(Jamaah $jamaah, Pembayaran $pembayaran)
    {
        abort_if($pembayaran->jamaah_id !== $jamaah->id, 403);

        $pembayaran->delete();

        return redirect()->route('jamaah.show', $jamaah)
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
}