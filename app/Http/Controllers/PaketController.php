<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index(Request $request)
    {
        $query = Paket::withCount('jamaah');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%");
        }

        // Filter by jenis
        if ($request->filled('filter') && $request->filter !== 'semua') {
            $query->where('jenis', $request->filter);
        }

        $pakets = $query->orderBy('nama', 'asc')->paginate(15)->withQueryString();
        $total  = Paket::count();

        return view('paket.index', compact('pakets', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'                  => 'required|string|max:255',
            'jenis'                 => 'required|in:umroh,haji',
            'harga'                 => 'required|numeric|min:0',
            'status'                => 'required|in:aktif,nonaktif',
            'tanggal_keberangkatan' => 'required|date',
            'kuota'                 => 'nullable|integer|min:1',
        ], [
            'nama.required'                  => 'Nama paket wajib diisi.',
            'jenis.required'                 => 'Jenis paket wajib dipilih.',
            'harga.required'                 => 'Harga wajib diisi.',
            'harga.numeric'                  => 'Harga harus berupa angka.',
            'status.required'                => 'Status wajib dipilih.',
            'tanggal_keberangkatan.required' => 'Tanggal keberangkatan wajib diisi.',
        ]);

        // Clean harga: remove dots (Rp format)
        $validated['harga'] = (int) preg_replace('/[^0-9]/', '', $validated['harga']);

        Paket::create($validated);

        return redirect()->route('paket.index')
            ->with('success', 'Paket berhasil ditambahkan.');
    }

    public function update(Request $request, Paket $paket)
    {
        $validated = $request->validate([
            'nama'                  => 'required|string|max:255',
            'jenis'                 => 'required|in:umroh,haji',
            'harga'                 => 'required|numeric|min:0',
            'status'                => 'required|in:aktif,nonaktif',
            'tanggal_keberangkatan' => 'required|date',
            'kuota'                 => 'nullable|integer|min:1',
        ], [
            'nama.required'                  => 'Nama paket wajib diisi.',
            'jenis.required'                 => 'Jenis paket wajib dipilih.',
            'harga.required'                 => 'Harga wajib diisi.',
            'status.required'                => 'Status wajib dipilih.',
            'tanggal_keberangkatan.required' => 'Tanggal keberangkatan wajib diisi.',
        ]);

        $validated['harga'] = (int) preg_replace('/[^0-9]/', '', $validated['harga']);

        $paket->update($validated);

        return redirect()->route('paket.index')
            ->with('success', 'Data paket berhasil diperbarui.');
    }

    public function destroy(Paket $paket)
    {
        if ($paket->jamaah()->exists()) {
            return redirect()->route('paket.index')
                ->with('error', 'Paket tidak dapat dihapus karena masih memiliki jamaah terdaftar.');
        }

        $paket->delete();

        return redirect()->route('paket.index')
            ->with('success', 'Paket berhasil dihapus.');
    }
}