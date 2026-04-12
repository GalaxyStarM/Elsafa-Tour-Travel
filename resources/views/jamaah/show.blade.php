{{-- resources/views/jamaah/show.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Detail Jamaah')

@section('content')
<div class="row g-4">

    {{-- LEFT: PROFIL + DOKUMEN --}}
    <div class="col-lg-5">
        <div class="card-elsafa p-4">

            {{-- FOTO PROFIL --}}
            <div class="text-center mb-3">
                <div class="photo-wrap">
                    <div class="photo-avatar" id="photoPreview">
                        @if($jamaah->foto_profil)
                            <img src="{{ Storage::url($jamaah->foto_profil) }}"
                                 alt="{{ $jamaah->nama_lengkap }}"
                                 style="width:110px;height:110px;border-radius:50%;object-fit:cover">
                        @else
                            <i class="bi bi-person-fill"></i>
                        @endif
                    </div>
                    <label for="fotoInput" class="photo-upload-btn" title="Ganti foto">
                        <i class="bi bi-camera-fill"></i>
                    </label>
                    <input type="file" id="fotoInput" accept="image/*" style="display:none"
                           onchange="uploadFoto(this)">
                </div>

                <h5 class="fw-bold mb-1 mt-2">{{ $jamaah->nama_lengkap }}</h5>
                @if($jamaah->kontak)
                    <div style="color:#64748B;font-size:.85rem;margin-bottom:8px">
                        {{ $jamaah->kontak_formatted ?? $jamaah->kontak }}
                    </div>
                @endif

                <span class="{{ $jamaah->jenis_jamaah === 'Mandiri' ? 'badge-mandiri' : 'badge-mitra' }} mb-2 d-inline-block">
                    {{ $jamaah->jenis_jamaah }}
                    @if($jamaah->mitra) — {{ $jamaah->mitra->nama_mitra }} @endif
                </span>

                <div class="d-flex justify-content-center gap-2 flex-wrap mt-1">
                    <span class="px-3 py-1 rounded-pill" style="background:#F1F5F9;font-size:.8rem;color:#374151">
                        {{ $jamaah->paket->nama_paket ?? '-' }}
                    </span>
                    <span class="px-3 py-1 rounded-pill" style="background:#F1F5F9;font-size:.8rem;color:#374151">
                        {{ $jamaah->status_jamaah }}
                    </span>
                    <span class="px-3 py-1 rounded-pill" style="background:#EDE9FE;font-size:.8rem;color:#5B21B6">
                        {{ $jamaah->kategori_usia }}
                    </span>
                </div>

                <div class="mt-2">
                    <a href="{{ route('jamaah.edit', $jamaah) }}" class="text-muted" style="font-size:.8rem;text-decoration:none">
                        <i class="bi bi-pencil"></i> Edit Data
                    </a>
                </div>
            </div>

            <hr>

            {{-- STATUS DOKUMEN --}}
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="fw-semibold" style="font-size:.85rem">Dokumen</span>
                @if($jamaah->dokumen_lengkap)
                    <span class="badge-lunas" style="font-size:.75rem">Lengkap</span>
                @else
                    <span class="badge-belum-lunas" style="font-size:.75rem">Belum Lengkap</span>
                @endif
            </div>

            {{-- DOKUMEN TABS — dinamis berdasarkan kategori_usia --}}
            <ul class="nav nav-tabs doc-tabs" id="dokTabs" role="tablist">
                @foreach($dokumenDiperlukan as $i => $jenis)
                <li class="nav-item">
                    <a class="nav-link {{ $i === 0 ? 'active' : '' }}"
                       data-bs-toggle="tab"
                       href="#tab{{ Str::studly($jenis) }}"
                       role="tab">
                        {{ $jenis }}
                        @if(isset($dokumenMap[$jenis]))
                            <i class="bi bi-check-circle-fill text-success ms-1" style="font-size:.7rem"></i>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>

            <div class="tab-content pt-3">
                @foreach($dokumenDiperlukan as $i => $jenis)
                @php
                    $dok = $dokumenMap[$jenis] ?? null;
                    $tabId = 'tab' . Str::studly($jenis);
                    $inputKey = strtolower(str_replace([' ', '-'], '_', $jenis));
                @endphp

                <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="{{ $tabId }}">
                    @if($dok)
                        {{-- SUDAH ADA DOKUMEN --}}
                        <div class="text-center">
                            <img src="{{ Storage::url($dok->file_path) }}"
                                 alt="{{ $jenis }}"
                                 class="img-fluid rounded-3 mb-2"
                                 style="max-height:200px;object-fit:contain;border:1.5px solid #E2E8F0;cursor:pointer"
                                 onclick="window.open('{{ Storage::url($dok->file_path) }}', '_blank')">
                            <div style="font-size:.75rem;color:#94A3B8;margin-bottom:8px">
                                {{ $dok->nama_file ?? $jenis }}
                            </div>
                            <label class="btn-elsafa-outline" style="font-size:.8rem;padding:6px 14px;cursor:pointer">
                                <i class="bi bi-arrow-repeat"></i> Ganti
                                <input type="file" style="display:none" accept="image/*"
                                       onchange="uploadDokumen(this, '{{ $jenis }}')">
                            </label>
                        </div>
                    @else
                        {{-- BELUM ADA DOKUMEN --}}
                        <div class="upload-zone" onclick="document.getElementById('up_{{ $inputKey }}').click()">
                            <i class="bi bi-cloud-upload d-block mb-2"></i>
                            <p>Choose a file or drag & drop it here</p>
                            <small>JPEG, JPG, and PNG formats, up to 5MB</small>
                            <div>
                                <span class="browse-btn mt-2 d-inline-block">Browse</span>
                            </div>
                        </div>
                        <input type="file" id="up_{{ $inputKey }}" style="display:none" accept="image/*"
                               onchange="uploadDokumen(this, '{{ $jenis }}')">
                    @endif
                </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- RIGHT: RIWAYAT PEMBAYARAN --}}
    <div class="col-lg-7">
        <div class="card-elsafa">
            <div class="d-flex align-items-center justify-content-between p-4 pb-3"
                 style="border-bottom:1px solid #F1F5F9">
                <h6 class="fw-bold mb-0">Riwayat Pembayaran</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('jamaah.invoice', $jamaah) }}" target="_blank"
                       class="btn-elsafa-outline" style="font-size:.8rem;padding:7px 14px">
                        <i class="bi bi-printer"></i> Cetak Invoice
                    </a>
                    <button class="btn-elsafa-primary" style="font-size:.8rem;padding:7px 14px"
                            onclick="openAddModal()">
                        <i class="bi bi-plus-lg"></i> Tambah Pembayaran
                    </button>
                </div>
            </div>

            {{-- Summary --}}
            <div class="px-4 py-3 d-flex gap-4 flex-wrap" style="background:#FAFBFC;border-bottom:1px solid #F1F5F9">
                <div>
                    <div style="font-size:.75rem;color:#64748B;font-weight:600">Total Paket</div>
                    <div style="font-size:.95rem;font-weight:700;color:var(--elsafa-dark)">
                        Rp {{ number_format($jamaah->paket->harga ?? 0, 0, ',', '.') }}
                    </div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#64748B;font-weight:600">Sudah Dibayar</div>
                    <div style="font-size:.95rem;font-weight:700;color:var(--elsafa-green)">
                        Rp {{ number_format($jamaah->total_pembayaran, 0, ',', '.') }}
                    </div>
                </div>
                <div>
                    @php $sisa = ($jamaah->paket->harga ?? 0) - $jamaah->total_pembayaran; @endphp
                    <div style="font-size:.75rem;color:#64748B;font-weight:600">Sisa</div>
                    <div style="font-size:.95rem;font-weight:700;color:{{ $sisa > 0 ? 'var(--elsafa-red)' : 'var(--elsafa-green)' }}">
                        Rp {{ number_format(max(0, $sisa), 0, ',', '.') }}
                    </div>
                </div>
                <div class="ms-auto d-flex align-items-center">
                    <span class="{{ $jamaah->status_pembayaran === 'Lunas' ? 'badge-lunas' : 'badge-belum-lunas' }}">
                        {{ $jamaah->status_pembayaran }}
                    </span>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table-elsafa">
                    <thead>
                        <tr>
                            <th style="width:45px">NO.</th>
                            <th>Tanggal</th>
                            <th>Jumlah Bayar</th>
                            <th>Metode</th>
                            <th>Catatan</th>
                            <th style="width:80px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran as $i => $p)
                        <tr>
                            <td class="text-center fw-semibold" style="color:#94A3B8">{{ $i + 1 }}.</td>
                            <td>{{ $p->tanggal_bayar->format('d-m-Y') }}</td>
                            <td class="fw-semibold">{{ $p->jumlah_bayar_formatted }}</td>
                            <td>{{ $p->metode }}</td>
                            <td style="color:#64748B">{{ $p->catatan ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn-action btn-action-edit"
                                            onclick="openEditModal({{ $p->id }}, '{{ $p->tanggal_bayar->format('Y-m-d') }}', {{ $p->jumlah_bayar }}, '{{ $p->metode }}', '{{ addslashes($p->catatan) }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn-action btn-action-delete"
                                            onclick="confirmDeletePembayaran({{ $p->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4" style="color:#94A3B8">
                                Belum ada riwayat pembayaran.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex align-items-center justify-content-between px-4 py-3"
                 style="border-top:1px solid #F1F5F9">
                <span style="font-size:.85rem;color:#64748B">
                    Menampilkan {{ $pembayaran->firstItem() ?? 0 }} entri dari {{ $pembayaran->total() }} entri
                </span>
                <div class="pagination-elsafa">
                    @if(!$pembayaran->onFirstPage())
                        <a href="{{ $pembayaran->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                    @else
                        <span class="page-btn" style="opacity:.4"><i class="bi bi-chevron-left"></i></span>
                    @endif
                    @foreach($pembayaran->getUrlRange(1, $pembayaran->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $pembayaran->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($pembayaran->hasMorePages())
                        <a href="{{ $pembayaran->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                    @else
                        <span class="page-btn" style="opacity:.4"><i class="bi bi-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH PEMBAYARAN --}}
<div class="modal fade" id="addPayModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header-elsafa">Tambah Pembayaran</div>
            <form method="POST" action="{{ route('jamaah.pembayaran.store', $jamaah) }}" class="p-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label-elsafa">Tanggal Pembayaran <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_bayar" class="form-control-elsafa" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label-elsafa">Jumlah Bayar <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_bayar" class="form-control-elsafa" placeholder="Masukkan Nominal" min="1000" required>
                </div>
                <div class="mb-3">
                    <label class="form-label-elsafa">Metode <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <select name="metode" class="form-control-elsafa" style="appearance:none" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Tunai">Tunai</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <i class="bi bi-chevron-down position-absolute" style="right:14px;top:50%;transform:translateY(-50%);color:#94A3B8;pointer-events:none"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label-elsafa">Catatan</label>
                    <input type="text" name="catatan" class="form-control-elsafa" placeholder="Tambahkan catatan (opsional)">
                </div>
                <div class="d-flex gap-3 justify-content-end">
                    <button type="button" class="btn-elsafa-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-elsafa-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT PEMBAYARAN --}}
<div class="modal fade" id="editPayModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header-elsafa">Edit Pembayaran</div>
            <form method="POST" id="editPayForm" class="p-4">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label-elsafa">Tanggal Pembayaran <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_bayar" id="editTanggal" class="form-control-elsafa" required>
                </div>
                <div class="mb-3">
                    <label class="form-label-elsafa">Jumlah Bayar <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_bayar" id="editJumlah" class="form-control-elsafa" min="1000" required>
                </div>
                <div class="mb-3">
                    <label class="form-label-elsafa">Metode <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <select name="metode" id="editMetode" class="form-control-elsafa" style="appearance:none" required>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Tunai">Tunai</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <i class="bi bi-chevron-down position-absolute" style="right:14px;top:50%;transform:translateY(-50%);color:#94A3B8;pointer-events:none"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label-elsafa">Catatan</label>
                    <input type="text" name="catatan" id="editCatatan" class="form-control-elsafa">
                </div>
                <div class="d-flex gap-3 justify-content-end">
                    <button type="button" class="btn-elsafa-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-elsafa-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DELETE PEMBAYARAN --}}
<div class="modal fade" id="deletePembayaranModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px">
        <div class="modal-content border-0 rounded-4 p-4 text-center">
            <div class="delete-icon-wrap"><i class="bi bi-trash3-fill"></i></div>
            <p class="fw-semibold mb-4">Apakah anda yakin ingin menghapus pembayaran ini?</p>
            <div class="d-flex gap-3 justify-content-center">
                <form id="deletePembayaranForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-elsafa-primary" style="background:var(--elsafa-red)">Hapus</button>
                </form>
                <button type="button" class="btn-elsafa-outline" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const jamaahId = {{ $jamaah->id }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function openAddModal() {
    new bootstrap.Modal(document.getElementById('addPayModal')).show();
}

function openEditModal(id, tanggal, jumlah, metode, catatan) {
    document.getElementById('editPayForm').action = `/jamaah/${jamaahId}/pembayaran/${id}`;
    document.getElementById('editTanggal').value = tanggal;
    document.getElementById('editJumlah').value = jumlah;
    document.getElementById('editMetode').value = metode;
    document.getElementById('editCatatan').value = catatan;
    new bootstrap.Modal(document.getElementById('editPayModal')).show();
}

function confirmDeletePembayaran(id) {
    document.getElementById('deletePembayaranForm').action = `/jamaah/${jamaahId}/pembayaran/${id}`;
    new bootstrap.Modal(document.getElementById('deletePembayaranModal')).show();
}

function uploadFoto(input) {
    if (!input.files[0]) return;
    const fd = new FormData();
    fd.append('file', input.files[0]);
    fd.append('_token', csrfToken);

    fetch(`/jamaah/${jamaahId}/upload-foto`, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('photoPreview').innerHTML =
                    `<img src="${data.url}" style="width:110px;height:110px;border-radius:50%;object-fit:cover">`;
            } else {
                alert('Upload gagal: ' + (data.message || ''));
            }
        })
        .catch(() => alert('Terjadi kesalahan saat upload foto.'));
}

function uploadDokumen(input, jenisDokumen) {
    if (!input.files[0]) return;
    const fd = new FormData();
    fd.append('file', input.files[0]);
    fd.append('jenis_dokumen', jenisDokumen);
    fd.append('_token', csrfToken);

    fetch(`/jamaah/${jamaahId}/upload-dokumen`, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Upload gagal: ' + (data.message || ''));
            }
        })
        .catch(() => alert('Terjadi kesalahan saat upload dokumen.'));
}

function hapusFoto() {
    const modal = new bootstrap.Modal(document.getElementById('hapusFotoModal'));
    modal.show();
 
    document.getElementById('btnKonfirmasiHapusFoto').onclick = function () {
        fetch(`/jamaah/${jamaahId}/hapus-foto`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                modal.hide();
                // Reset foto ke icon default
                document.getElementById('photoPreview').innerHTML =
                    '<i class="bi bi-person-fill"></i>';
                // Sembunyikan tombol hapus foto
                const hapusBtn = document.querySelector('[onclick="hapusFoto()"]');
                if (hapusBtn) hapusBtn.style.display = 'none';
            } else {
                alert('Gagal: ' + (data.message || ''));
            }
        })
        .catch(() => alert('Terjadi kesalahan saat menghapus foto.'));
    };
}

</script>
@endpush