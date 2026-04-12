<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Jamaah;
use App\Models\Mitra;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JamaahController extends Controller
{
    public function index(Request $request)
    {
        $query = Jamaah::with(['paket', 'mitra', 'pembayaran', 'dokumen']);

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('paket_id')) {
            $query->where('paket_id', $request->paket_id);
        }

        if ($request->filled('jenis_jamaah')) {
            $query->where('jenis_jamaah', $request->jenis_jamaah);
        }

        if ($request->filled('status_jamaah')) {
            $query->where('status_jamaah', $request->status_jamaah);
        }

        $jamaah = $query->latest()->paginate(15)->withQueryString();

        // Post-filter status pembayaran
        if ($request->filled('status_pembayaran')) {
            $statusFilter = $request->status_pembayaran;
            $filtered = $jamaah->getCollection()->filter(
                fn($j) => $j->status_pembayaran === $statusFilter
            );
            $jamaah->setCollection($filtered);
        }

        $pakets = Paket::orderBy('nama_paket')->get();
        $mitras = Mitra::where('status', 'Aktif')->orderBy('nama_mitra')->get();
        $total  = Jamaah::count();

        return view('jamaah.index', compact('jamaah', 'pakets', 'mitras', 'total'));
    }

    public function create()
    {
        $pakets = Paket::where('status', 'Aktif')->orderBy('nama_paket')->get();
        $mitras = Mitra::where('status', 'Aktif')->orderBy('nama_mitra')->get();
        return view('jamaah.create', compact('pakets', 'mitras'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'kontak'        => 'nullable|string|max:20',
            'paket_id'      => 'required|exists:pakets,id',
            'jenis_jamaah'  => 'required|in:Mandiri,Mitra',
            'mitra_id'      => 'nullable|exists:mitras,id',
            'status_jamaah' => 'required|in:Akan Berangkat,Selesai',
            'kategori_usia' => 'required|in:Dewasa,Anak-anak',
            'foto_profil'   => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            // Validasi dokumen dinamis berdasarkan kategori
            'dokumen.*'     => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        if ($request->jenis_jamaah === 'Mandiri') {
            $validated['mitra_id'] = null;
        }

        DB::transaction(function () use ($request, $validated) {
            // Upload foto profil
            if ($request->hasFile('foto_profil')) {
                $validated['foto_profil'] = $request->file('foto_profil')
                    ->store('jamaah/foto_profil', 'public');
            }

            $jamaah = Jamaah::create($validated);

            // Upload dokumen ke tabel dokumen
            $jenisDokumen = Dokumen::getDokumenByKategori($request->kategori_usia);

            foreach ($jenisDokumen as $jenis) {
                $key = 'dokumen_' . strtolower(str_replace([' ', '-'], '_', $jenis));
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $path = $file->store("jamaah/dokumen/{$jamaah->id}", 'public');

                    Dokumen::create([
                        'jamaah_id'     => $jamaah->id,
                        'jenis_dokumen' => $jenis,
                        'file_path'     => $path,
                        'nama_file'     => $file->getClientOriginalName(),
                    ]);
                }
            }
        });

        return redirect()->route('jamaah.index')
            ->with('success', 'Data jamaah berhasil ditambahkan.');
    }

    public function show(Jamaah $jamaah)
    {
        $jamaah->load(['paket', 'mitra', 'dokumen']);
        $pembayaran = $jamaah->pembayaran()->orderBy('tanggal_bayar', 'desc')->paginate(10);

        // Susun dokumen berdasarkan kategori usia
        $dokumenDiperlukan = Dokumen::getDokumenByKategori($jamaah->kategori_usia);
        $dokumenMap = $jamaah->dokumen->keyBy('jenis_dokumen');

        return view('jamaah.show', compact('jamaah', 'pembayaran', 'dokumenDiperlukan', 'dokumenMap'));
    }

    public function edit(Jamaah $jamaah)
    {
        $pakets = Paket::where('status', 'Aktif')->orderBy('nama_paket')->get();
        $mitras = Mitra::where('status', 'Aktif')->orderBy('nama_mitra')->get();
        $dokumenMap = $jamaah->dokumen->keyBy('jenis_dokumen');
        return view('jamaah.edit', compact('jamaah', 'pakets', 'mitras', 'dokumenMap'));
    }

    public function update(Request $request, Jamaah $jamaah)
    {
        $validated = $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'kontak'        => 'nullable|string|max:20',
            'paket_id'      => 'required|exists:pakets,id',
            'jenis_jamaah'  => 'required|in:Mandiri,Mitra',
            'mitra_id'      => 'nullable|exists:mitras,id',
            'status_jamaah' => 'required|in:Akan Berangkat,Selesai',
            'kategori_usia' => 'required|in:Dewasa,Anak-anak',
            'foto_profil'   => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'dokumen.*'     => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        if ($request->jenis_jamaah === 'Mandiri') {
            $validated['mitra_id'] = null;
        }

        DB::transaction(function () use ($request, $validated, $jamaah) {
            // Upload foto profil baru (jika ada)
            if ($request->hasFile('foto_profil')) {
                if ($jamaah->foto_profil) {
                    Storage::disk('public')->delete($jamaah->foto_profil);
                }
                $validated['foto_profil'] = $request->file('foto_profil')
                    ->store('jamaah/foto_profil', 'public');
            } else {
                unset($validated['foto_profil']);
            }

            $jamaah->update($validated);

            // Jika kategori usia berubah, hapus dokumen lama yang tidak relevan
            $jenisDiperlukan = Dokumen::getDokumenByKategori($jamaah->kategori_usia);

            // Hapus dokumen yang tidak lagi diperlukan
            $jamaah->dokumen()
                ->whereNotIn('jenis_dokumen', $jenisDiperlukan)
                ->each(function ($dok) {
                    $dok->deleteFile();
                    $dok->delete();
                });

            // Upload/replace dokumen baru
            foreach ($jenisDiperlukan as $jenis) {
                $key = 'dokumen_' . strtolower(str_replace([' ', '-'], '_', $jenis));
                if ($request->hasFile($key)) {
                    $file = $request->file($key);

                    // Hapus file lama jika ada
                    $existing = $jamaah->dokumen->firstWhere('jenis_dokumen', $jenis);
                    if ($existing) {
                        $existing->deleteFile();
                        $existing->delete();
                    }

                    $path = $file->store("jamaah/dokumen/{$jamaah->id}", 'public');
                    Dokumen::create([
                        'jamaah_id'     => $jamaah->id,
                        'jenis_dokumen' => $jenis,
                        'file_path'     => $path,
                        'nama_file'     => $file->getClientOriginalName(),
                    ]);
                }
            }
        });

        return redirect()->route('jamaah.show', $jamaah)
            ->with('success', 'Data jamaah berhasil diperbarui.');
    }

    public function destroy(Jamaah $jamaah)
    {
        DB::transaction(function () use ($jamaah) {
            // Hapus semua file dokumen
            $jamaah->dokumen->each(fn($d) => $d->deleteFile());

            // Hapus foto profil
            if ($jamaah->foto_profil) {
                Storage::disk('public')->delete($jamaah->foto_profil);
            }

            $jamaah->delete(); // cascade hapus dokumen & pembayaran di DB
        });

        return redirect()->route('jamaah.index')
            ->with('success', 'Data jamaah berhasil dihapus.');
    }

    /**
     * Upload AJAX satu dokumen (dari halaman detail).
     * POST /jamaah/{jamaah}/upload-dokumen
     */
    public function uploadDokumen(Request $request, Jamaah $jamaah)
    {
        $request->validate([
            'jenis_dokumen' => 'required|in:KTP,KK,Paspor,Akta Kelahiran',
            'file'          => 'required|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        // Pastikan jenis dokumen sesuai kategori usia jamaah
        $diperbolehkan = Dokumen::getDokumenByKategori($jamaah->kategori_usia);
        if (!in_array($request->jenis_dokumen, $diperbolehkan)) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis dokumen tidak sesuai kategori usia jamaah.',
            ], 422);
        }

        // Hapus dokumen lama jika ada
        $existing = $jamaah->dokumen()->where('jenis_dokumen', $request->jenis_dokumen)->first();
        if ($existing) {
            $existing->deleteFile();
            $existing->delete();
        }

        $file = $request->file('file');
        $path = $file->store("jamaah/dokumen/{$jamaah->id}", 'public');

        $dokumen = Dokumen::create([
            'jamaah_id'     => $jamaah->id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_path'     => $path,
            'nama_file'     => $file->getClientOriginalName(),
        ]);

        return response()->json([
            'success' => true,
            'url'     => Storage::url($path),
            'message' => "{$request->jenis_dokumen} berhasil diupload.",
        ]);
    }

    /**
     * Upload foto profil AJAX.
     */
    public function uploadFoto(Request $request, Jamaah $jamaah)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        if ($jamaah->foto_profil) {
            Storage::disk('public')->delete($jamaah->foto_profil);
        }

        $path = $request->file('file')->store('jamaah/foto_profil', 'public');
        $jamaah->update(['foto_profil' => $path]);

        return response()->json([
            'success' => true,
            'url'     => Storage::url($path),
        ]);
    }

    /**
     * Hapus satu dokumen (KTP/KK/Paspor/Akta) milik jamaah.
     * DELETE /jamaah/{jamaah}/hapus-dokumen
     */
    public function hapusDokumen(Request $request, Jamaah $jamaah)
    {
        $request->validate([
            'jenis_dokumen' => 'required|in:KTP,KK,Paspor,Akta Kelahiran',
        ]);
    
        $dokumen = $jamaah->dokumen()
            ->where('jenis_dokumen', $request->jenis_dokumen)
            ->first();
    
        if (!$dokumen) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak ditemukan.',
            ], 404);
        }
    
        $dokumen->deleteFile();
        $dokumen->delete();
    
        return response()->json([
            'success' => true,
            'message' => "{$request->jenis_dokumen} berhasil dihapus.",
        ]);
    }
    
    /**
     * Hapus foto profil jamaah.
     * DELETE /jamaah/{jamaah}/hapus-foto
     */
    public function hapusFoto(Jamaah $jamaah)
    {
        if (!$jamaah->foto_profil) {
            return response()->json([
                'success' => false,
                'message' => 'Foto profil tidak ditemukan.',
            ], 404);
        }
    
        Storage::disk('public')->delete($jamaah->foto_profil);
        $jamaah->update(['foto_profil' => null]);
    
        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus.',
        ]);
    }

    /**
     * Cetak invoice (print view).
     */
    public function invoice(Jamaah $jamaah)
    {
        $jamaah->load([
            'paket',
            'mitra',
            'pembayaran' => fn($q) => $q->orderBy('tanggal_bayar'),
        ]);
    
        // Nomor invoice: urut per tahun, format 01//ETT/bulanRomawi/tahun
        $nomorUrut   = str_pad($jamaah->id, 2, '0', STR_PAD_LEFT);
        $bulanRomawi = $this->toRoman(now()->month);
        $tahun       = now()->year;
        $nomorInvoice = "{$nomorUrut}//ETT/{$bulanRomawi}/{$tahun}";
    
        return view('jamaah.invoice', compact('jamaah', 'nomorInvoice'));
    }
 
    /**
     * Konversi angka bulan ke angka Romawi.
     */
    private function toRoman(int $bulan): string
    {
        $roman = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
        return $roman[$bulan - 1] ?? (string) $bulan;
    }
}