{{-- resources/views/jamaah/index.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Data Jamaah')

@section('content')
<div class="d-flex flex-column gap-3">

    {{-- FILTER BAR --}}
    <div class="filter-bar">

        {{-- Filter Dropdown --}}
        <div class="position-relative">
            <button class="filter-toggle-btn" id="filterToggle" type="button">
                <span style="color:#adb5bd;font-size:12px;font-weight:500">Filter</span>
                <span id="filterLabel" style="font-size:13px;color:#495057">
                    @if(request()->anyFilled(['paket_id','jenis_jamaah','status_jamaah','status_pembayaran','perhatian']))
                        Filter aktif
                    @else
                        Pilih filter...
                    @endif
                </span>
                <i class="bi bi-chevron-down" style="font-size:11px;color:#adb5bd;margin-left:auto"></i>
            </button>

            {{-- DROPDOWN FILTER --}}
            <div id="filterDropdown" class="filter-popup" style="width:260px">
                <form method="GET" action="{{ route('jamaah.index') }}" id="filterForm">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <p class="filter-popup-title">Paket</p>
                    <div class="mb-3">
                        <label class="filter-popup-label">
                            <input type="radio" name="paket_id" value="" {{ !request('paket_id') ? 'checked' : '' }}> Semua Paket
                        </label>
                        @foreach($paket as $p)
                        <label class="filter-popup-label">
                            <input type="radio" name="paket_id" value="{{ $p->id }}" {{ request('paket_id') == $p->id ? 'checked' : '' }}>
                            {{ $p->nama }}
                        </label>
                        @endforeach
                    </div>

                    <hr class="my-2">
                    <p class="filter-popup-title">Jenis Jamaah</p>
                    <div class="mb-3">
                        @foreach(['Mandiri','Mitra'] as $jenis)
                        <label class="filter-popup-label">
                            <input type="checkbox" name="jenis_jamaah" value="{{ $jenis }}" {{ request('jenis_jamaah') === $jenis ? 'checked' : '' }}>
                            {{ $jenis }}
                        </label>
                        @endforeach
                    </div>

                    <hr class="my-2">
                    <p class="filter-popup-title">Status Jamaah</p>
                    <div class="mb-3">
                        @foreach(['Akan Berangkat','Selesai'] as $status)
                        <label class="filter-popup-label">
                            <input type="checkbox" name="status_jamaah" value="{{ $status }}" {{ request('status_jamaah') === $status ? 'checked' : '' }}>
                            {{ $status }}
                        </label>
                        @endforeach
                    </div>

                    <hr class="my-2">
                    <p class="filter-popup-title">Status Pembayaran</p>
                    <div class="mb-3">
                        @foreach(['Lunas','Belum Lunas'] as $sp)
                        <label class="filter-popup-label">
                            <input type="checkbox" name="status_pembayaran" value="{{ $sp }}" {{ request('status_pembayaran') === $sp ? 'checked' : '' }}>
                            {{ $sp }}
                        </label>
                        @endforeach
                    </div>

                    <button type="submit" class="btn-elsafa w-100 mt-2 justify-content-center">
                        Terapkan
                    </button>
                </form>
            </div>
        </div>

        {{-- SEARCH --}}
        <form method="GET" action="{{ route('jamaah.index') }}" class="search-wrap" id="searchForm">
            @foreach(['paket_id','jenis_jamaah','status_jamaah','status_pembayaran','perhatian'] as $fk)
                @if(request($fk))
                    <input type="hidden" name="{{ $fk }}" value="{{ request($fk) }}">
                @endif
            @endforeach
            <i class="bi bi-search"></i>
            <input type="text" class="search-input" name="search"
                   placeholder="Search..." value="{{ request('search') }}" autocomplete="off">
        </form>

        <a href="{{ route('jamaah.create') }}" class="btn-elsafa ms-auto">
            <i class="bi bi-plus-lg"></i> Tambah Jamaah
        </a>
    </div>

    {{-- BANNER FILTER PERHATIAN AKTIF --}}
    @if(request('perhatian'))
    @php
        $perhatianLabel = match(request('perhatian')) {
            'dokumen_tidak_lengkap' => ['label' => 'Dokumen tidak lengkap', 'icon' => 'bi-file-earmark-x', 'color' => '#856404', 'bg' => '#fff8e1', 'border' => '#f6d860'],
            'belum_ada_pembayaran'  => ['label' => 'Belum ada pembayaran', 'icon' => 'bi-cash-stack', 'color' => '#842029', 'bg' => '#fff0f0', 'border' => '#f5c2c7'],
            'cicilan_30_hari'       => ['label' => 'Cicilan terakhir >30 hari', 'icon' => 'bi-clock-history', 'color' => '#856404', 'bg' => '#fff8e1', 'border' => '#f6d860'],
            default                 => null,
        };
    @endphp
    @if($perhatianLabel)
    <div style="display:flex;align-items:center;gap:10px;padding:10px 16px;background:{{ $perhatianLabel['bg'] }};border:1.5px solid {{ $perhatianLabel['border'] }};border-radius:8px;font-size:13px;color:{{ $perhatianLabel['color'] }};font-weight:600">
        <i class="bi {{ $perhatianLabel['icon'] }}"></i>
        <span>Menampilkan jamaah: {{ $perhatianLabel['label'] }}</span>
        <a href="{{ route('jamaah.index') }}" style="margin-left:auto;color:{{ $perhatianLabel['color'] }};text-decoration:none;font-size:12px;opacity:.7">
            <i class="bi bi-x-lg"></i> Hapus filter
        </a>
    </div>
    @endif
    @endif

    {{-- TOTAL --}}
    <div class="text-end" style="font-size:13px;color:#6c757d;font-weight:600">
        Total : {{ number_format($total) }} Jamaah
    </div>

    {{-- TABLE --}}
    <div class="card-elsafa">
        <div class="table-responsive">
            <table class="table-elsafa">
                <thead>
                    <tr>
                        <th style="width:50px">NO.</th>
                        <th>Nama Jamaah</th>
                        <th>Paket</th>
                        <th>Jenis</th>
                        <th>Status Jamaah</th>
                        <th>Status Pembayaran</th>
                        <th class="text-center" style="width:80px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jamaah as $i => $j)
                    <tr>
                        <td class="text-center" style="color:#adb5bd;font-weight:600">
                            {{ ($jamaah->currentPage() - 1) * $jamaah->perPage() + $i + 1 }}.
                        </td>
                        <td class="fw-600">{{ $j->nama_lengkap }}</td>
                        <td style="font-size:13px">{{ $j->paket->nama ?? '-' }}</td>
                        <td>
                            <span class="bdg {{ $j->jenis_jamaah === 'Mandiri' ? 'bdg-mandiri' : 'bdg-mitra' }}">
                                {{ $j->jenis_jamaah }}
                            </span>
                        </td>
                        <td style="font-size:13px">{{ $j->status_jamaah }}</td>
                        <td>
                            <span class="bdg {{ $j->status_pembayaran === 'Lunas' ? 'bdg-lunas' : 'bdg-belum' }}">
                                {{ $j->status_pembayaran }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('jamaah.show', $j) }}" class="btn-act btn-detail" title="Detail">
                                    <i class="bi bi-exclamation-lg"></i>
                                </a>
                                <button type="button" class="btn-act btn-del" title="Hapus"
                                        onclick="confirmDelete({{ $j->id }}, '{{ addslashes($j->nama_lengkap) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4" style="color:#adb5bd">
                            <i class="bi bi-inbox d-block mb-2" style="font-size:2rem"></i>
                            Tidak ada data jamaah ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex align-items-center justify-content-between px-4 py-3"
             style="border-top:1px solid #f0f0f0">
            <span style="font-size:13px;color:#6c757d">
                Menampilkan {{ $jamaah->firstItem() ?? 0 }} - {{ $jamaah->lastItem() ?? 0 }}
                dari {{ $jamaah->total() }} entri
            </span>
            <div class="d-flex gap-1">
                @if($jamaah->onFirstPage())
                    <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                @else
                    <a href="{{ $jamaah->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                @endif

                @foreach($jamaah->getUrlRange(1, $jamaah->lastPage()) as $page => $url)
                    @if($page == 1 || $page == $jamaah->lastPage() || abs($page - $jamaah->currentPage()) <= 1)
                        <a href="{{ $url }}" class="page-btn {{ $page == $jamaah->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @elseif(abs($page - $jamaah->currentPage()) == 2)
                        <span class="page-btn" style="pointer-events:none;cursor:default">...</span>
                    @endif
                @endforeach

                @if($jamaah->hasMorePages())
                    <a href="{{ $jamaah->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- DELETE CONFIRM MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px">
        <div class="modal-content border-0 rounded-4 p-4 text-center">
            <div class="delete-icon-wrap">
                <i class="bi bi-trash3-fill"></i>
            </div>
            <p class="fw-600 mb-4" id="deleteMessage">Apakah anda yakin ingin menghapus jamaah ini?</p>
            <div class="d-flex gap-3 justify-content-center">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-elsafa" style="background:var(--red)">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        style="border-radius:8px;font-weight:600;font-size:14px">
                    Batal
                </button>
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
// Toggle dropdown saat klik tombol filter
document.getElementById('filterToggle').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('filterDropdown').classList.toggle('show');
});

// Cegah dropdown tertutup saat klik di dalam area filter popup
document.getElementById('filterDropdown').addEventListener('click', function(e) {
    e.stopPropagation();
});

// Tutup dropdown hanya saat klik di luar area filter (tombol & popup)
document.addEventListener('click', function(e) {
    const toggle = document.getElementById('filterToggle');
    const dropdown = document.getElementById('filterDropdown');
    if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});

// Search on Enter
document.querySelector('.search-input')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') this.closest('form').submit();
});

// Delete confirmation modal
function confirmDelete(id, nama) {
    document.getElementById('deleteMessage').textContent =
        'Apakah anda yakin ingin menghapus jamaah "' + nama + '"?';
    document.getElementById('deleteForm').action = '/jamaah/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush