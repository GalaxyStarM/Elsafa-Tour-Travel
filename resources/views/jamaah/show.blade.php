{{-- resources/views/jamaah/show.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Detail Jamaah')

@section('content')

{{-- BARIS ATAS: Profile Card + Dokumen Card --}}
<div class="row g-3 mb-3">

    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="card-elsafa p-4 h-100 text-center d-flex flex-column align-items-center justify-content-center"
             style="position:relative">

            <a href="{{ route('jamaah.edit', $jamaah) }}"
               style="position:absolute;top:12px;right:12px;width:30px;height:30px;border-radius:50%;
                      background:#f1f5f9;border:1.5px solid #e2e8f0;display:flex;align-items:center;
                      justify-content:center;color:#374151;font-size:13px;text-decoration:none"
               title="Edit Jamaah">
                <i class="bi bi-pencil"></i>
            </a>

            <div class="photo-wrap" style="margin-bottom:10px">
                <div class="photo-avatar" id="photoPreview">
                    @if($jamaah->foto_profil)
                        <img src="{{ Storage::url($jamaah->foto_profil) }}"
                             style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                    @else
                        <i class="bi bi-person-fill" style="font-size:42px;color:#9ca3af"></i>
                    @endif
                </div>
                <label for="fotoInput" class="photo-camera-btn" title="Ganti Foto">
                    <i class="bi bi-camera-fill"></i>
                </label>
                <input type="file" id="fotoInput" hidden
                       accept="image/jpeg,image/jpg,image/png" onchange="uploadFoto(this)">
            </div>

            <h6 class="fw-700 mb-1" style="font-size:15px;color:#1e2d4a">
                {{ $jamaah->nama_lengkap }}
            </h6>

            <p style="font-size:12.5px;color:#6c757d;margin-bottom:10px">
                {{ $jamaah->kontak ? '+62 ' . $jamaah->kontak : '-' }}
            </p>

            <span class="{{ $jamaah->jenis_jamaah === 'Mandiri' ? 'badge-mandiri' : 'badge-mitra' }}"
                  style="margin-bottom:12px">
                {{ $jamaah->jenis_jamaah }}
            </span>

            <div class="d-flex flex-column gap-2 w-100 px-2">
                <span style="background:#f3f4f6;color:#374151;font-size:12px;font-weight:500;
                             padding:5px 10px;border-radius:20px;text-align:center">
                    {{ $jamaah->paket->nama ?? '-' }}
                </span>
                <span style="background:#f3f4f6;color:#374151;font-size:12px;font-weight:500;
                             padding:5px 10px;border-radius:20px;text-align:center">
                    {{ $jamaah->status_jamaah }}
                </span>
            </div>
        </div>
    </div>

    {{-- Dokumen Card --}}
    <div class="col-lg-8">
        <div class="card-elsafa p-4 h-100">
            <ul class="nav doc-tabs mb-3">
                @foreach($dokumenDiperlukan as $i => $jenis)
                <li class="nav-item">
                    <a class="nav-link {{ $i === 0 ? 'active' : '' }}"
                       data-bs-toggle="tab" href="#tab{{ $i }}">
                        {{ $jenis }}
                        @if(isset($dokumenMap[$jenis]))
                            <i class="bi bi-check-circle-fill ms-1" style="font-size:10px;color:#198754"></i>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($dokumenDiperlukan as $i => $jenis)
                @php $dok = $dokumenMap[$jenis] ?? null; @endphp
                <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tab{{ $i }}">
                    @if($dok)
                        <img src="{{ Storage::url($dok->file_path) }}"
                             style="max-width:100%;max-height:240px;object-fit:contain;border-radius:8px;
                                    border:1.5px solid #e5e7eb;display:block;margin-bottom:10px">
                        <div class="d-flex gap-2">
                            <label class="btn-elsafa-outline"
                                   style="font-size:12px;padding:5px 12px;cursor:pointer">
                                <i class="bi bi-arrow-repeat"></i> Ganti
                                <input type="file" hidden accept="image/jpeg,image/jpg,image/png"
                                       onchange="uploadDokumen(this, '{{ $jenis }}')">
                            </label>
                            <button type="button" onclick="hapusDokumen('{{ $jenis }}')"
                                    class="btn-act btn-act-del"
                                    style="width:auto;padding:5px 10px;font-size:12px;border-radius:6px">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    @else
                        <div class="upload-zone" onclick="this.nextElementSibling.click()">
                            <i class="bi bi-cloud-upload"
                               style="font-size:36px;color:#94a3b8;display:block;margin-bottom:8px"></i>
                            <p style="font-size:14px;font-weight:600;color:#374151;margin-bottom:4px">
                                Choose a file or drag &amp; drop it here
                            </p>
                            <small style="font-size:12px;color:#9ca3af">
                                JPEG, JPG, and PNG formats, up to 5MB
                            </small>
                            <div style="margin-top:12px">
                                <span class="browse-btn">Browse</span>
                            </div>
                        </div>
                        <input type="file" hidden accept="image/jpeg,image/jpg,image/png"
                               onchange="uploadDokumen(this, '{{ $jenis }}')">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- BARIS BAWAH: Riwayat Pembayaran --}}
<div class="card-elsafa">

    <div class="d-flex align-items-center justify-content-between px-4 py-3"
         style="background:#fdf8ee;border-bottom:1px solid #f0e8cc">
        <h6 class="fw-700 mb-0" style="font-size:15px;color:#1e2d4a">Riwayat Pembayaran</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('jamaah.invoice', $jamaah) }}" target="_blank"
               class="btn-elsafa-outline" style="font-size:12.5px;padding:6px 12px">
                <i class="bi bi-printer"></i> Cetak Invoice
            </a>
            <button onclick="openAddModal()" class="btn-elsafa" style="font-size:12.5px;padding:6px 12px">
                <i class="bi bi-plus-lg"></i> Tambah Pembayaran
            </button>
        </div>
    </div>

    @php
        $totalPaket = $jamaah->paket->harga ?? 0;
        $sudahBayar = $jamaah->total_pembayaran ?? 0;
        $sisaBayar  = max(0, $totalPaket - $sudahBayar);
    @endphp
    <div class="d-flex gap-4 flex-wrap px-4 py-3" style="background:#fafafa;border-bottom:1px solid #f0f2f5">
        <div>
            <div style="font-size:12px;color:#6c757d;margin-bottom:3px">Total Paket</div>
            <div class="fw-700" style="font-size:14px;color:#1e2d4a">
                Rp {{ number_format($totalPaket, 0, ',', '.') }}
            </div>
        </div>
        <div>
            <div style="font-size:12px;color:#6c757d;margin-bottom:3px">Sudah Dibayar</div>
            <div class="fw-700" style="font-size:14px;color:#198754">
                Rp {{ number_format($sudahBayar, 0, ',', '.') }}
            </div>
        </div>
        <div>
            <div style="font-size:12px;color:#6c757d;margin-bottom:3px">Sisa</div>
            <div class="fw-700" style="font-size:14px;color:{{ $sisaBayar > 0 ? '#dc3545' : '#198754' }}">
                Rp {{ number_format($sisaBayar, 0, ',', '.') }}
            </div>
        </div>
        <div class="ms-auto d-flex align-items-center">
            <span class="bdg {{ $jamaah->status_pembayaran === 'Lunas' ? 'bdg-lunas' : 'bdg-belum' }}">
                {{ $jamaah->status_pembayaran }}
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-elsafa">
            <thead>
                <tr>
                    <th style="width:50px">NO.</th>
                    <th>Tanggal</th>
                    <th>Jumlah Bayar</th>
                    <th>Metode</th>
                    <th>Catatan</th>
                    <th class="text-center" style="width:80px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayaran as $i => $p)
                <tr>
                    <td class="text-center" style="color:#adb5bd;font-weight:600">
                        {{ ($pembayaran->currentPage()-1)*$pembayaran->perPage()+$i+1 }}.
                    </td>
                    <td>{{ $p->tanggal_bayar->format('d-m-Y') }}</td>
                    <td class="fw-700" style="color:#1e2d4a">
                        Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}
                    </td>
                    <td style="font-size:13px">{{ $p->metode }}</td>
                    <td style="font-size:13px;color:#6c757d">{{ $p->catatan ?? '-' }}</td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <button class="btn-act btn-act-edit" title="Edit"
                                    onclick="openEditModal({{ $p->id }},'{{ $p->tanggal_bayar->format('Y-m-d') }}',{{ $p->jumlah_bayar }},'{{ $p->metode }}','{{ addslashes($p->catatan ?? '') }}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn-act btn-act-del" title="Hapus"
                                    onclick="confirmDeletePembayaran({{ $p->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5" style="color:#adb5bd">
                        <i class="bi bi-inbox d-block mb-2" style="font-size:2rem"></i>
                        Belum ada pembayaran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex align-items-center justify-content-between px-4 py-3"
         style="border-top:1px solid #f0f2f5">
        <span style="font-size:13px;color:#6c757d">
            Menampilkan {{ $pembayaran->firstItem() ?? 0 }} entri dari {{ $pembayaran->total() }} entri
        </span>
        <div class="d-flex gap-1">
            @if($pembayaran->onFirstPage())
                <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
            @else
                <a href="{{ $pembayaran->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
            @endif
            @foreach($pembayaran->getUrlRange(1, $pembayaran->lastPage()) as $page => $url)
                @if($page==1 || $page==$pembayaran->lastPage() || abs($page-$pembayaran->currentPage())<=1)
                    <a href="{{ $url }}" class="page-btn {{ $page==$pembayaran->currentPage()?'active':'' }}">{{ $page }}</a>
                @elseif(abs($page-$pembayaran->currentPage())==2)
                    <span class="page-btn" style="pointer-events:none">...</span>
                @endif
            @endforeach
            @if($pembayaran->hasMorePages())
                <a href="{{ $pembayaran->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
            @else
                <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
            @endif
        </div>
    </div>
</div>

{{-- MODAL: Tambah Pembayaran --}}
<div class="modal fade" id="addPayModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header-gold px-4 py-3">
                <h6 class="modal-title-gold mb-0">Tambah Pembayaran</h6>
            </div>
            <form method="POST" action="{{ route('jamaah.pembayaran.store', $jamaah) }}">
                @csrf
                <div class="px-4 pt-4 pb-2">
                    <div class="mb-3">
                        <label class="form-label-elsafa">Tanggal Pembayaran <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <i class="bi bi-calendar3 position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none"></i>
                            <input type="date" name="tanggal_bayar" class="form-control-elsafa" style="padding-left:36px" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-elsafa">Jumlah Bayar <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_bayar" class="form-control-elsafa" placeholder="Masukkan Nominal" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-elsafa">Metode <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <select name="metode" class="form-control-elsafa" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Tunai">Tunai</option>
                            </select>
                            <i class="bi bi-chevron-down position-absolute" style="right:12px;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-elsafa">Catatan</label>
                        <input type="text" name="catatan" class="form-control-elsafa" placeholder="Tambahkan catatan (opsional)">
                    </div>
                </div>
                <div class="px-4 pb-4 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn-elsafa px-4">Simpan</button>
                    <button type="button" class="btn-elsafa-outline px-4" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: Edit Pembayaran --}}
<div class="modal fade" id="editPayModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header-gold px-4 py-3">
                <h6 class="modal-title-gold mb-0">Edit Pembayaran</h6>
            </div>
            <form method="POST" id="editPayForm">
                @csrf @method('PUT')
                <div class="px-4 pt-4 pb-2">
                    <div class="mb-3">
                        <label class="form-label-elsafa">Tanggal Pembayaran</label>
                        <div class="position-relative">
                            <i class="bi bi-calendar3 position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none"></i>
                            <input type="date" id="editTanggal" name="tanggal_bayar" class="form-control-elsafa" style="padding-left:36px" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-elsafa">Jumlah Bayar</label>
                        <input type="number" id="editJumlah" name="jumlah_bayar" class="form-control-elsafa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-elsafa">Metode</label>
                        <div class="position-relative">
                            <select id="editMetode" name="metode" class="form-control-elsafa">
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Tunai">Tunai</option>
                            </select>
                            <i class="bi bi-chevron-down position-absolute" style="right:12px;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-elsafa">Catatan</label>
                        <input type="text" id="editCatatan" name="catatan" class="form-control-elsafa">
                    </div>
                </div>
                <div class="px-4 pb-4 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn-elsafa px-4">Simpan</button>
                    <button type="button" class="btn-elsafa-outline px-4" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: Hapus Pembayaran --}}
<div class="modal fade" id="deletePembayaranModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px">
        <div class="modal-content border-0 rounded-4 p-4 text-center">
            <div class="delete-icon-wrap"><i class="bi bi-trash3-fill"></i></div>
            <p class="fw-600 mb-4" style="font-size:15px;color:#374151">
                Apakah anda yakin ingin menghapus pembayaran ini?
            </p>
            <div class="d-flex gap-3 justify-content-center">
                <form id="deletePembayaranForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-elsafa" style="background:var(--red)">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
                <button type="button" class="btn-elsafa-outline" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const jamaahId  = {{ $jamaah->id }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function openAddModal() {
    new bootstrap.Modal(document.getElementById('addPayModal')).show();
}
function openEditModal(id, tanggal, jumlah, metode, catatan) {
    document.getElementById('editPayForm').action = `/jamaah/${jamaahId}/pembayaran/${id}`;
    document.getElementById('editTanggal').value  = tanggal;
    document.getElementById('editJumlah').value   = jumlah;
    document.getElementById('editMetode').value   = metode;
    document.getElementById('editCatatan').value  = catatan;
    new bootstrap.Modal(document.getElementById('editPayModal')).show();
}
function confirmDeletePembayaran(id) {
    document.getElementById('deletePembayaranForm').action = `/jamaah/${jamaahId}/pembayaran/${id}`;
    new bootstrap.Modal(document.getElementById('deletePembayaranModal')).show();
}
function doUpload(url, formData, onSuccess) {
    fetch(url, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(res => { if (res.success) onSuccess(res); else alert(res.message ?? 'Upload gagal.'); })
        .catch(() => alert('Terjadi kesalahan koneksi.'));
}
function uploadFoto(input) {
    if (!input.files[0]) return;
    const fd = new FormData();
    fd.append('file', input.files[0]);
    fd.append('_token', csrfToken);
    doUpload(`/jamaah/${jamaahId}/upload-foto`, fd, res => {
        document.getElementById('photoPreview').innerHTML =
            `<img src="${res.url}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">`;
    });
}
function uploadDokumen(input, jenis) {
    if (!input.files[0]) return;
    const fd = new FormData();
    fd.append('file', input.files[0]);
    fd.append('jenis_dokumen', jenis);
    fd.append('_token', csrfToken);
    doUpload(`/jamaah/${jamaahId}/upload-dokumen`, fd, () => location.reload());
}
function hapusDokumen(jenis) {
    if (!confirm(`Hapus dokumen ${jenis}?`)) return;
    fetch(`/jamaah/${jamaahId}/hapus-dokumen`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ jenis_dokumen: jenis })
    })
    .then(r => r.json())
    .then(res => { if (res.success) location.reload(); else alert(res.message); });
}
</script>
@endpush