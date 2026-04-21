<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        $query = Mitra::withCount('jamaah');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kontak', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('filter') && $request->filter !== 'semua') {
            $query->where('status', $request->filter);
        }

        $mitras = $query->orderBy('nama', 'asc')->paginate(15)->withQueryString();
        $total  = Mitra::count();

        return view('mitra.index', compact('mitras', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'kontak' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama.required' => 'Nama mitra wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        // Normalize kontak: strip +62 prefix, store raw number
        if (!empty($validated['kontak'])) {
            $kontak = preg_replace('/[^0-9]/', '', $validated['kontak']);
            if (str_starts_with($kontak, '62')) {
                $kontak = '0' . substr($kontak, 2);
            }
            $validated['kontak'] = $kontak;
        }

        Mitra::create($validated);

        return redirect()->route('mitra.index')
            ->with('success', 'Mitra berhasil ditambahkan.');
    }

    public function show(Mitra $mitra)
    {
        $mitra->loadCount('jamaah');
        $jamaah = $mitra->jamaah()
            ->with(['paket'])
            ->orderBy('nama_lengkap', 'asc')
            ->paginate(15);

        return view('mitra.show', compact('mitra', 'jamaah'));
    }

    public function update(Request $request, Mitra $mitra)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'kontak' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama.required' => 'Nama mitra wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        // Normalize kontak
        if (!empty($validated['kontak'])) {
            $kontak = preg_replace('/[^0-9]/', '', $validated['kontak']);
            if (str_starts_with($kontak, '62')) {
                $kontak = '0' . substr($kontak, 2);
            }
            $validated['kontak'] = $kontak;
        }

        $mitra->update($validated);

        return redirect()->route('mitra.index')
            ->with('success', 'Data mitra berhasil diperbarui.');
    }

    public function destroy(Mitra $mitra)
    {
        // Check if mitra has jamaah
        if ($mitra->jamaah()->exists()) {
            return redirect()->route('mitra.index')
                ->with('error', 'Mitra tidak dapat dihapus karena masih memiliki jamaah terdaftar.');
        }

        $mitra->delete();

        return redirect()->route('mitra.index')
            ->with('success', 'Mitra berhasil dihapus.');
    }
}