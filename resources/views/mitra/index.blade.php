@extends('layouts.app')

@section('title', 'Data Mitra')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Data Mitra</h4>
    <div class="d-flex align-items-center gap-2">
        <span class="text-muted small">Admin</span>
        <i class="bi bi-person-circle fs-4 text-secondary"></i>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filter & Search Bar --}}
<div class="card border-0 shadow-sm mb-3" style="background:#f5ede0;">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('mitras.index') }}" class="d-flex gap-2 align-items-center">
            {{-- Filter Dropdown --}}
            <div class="dropdown">
                <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2"
                        type="button" data-bs-toggle="dropdown" style="min-width:180px;">
                    <span class="text-muted small fw-semibold">Filter</span>
                    <span>{{ request('filter') && request('filter') !== 'semua' ? ucfirst(request('filter')) : 'Pilih filter...' }}</span>
                </button>
                <ul class="dropdown-menu shadow-sm">
                    <li>
                        <a class="dropdown-item {{ !request('filter') || request('filter') === 'semua' ? 'active' : '' }}"
                           href="{{ route('mitras.index', array_merge(request()->except('filter','page'), ['filter'=>'semua'])) }}">
                            Semua Status
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request('filter') === 'aktif' ? 'active' : '' }}"
                           href="{{ route('mitras.index', array_merge(request()->except('filter','page'), ['filter'=>'aktif'])) }}">
                            Aktif
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request('filter') === 'nonaktif' ? 'active' : '' }}"
                           href="{{ route('mitras.index', array_merge(request()->except('filter','page'), ['filter'=>'nonaktif'])) }}">
                            Non-Aktif
                        </a>
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

            {{-- Tambah Mitra --}}
            <button type="button" class="btn btn-dark px-4 fw-semibold"
                    data-bs-toggle="modal" data-bs-target="#modalTambahMitra">
                <i class="bi bi-plus-lg me-1"></i> Tambah Mitra
            </button>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="d-flex justify-content-end px-3 py-2 border-bottom">
            <small class="text-muted">Total : <strong>{{ $total }} Mitra</strong></small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#1e2d40; color:#fff;">
                    <tr>
                        <th class="ps-3" width="60">NO.</th>
                        <th>Nama Mitra</th>
                        <th>Kontak</th>
                        <th>Jumlah Jamaah</th>
                        <th>Status</th>
                        <th class="text-center" width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mitras as $i => $mitra)
                    <tr>
                        <td class="ps-3 text-muted">{{ $mitras->firstItem() + $i }}.</td>
                        <td class="fw-medium">{{ $mitra->nama }}</td>
                        <td>{{ $mitra->kontak ?? '-' }}</td>
                        <td>{{ $mitra->jamaah_count }}</td>
                        <td>
                            <span class="badge {{ $mitra->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($mitra->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            {{-- Detail --}}
                            <a href="{{ route('mitras.show', $mitra) }}"
                               class="btn btn-sm btn-primary me-1" title="Detail">
                                <i class="bi bi-exclamation-lg"></i>
                            </a>
                            {{-- Edit --}}
                            <button class="btn btn-sm btn-success me-1 btn-edit-mitra"
                                    title="Edit"
                                    data-id="{{ $mitra->id }}" 
                                    data-nama="{{ $mitra->nama }}"
                                    data-kontak="{{ $mitra->kontak }}"
                                    data-status="{{ $mitra->status }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            {{-- Hapus --}}
                            <button class="btn btn-sm btn-danger btn-hapus-mitra"
                                    title="Hapus"
                                    data-id="{{ $mitra->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Belum ada data mitra.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($mitras->hasPages())
        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
            <small class="text-muted">
                Menampilkan {{ $mitras->firstItem() }} - {{ $mitras->lastItem() }} entri dari {{ $mitras->total() }} entri
            </small>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item {{ $mitras->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $mitras->previousPageUrl() }}">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    @foreach($mitras->getUrlRange(1, $mitras->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $mitras->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach
                    <li class="page-item {{ !$mitras->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $mitras->nextPageUrl() }}">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        @else
        <div class="px-3 py-2 border-top">
            <small class="text-muted">Menampilkan {{ $mitras->count() }} entri dari {{ $total }} entri</small>
        </div>
        @endif
    </div>
</div>

{{-- ============ MODAL TAMBAH MITRA ============ --}}
<div class="modal fade" id="modalTambahMitra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0" style="background:#f5ede0;">
                <h5 class="modal-title fw-bold">Tambah Mitra</h5>
            </div>
            <form method="POST" action="{{ route('mitras.store') }}" id="formTambahMitra">
                @csrf
                <div class="modal-body">
                    {{-- Nama Mitra --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Mitra <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama') }}">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kontak --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kontak</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <img src="https://flagcdn.com/16x12/id.png" alt="ID" class="me-1"> +62
                            </span>
                            <input type="text" name="kontak" id="kontakInput"
                                   class="form-control @error('kontak') is-invalid @enderror"
                                   placeholder="8xx-xxxx-xxxx"
                                   value="{{ old('kontak') }}">
                        </div>
                        @error('kontak')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

{{-- ============ MODAL EDIT MITRA ============ --}}
<div class="modal fade" id="modalEditMitra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0" style="background:#f5ede0;">
                <h5 class="modal-title fw-bold">Edit Mitra</h5>
            </div>
            <form method="POST" id="formEditMitra">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Mitra <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kontak</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <img src="https://flagcdn.com/16x12/id.png" alt="ID" class="me-1"> +62
                            </span>
                            <input type="text" name="kontak" id="editKontak" class="form-control" placeholder="8xx-xxxx-xxxx">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" id="editStatus" class="form-select">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
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

{{-- ============ MODAL KONFIRMASI HAPUS ============ --}}
<div class="modal fade" id="modalHapusMitra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center p-3">
            <div class="modal-body">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 p-3 mb-2">
                        <i class="bi bi-trash3 text-danger fs-3"></i>
                    </div>
                </div>
                <p class="fw-semibold">Apakah anda yakin ingin menghapus Mitra ini?</p>
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <form method="POST" id="formHapusMitra">
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
// Open modal tambah jika ada validation error
@if($errors->any() && old('_token'))
    var addModal = new bootstrap.Modal(document.getElementById('modalTambahMitra'));
    addModal.show();
@endif

document.addEventListener('click', function(e) {
    const btnEdit = e.target.closest('.btn-edit-mitra');
    if (btnEdit) {
        const d = btnEdit.dataset;
        openEditMitra(d.id, d.nama, d.kontak, d.status);
    }

    const btnHapus = e.target.closest('.btn-hapus-mitra');
    if (btnHapus) {
        confirmDeleteMitra(btnHapus.dataset.id);
    }
});

// Edit Mitra
function openEditMitra(id, nama, kontak, status) {
    document.getElementById('formEditMitra').action = '/mitras/' + id;
    document.getElementById('editNama').value = nama;

    // Strip leading 0 for display
    let k = kontak || '';
    if (k.startsWith('0')) k = k.substring(1);
    document.getElementById('editKontak').value = k;
    document.getElementById('editStatus').value = status;

    var modal = new bootstrap.Modal(document.getElementById('modalEditMitra'));
    modal.show();
}

// Confirm Delete
function confirmDeleteMitra(id, nama) {
    document.getElementById('formHapusMitra').action = '/mitras/' + id;
    var modal = new bootstrap.Modal(document.getElementById('modalHapusMitra'));
    modal.show();
}

// Format kontak input: strip leading 0
document.getElementById('kontakInput')?.addEventListener('input', function() {
    if (this.value.startsWith('0')) {
        this.value = this.value.substring(1);
    }
});
</script>
@endpush
@endsection