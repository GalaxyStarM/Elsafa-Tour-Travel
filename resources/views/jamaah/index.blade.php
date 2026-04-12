{{-- resources/views/jamaah/index.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Data Jamaah')

@section('content')
<div class="d-flex flex-column gap-4">

    {{-- FILTER BAR --}}
    <div class="filter-bar">
        <div class="filter-select-wrap">
            <button class="filter-btn" id="filterToggle" type="button">
                <i class="bi bi-funnel"></i> Filter
                @if(request()->anyFilled(['paket_id','jenis_jamaah','status_jamaah','status_pembayaran']))
                    <span class="badge bg-warning text-dark ms-1" style="font-size:.7rem">
                        {{ collect(['paket_id','jenis_jamaah','status_jamaah','status_pembayaran'])->filter(fn($k) => request()->filled($k))->count() }}
                    </span>
                @endif
                <i class="bi bi-chevron-down" style="font-size:.7rem"></i>
            </button>

            {{-- DROPDOWN FILTER --}}
            <div id="filterDropdown" class="position-absolute bg-white rounded-3 shadow-lg p-3"
                 style="top:44px;left:0;width:260px;z-index:200;display:none;border:1.5px solid #E2E8F0">
                <form method="GET" action="{{ route('jamaah.index') }}" id="filterForm">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    {{-- Paket --}}
                    <p class="fw-bold mb-1" style="font-size:.8rem;color:#374151">Paket</p>
                    <div class="mb-2">
                        <label class="d-flex align-items-center gap-2 mb-1" style="font-size:.83rem;cursor:pointer">
                            <input type="radio" name="paket_id" value=""
                                   {{ !request('paket_id') ? 'checked' : '' }}> Semua Paket
                        </label>
                        @foreach($pakets as $p)
                        <label class="d-flex align-items-center gap-2 mb-1" style="font-size:.83rem;cursor:pointer">
                            <input type="radio" name="paket_id" value="{{ $p->id }}"
                                   {{ request('paket_id') == $p->id ? 'checked' : '' }}> {{ $p->nama_paket }}
                        </label>
                        @endforeach
                    </div>

                    <hr class="my-2">

                    {{-- Jenis Jamaah --}}
                    <p class="fw-bold mb-1" style="font-size:.8rem;color:#374151">Jenis Jamaah</p>
                    <div class="mb-2">
                        @foreach(['Mandiri','Mitra'] as $jenis)
                        <label class="d-flex align-items-center gap-2 mb-1" style="font-size:.83rem;cursor:pointer">
                            <input type="checkbox" name="jenis_jamaah" value="{{ $jenis }}"
                                   {{ request('jenis_jamaah') === $jenis ? 'checked' : '' }}> {{ $jenis }}
                        </label>
                        @endforeach
                    </div>

                    <hr class="my-2">

                    {{-- Status Jamaah --}}
                    <p class="fw-bold mb-1" style="font-size:.8rem;color:#374151">Status Jamaah</p>
                    <div class="mb-2">
                        @foreach(['Akan Berangkat','Selesai'] as $status)
                        <label class="d-flex align-items-center gap-2 mb-1" style="font-size:.83rem;cursor:pointer">
                            <input type="checkbox" name="status_jamaah" value="{{ $status }}"
                                   {{ request('status_jamaah') === $status ? 'checked' : '' }}> {{ $status }}
                        </label>
                        @endforeach
                    </div>

                    <hr class="my-2">

                    {{-- Status Pembayaran --}}
                    <p class="fw-bold mb-1" style="font-size:.8rem;color:#374151">Status Pembayaran</p>
                    <div class="mb-3">
                        @foreach(['Lunas','Belum Lunas'] as $sp)
                        <label class="d-flex align-items-center gap-2 mb-1" style="font-size:.83rem;cursor:pointer">
                            <input type="checkbox" name="status_pembayaran" value="{{ $sp }}"
                                   {{ request('status_pembayaran') === $sp ? 'checked' : '' }}> {{ $sp }}
                        </label>
                        @endforeach
                    </div>

                    <button type="submit" class="btn-elsafa-primary w-100 justify-content-center">
                        Terapkan
                    </button>
                </form>
            </div>
        </div>

        {{-- SEARCH --}}
        <form method="GET" action="{{ route('jamaah.index') }}" class="search-input-wrap" id="searchForm">
            @foreach(['paket_id','jenis_jamaah','status_jamaah','status_pembayaran'] as $fk)
                @if(request($fk))
                    <input type="hidden" name="{{ $fk }}" value="{{ request($fk) }}">
                @endif
            @endforeach
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="search-input" name="search"
                   placeholder="Search..." value="{{ request('search') }}"
                   autocomplete="off">
        </form>

        <a href="{{ route('jamaah.create') }}" class="btn-elsafa-primary ms-auto">
            <i class="bi bi-plus-lg"></i> Tambah Jamaah
        </a>
    </div>

    {{-- TOTAL --}}
    <div class="text-end" style="font-size:.85rem;color:#64748B;font-weight:600">
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
                        <th style="width:90px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jamaah as $i => $j)
                    <tr>
                        <td class="text-center fw-semibold" style="color:#94A3B8">
                            {{ ($jamaah->currentPage() - 1) * $jamaah->perPage() + $i + 1 }}.
                        </td>
                        <td class="fw-semibold">{{ $j->nama_lengkap }}</td>
                        <td>{{ $j->paket->nama_paket ?? '-' }}</td>
                        <td>
                            <span class="{{ $j->jenis_jamaah === 'Mandiri' ? 'badge-mandiri' : 'badge-mitra' }}">
                                {{ $j->jenis_jamaah }}
                            </span>
                        </td>
                        <td>{{ $j->status_jamaah }}</td>
                        <td>
                            <span class="{{ $j->status_pembayaran === 'Lunas' ? 'badge-lunas' : 'badge-belum-lunas' }}">
                                {{ $j->status_pembayaran }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('jamaah.show', $j) }}" class="btn-action btn-action-detail" title="Detail">
                                    <i class="bi bi-exclamation-lg"></i>
                                </a>
                                <button type="button" class="btn-action btn-action-delete"
                                        title="Hapus"
                                        onclick="confirmDelete({{ $j->id }}, '{{ addslashes($j->nama_lengkap) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4" style="color:#94A3B8">
                            <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px"></i>
                            Tidak ada data jamaah ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex align-items-center justify-content-between px-4 py-3"
             style="border-top:1px solid #F1F5F9">
            <span style="font-size:.85rem;color:#64748B">
                Menampilkan {{ $jamaah->firstItem() ?? 0 }} - {{ $jamaah->lastItem() ?? 0 }}
                dari {{ $jamaah->total() }} entri
            </span>
            <div class="pagination-elsafa">
                @if($jamaah->onFirstPage())
                    <span class="page-btn" style="opacity:.4;cursor:not-allowed"><i class="bi bi-chevron-left"></i></span>
                @else
                    <a href="{{ $jamaah->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                @endif

                @foreach($jamaah->getUrlRange(1, $jamaah->lastPage()) as $page => $url)
                    @if($page == 1 || $page == $jamaah->lastPage() || abs($page - $jamaah->currentPage()) <= 1)
                        <a href="{{ $url }}" class="page-btn {{ $page == $jamaah->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @elseif(abs($page - $jamaah->currentPage()) == 2)
                        <span class="page-btn" style="pointer-events:none">...</span>
                    @endif
                @endforeach

                @if($jamaah->hasMorePages())
                    <a href="{{ $jamaah->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="page-btn" style="opacity:.4;cursor:not-allowed"><i class="bi bi-chevron-right"></i></span>
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
            <p class="fw-semibold mb-4" id="deleteMessage">Apakah anda yakin ingin menghapus jamaah ini?</p>
            <div class="d-flex gap-3 justify-content-center">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-elsafa-primary" style="background:var(--elsafa-red)">
                        Hapus
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
// Filter dropdown toggle
document.getElementById('filterToggle').addEventListener('click', function(e) {
    e.stopPropagation();
    const dd = document.getElementById('filterDropdown');
    dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
});

document.addEventListener('click', function(e) {
    if (!document.getElementById('filterDropdown').contains(e.target)) {
        document.getElementById('filterDropdown').style.display = 'none';
    }
});

// Search on enter
document.querySelector('.search-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') this.closest('form').submit();
});

// Delete confirm
function confirmDelete(id, nama) {
    document.getElementById('deleteMessage').textContent =
        'Apakah anda yakin ingin menghapus jamaah "' + nama + '"?';
    document.getElementById('deleteForm').action = '/jamaah/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush