{{-- resources/views/mitra/index.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Data Mitra')

@section('content')
<div class="d-flex flex-column gap-3">

    {{-- FILTER BAR --}}
    <div class="filter-bar">

        {{-- Filter dropdown --}}
        <div class="position-relative">
            <button class="filter-toggle-btn" id="filterToggle" type="button">
                <span style="color:#adb5bd;font-size:12px;font-weight:500">Filter</span>
                <span id="filterLabel" style="font-size:13px;color:#495057">Pilih filter...</span>
                <i class="bi bi-chevron-down" style="font-size:11px;color:#adb5bd;margin-left:auto"></i>
            </button>

            <div id="filterDropdown" class="filter-popup">
                <form method="GET" action="{{ route('mitra.index') }}">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <p class="filter-popup-title">Status</p>
                    <label class="filter-popup-label">
                        <input type="radio" name="status" value="" {{ !request('status') ? 'checked' : '' }}> Semua
                    </label>
                    <label class="filter-popup-label">
                        <input type="radio" name="status" value="aktif" {{ request('status') === 'aktif' ? 'checked' : '' }}> Aktif
                    </label>
                    <label class="filter-popup-label">
                        <input type="radio" name="status" value="nonaktif" {{ request('status') === 'nonaktif' ? 'checked' : '' }}> Nonaktif
                    </label>
                    <button type="submit" class="btn-elsafa w-100 mt-2 justify-content-center">Terapkan</button>
                </form>
            </div>
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('mitra.index') }}" class="search-wrap">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <i class="bi bi-search"></i>
            <input type="text" class="search-input" name="search"
                   placeholder="Search..." value="{{ request('search') }}" autocomplete="off">
        </form>

        {{-- Tambah --}}
        <button type="button" class="btn-elsafa ms-auto" onclick="openTambah()">
            <i class="bi bi-plus-lg"></i> Tambah Mitra
        </button>
    </div>

    {{-- Total --}}
    <div class="text-end" style="font-size:13px;color:#6c757d;font-weight:600">
        Total : {{ $mitras->total() }} Mitra
    </div>

    {{-- TABLE --}}
    <div class="card-elsafa">
        <div class="table-responsive">
            <table class="table-elsafa">
                <thead>
                    <tr>
                        <th style="width:55px">NO.</th>
                        <th>Nama Mitra</th>
                        <th>Kontak</th>
                        <th class="text-center">Jumlah Jamaah</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width:110px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mitras as $i => $m)
                    <tr>
                        <td class="text-center" style="color:#adb5bd;font-weight:600">
                            {{ ($mitras->currentPage()-1) * $mitras->perPage() + $i + 1 }}.
                        </td>
                        <td class="fw-600">{{ $m->nama }}</td>
                        <td style="font-size:13px">{{ $m->kontak ?? '-' }}</td>
                        <td class="text-center">{{ $m->jamaah_count ?? $m->jamaah()->count() }}</td>
                        <td class="text-center">
                            <span class="bdg {{ $m->status === 'aktif' ? 'bdg-aktif' : 'bdg-nonaktif' }}">
                                {{ ucfirst($m->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('mitra.show', $m) }}" class="btn-act btn-detail" title="Detail">
                                    <i class="bi bi-exclamation-lg"></i>
                                </a>
                                <button class="btn-act btn-edit" title="Edit"
                                        onclick="openEdit({{ $m->id }}, '{{ addslashes($m->nama) }}', '{{ $m->kontak }}', '{{ $m->status }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-act btn-del" title="Hapus"
                                        onclick="confirmHapus({{ $m->id }}, '{{ addslashes($m->nama) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color:#adb5bd">
                            <i class="bi bi-inbox d-block mb-2" style="font-size:1.8rem"></i>
                            Belum ada data mitra.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex align-items-center justify-content-between px-4 py-3"
             style="border-top:1px solid #f0f0f0">
            <span style="font-size:13px;color:#6c757d">
                Menampilkan {{ $mitras->firstItem() ?? 0 }} entri dari {{ $mitras->total() }} entri
            </span>
            <div class="d-flex gap-1">
                @if($mitras->onFirstPage())
                    <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                @else
                    <a href="{{ $mitras->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                @endif
                @foreach($mitras->getUrlRange(1, $mitras->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-btn {{ $page == $mitras->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($mitras->hasMorePages())
                    <a href="{{ $mitras->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ═══ MODAL TAMBAH MITRA ═══ --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header" style="padding:18px 24px">
                <h5 class="modal-title">Tambah Mitra</h5>
            </div>
            <form method="POST" action="{{ route('mitra.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Mitra <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required
                               value="{{ old('nama') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak</label>
                        <div class="phone-wrap">
                            <div class="phone-prefix">🇮🇩 +62</div>
                            <input type="text" name="kontak" class="phone-input"
                                   placeholder="8xxxxxxxxxx" value="{{ old('kontak') }}">
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="aktif" selected>Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="padding:16px 24px;gap:10px">
                    <button type="submit" class="btn-elsafa px-4">Simpan</button>
                    <button type="button" class="btn btn-outline-secondary fw-600"
                            style="border-radius:8px" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══ MODAL EDIT MITRA ═══ --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header" style="padding:18px 24px">
                <h5 class="modal-title">Edit Mitra</h5>
            </div>
            <form method="POST" id="editForm">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Mitra <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak</label>
                        <div class="phone-wrap">
                            <div class="phone-prefix">🇮🇩 +62</div>
                            <input type="text" name="kontak" id="editKontak" class="phone-input">
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="editStatus" class="form-select" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="padding:16px 24px;gap:10px">
                    <button type="submit" class="btn-elsafa px-4">Simpan</button>
                    <button type="button" class="btn btn-outline-secondary fw-600"
                            style="border-radius:8px" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══ MODAL HAPUS ═══ --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
        <div class="modal-content border-0 rounded-4 p-4 text-center">
            <div class="delete-icon-wrap"><i class="bi bi-trash3-fill"></i></div>
            <p class="fw-600 mb-4" id="hapusMsg">Apakah anda yakin ingin menghapus Mitra ini?</p>
            <div class="d-flex gap-3 justify-content-center">
                <form id="hapusForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-elsafa" style="background:var(--red)">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
                <button type="button" class="btn btn-outline-secondary fw-600"
                        style="border-radius:8px" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.filter-toggle-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1.5px solid #e9d9a0;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 13px;
    cursor: pointer;
    min-width: 200px;
    transition: border-color 0.15s;
}
.filter-toggle-btn:hover { border-color: var(--gold); }

.filter-popup {
    position: absolute;
    top: 46px;
    left: 0;
    width: 220px;
    background: #fff;
    border: 1.5px solid #e9ecef;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,.1);
    padding: 14px;
    z-index: 300;
    display: none;
}
.filter-popup.show { display: block; }
.filter-popup-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--navy);
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-bottom: 8px;
}
.filter-popup-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    cursor: pointer;
    margin-bottom: 6px;
    color: #374151;
}
</style>
@endpush

@push('scripts')
<script>
// Filter toggle
document.getElementById('filterToggle').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('filterDropdown').classList.toggle('show');
});
document.getElementById('filterDropdown').addEventListener('click', function(e) {
    e.stopPropagation();
});
document.addEventListener('click', function(e) {
    const toggle = document.getElementById('filterToggle');
    const dropdown = document.getElementById('filterDropdown');
    if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});

function openTambah() {
    new bootstrap.Modal(document.getElementById('modalTambah')).show();
}

function openEdit(id, nama, kontak, status) {
    document.getElementById('editForm').action = '/mitra/' + id;
    document.getElementById('editNama').value   = nama;
    document.getElementById('editKontak').value = kontak;
    document.getElementById('editStatus').value = status;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}

function confirmHapus(id, nama) {
    document.getElementById('hapusMsg').textContent =
        'Apakah anda yakin ingin menghapus Mitra "' + nama + '"?';
    document.getElementById('hapusForm').action = '/mitra/' + id;
    new bootstrap.Modal(document.getElementById('modalHapus')).show();
}
</script>
@endpush