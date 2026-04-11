@extends('layouts.app')

@section('title', $mitra->nama)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('mitras.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">{{ $mitra->nama }}</h4>
    </div>
    <span class="badge {{ $mitra->status === 'aktif' ? 'bg-success' : 'bg-secondary' }} fs-6">
        {{ ucfirst($mitra->status) }}
    </span>
</div>

{{-- Info Mitra --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <small class="text-muted d-block">Nama Mitra</small>
                <span class="fw-semibold">{{ $mitra->nama }}</span>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Kontak</small>
                <span class="fw-semibold">{{ $mitra->kontak ? '+62' . ltrim($mitra->kontak, '0') : '-' }}</span>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Jumlah Jamaah</small>
                <span class="fw-semibold">{{ $mitra->jamaah_count }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Jamaah --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#1e2d40; color:#fff;">
                    <tr>
                        <th class="ps-3" width="60">NO.</th>
                        <th>Nama Jamaah</th>
                        <th>Paket</th>
                        <th>Status Jamaah</th>
                        <th>Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jamaah as $i => $j)
                    <tr>
                        <td class="ps-3 text-muted">{{ $jamaah->firstItem() + $i }}.</td>
                        <td class="fw-medium">
                            <a href="{{ route('jamaah.show', $j) }}" class="text-decoration-none text-dark">
                                {{ $j->nama }}
                            </a>
                        </td>
                        <td>{{ $j->paket->nama ?? '-' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $j->status_jamaah)) }}</td>
                        <td>
                            @php
                                $lunas = $j->status_pembayaran === 'lunas';
                            @endphp
                            <span class="badge px-3 py-2 {{ $lunas ? 'badge-lunas' : 'badge-belum-lunas' }}">
                                {{ $lunas ? 'Lunas' : 'Belum Lunas' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Belum ada jamaah untuk mitra ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($jamaah->hasPages())
        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
            <small class="text-muted">
                Menampilkan {{ $jamaah->firstItem() }} - {{ $jamaah->lastItem() }} entri dari {{ $jamaah->total() }} entri
            </small>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item {{ $jamaah->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $jamaah->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    @foreach($jamaah->getUrlRange(1, $jamaah->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $jamaah->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach
                    <li class="page-item {{ !$jamaah->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $jamaah->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
        @else
        <div class="px-3 py-2 border-top">
            <small class="text-muted">Menampilkan {{ $jamaah->count() }} entri</small>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.badge-lunas       { background: #d4edda; color: #155724; }
.badge-belum-lunas { background: #fff3cd; color: #856404; }
</style>
@endpush