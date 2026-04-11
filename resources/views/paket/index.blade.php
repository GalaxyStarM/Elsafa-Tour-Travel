@extends('layouts.app')

@section('title', 'Paket')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Paket</h4>
    <div class="d-flex align-items-center gap-2">
        <span class="text-muted small">Admin</span>
        <i class="bi bi-person-circle fs-4 text-secondary"></i>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filter & Search Bar --}}
<div class="card border-0 shadow-sm mb-3" style="background:#f0ebe0;">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('pakets.index') }}" class="d-flex gap-2 align-items-center">
            {{-- Filter --}}
            <div class="dropdown">
                <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2"
                        type="button" data-bs-toggle="dropdown" style="min-width:180px;">
                    <span class="text-muted small fw-semibold">Filter</span>
                    <span>{{ request('filter') && request('filter') !== 'semua' ? ucfirst(request('filter')) : 'Pilih filter...' }}</span>
                </button>
                <ul class="dropdown-menu shadow-sm">
                    <li>
                        <a class="dropdown-item {{ !request('filter') || request('filter') === 'semua' ? 'active' : '' }}"
                           href="{{ route('pakets.index', ['filter'=>'semua']) }}">Semua Jenis</a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request('filter') === 'umroh' ? 'active' : '' }}"
                           href="{{ route('pakets.index', ['filter'=>'umroh']) }}">Umroh</a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request('filter') === 'haji' ? 'active' : '' }}"
                           href="{{ route('pakets.index', ['filter'=>'haji']) }}">Haji</a>
                    </li>
                </ul>
            </div>

            {{-- Search --}}
            <div class="input-group flex-grow-1">
                <input type="text" name="search" class="form-control border"
                       placeholder="Search..." value="{{ request('search') }}">
                <button class="btn btn-light border" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>

            {{-- Tambah Paket --}}
            <button type="button" class="btn btn-dark px-4 fw-semibold"
                    data-bs-toggle="modal" data-bs-target="#modalTambahPaket">
                <i class="bi bi-plus-lg me-1"></i> Tambah Paket
            </button>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="d-flex justify-content-end px-3 py-2 border-bottom">
            <small class="text-muted">Total : <strong>{{ $total }} Paket</strong></small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#1e2d40; color:#fff;">
                    <tr>
                        <th class="ps-3" width="60">NO.</th>
                        <th>Nama Paket</th>
                        <th>Jenis</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Tanggal Keberangkatan</th>
                        <th>Kuota</th>
                        <th>Jumlah Jamaah</th>
                        <th class="text-center" width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pakets as $i => $paket)
                    <tr>
                        <td class="ps-3 text-muted">{{ $pakets->firstItem() + $i }}.</td>
                        <td class="fw-medium">{{ $paket->nama }}</td>
                        <td>
                            <span class="badge {{ $paket->jenis === 'haji' ? 'bg-warning text-dark' : 'bg-info text-dark' }}">
                                {{ ucfirst($paket->jenis) }}
                            </span>
                        </td>
                        <td>{{ $paket->harga_formatted }}</td>
                        <td>
                            <span class="badge {{ $paket->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($paket->status) }}
                            </span>
                        </td>
                        <td>{{ $paket->tanggal_formatted }}</td>
                        <td>{{ $paket->kuota ?? '-' }}</td>
                        <td>{{ $paket->jamaah_count }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-success me-1 btn-edit-paket" title="Edit"
                                    data-id="{{ $paket->id }}"
                                    data-nama="{{ $paket->nama }}"
                                    data-jenis="{{ $paket->jenis }}"
                                    data-harga="{{ $paket->harga }}"
                                    data-status="{{ $paket->status }}"
                                    data-tanggal="{{ $paket->tanggal_keberangkatan->format('Y-m-d') }}"
                                    data-kuota="{{ $paket->kuota }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-hapus-paket" title="Hapus"
                                    data-id="{{ $paket->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Belum ada data paket.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pakets->hasPages())
        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
            <small class="text-muted">
                Menampilkan {{ $pakets->firstItem() }} - {{ $pakets->lastItem() }} entri dari {{ $pakets->total() }} entri
            </small>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item {{ $pakets->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $pakets->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    @foreach($pakets->getUrlRange(1, $pakets->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $pakets->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach
                    <li class="page-item {{ !$pakets->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $pakets->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
        @else
        <div class="px-3 py-2 border-top">
            <small class="text-muted">Menampilkan {{ $pakets->count() }} entri dari {{ $total }} entri</small>
        </div>
        @endif
    </div>
</div>

{{-- ============ MODAL TAMBAH PAKET ============ --}}
<div class="modal fade" id="modalTambahPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0" style="background:#f5ede0;">
                <h5 class="modal-title fw-bold">Tambah Paket</h5>
            </div>
            <form method="POST" action="{{ route('pakets.store') }}">
                @csrf
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama') }}">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select @error('jenis') is-invalid @enderror">
                            <option value="umroh" {{ old('jenis') === 'umroh' ? 'selected' : '' }}>Umroh</option>
                            <option value="haji" {{ old('jenis') === 'haji' ? 'selected' : '' }}>Haji</option>
                        </select>
                        @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                        <input type="text" name="harga" class="form-control harga-input @error('harga') is-invalid @enderror"
                               placeholder="Masukkan Nominal" value="{{ old('harga') }}">
                        @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Keberangkatan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_keberangkatan"
                               class="form-control @error('tanggal_keberangkatan') is-invalid @enderror"
                               value="{{ old('tanggal_keberangkatan') }}">
                        @error('tanggal_keberangkatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kuota</label>
                        <input type="number" name="kuota" class="form-control @error('kuota') is-invalid @enderror"
                               placeholder="Jumlah kuota" value="{{ old('kuota') }}" min="1">
                        @error('kuota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-dark px-4 fw-semibold">Simpan</button>
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============ MODAL EDIT PAKET ============ --}}
<div class="modal fade" id="modalEditPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0" style="background:#f5ede0;">
                <h5 class="modal-title fw-bold">Edit Paket</h5>
            </div>
            <form method="POST" id="formEditPaket">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editPaketNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" id="editPaketJenis" class="form-select">
                            <option value="umroh">Umroh</option>
                            <option value="haji">Haji</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                        <input type="text" name="harga" id="editPaketHarga" class="form-control harga-input" placeholder="Masukkan Nominal">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" id="editPaketStatus" class="form-select">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Keberangkatan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_keberangkatan" id="editPaketTanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kuota</label>
                        <input type="number" name="kuota" id="editPaketKuota" class="form-control" min="1">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-dark px-4 fw-semibold">Simpan</button>
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============ MODAL HAPUS PAKET ============ --}}
<div class="modal fade" id="modalHapusPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center p-3">
            <div class="modal-body">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 p-3 mb-2">
                        <i class="bi bi-trash3 text-danger fs-3"></i>
                    </div>
                </div>
                <p class="fw-semibold">Apakah anda yakin ingin menghapus paket ini?</p>
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <form method="POST" id="formHapusPaket">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Hapus</button>
                    </form>
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-open tambah modal on validation error
@if($errors->any() && old('_token'))
    new bootstrap.Modal(document.getElementById('modalTambahPaket')).show();
@endif

document.addEventListener('click', function(e) {
    const btnEdit = e.target.closest('.btn-edit-paket');
    if (btnEdit) {
        const d = btnEdit.dataset;
        openEditPaket(d.id, d.nama, d.jenis, d.harga, d.status, d.tanggal, d.kuota);
    }

    const btnHapus = e.target.closest('.btn-hapus-paket');
    if (btnHapus) {
        confirmDeletePaket(btnHapus.dataset.id);
    }
});

// Edit Paket
function openEditPaket(id, nama, jenis, harga, status, tanggal, kuota) {
    document.getElementById('formEditPaket').action = '/pakets/' + id;
    document.getElementById('editPaketNama').value    = nama;
    document.getElementById('editPaketJenis').value   = jenis;
    document.getElementById('editPaketHarga').value   = formatRupiah(harga);
    document.getElementById('editPaketStatus').value  = status;
    document.getElementById('editPaketTanggal').value = tanggal;
    document.getElementById('editPaketKuota').value   = kuota !== 'null' ? kuota : '';
    new bootstrap.Modal(document.getElementById('modalEditPaket')).show();
}

// Delete Paket
function confirmDeletePaket(id, nama) {
    document.getElementById('formHapusPaket').action = '/pakets/' + id;
    new bootstrap.Modal(document.getElementById('modalHapusPaket')).show();
}

// Format rupiah on input
function formatRupiah(angka) {
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

document.querySelectorAll('.harga-input').forEach(function(input) {
    input.addEventListener('input', function() {
        let val = this.value.replace(/[^0-9]/g, '');
        this.value = formatRupiah(val);
    });
});
</script>
@endpush
@endsection