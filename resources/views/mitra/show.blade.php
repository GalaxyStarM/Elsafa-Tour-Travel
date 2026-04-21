@extends('layouts.app')

@section('page-title', 'Detail Mitra')

@section('content')
<div class="d-flex flex-column gap-3">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('mitra.index') }}" class="btn-act btn-detail" title="Kembali">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h5 class="fw-bold mb-0" style="color:var(--navy)">{{ $mitra->nama }}</h5>
        </div>
        <span class="bdg {{ $mitra->status === 'Aktif' ? 'bdg-lunas' : 'bdg-belum' }}">
            {{ $mitra->status }}
        </span>
    </div>

    {{-- INFO CARD --}}
    <div class="card-elsafa px-4 py-3">
        <div class="row g-3">
            <div class="col-md-4">
                <span style="font-size:11px;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em">Nama Mitra</span>
                <div class="fw-600 mt-1" style="font-size:14px;color:var(--navy)">{{ $mitra->nama }}</div>
            </div>
            <div class="col-md-4">
                <span style="font-size:11px;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em">Kontak</span>
                <div class="fw-600 mt-1" style="font-size:14px;color:var(--navy)">
                    {{ $mitra->kontak ? '+62' . ltrim($mitra->kontak, '0') : '-' }}
                </div>
            </div>
            <div class="col-md-4">
                <span style="font-size:11px;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.05em">Jumlah Jamaah</span>
                <div class="fw-600 mt-1" style="font-size:14px;color:var(--navy)">{{ $mitra->jamaah_count }}</div>
            </div>
        </div>
    </div>

    {{-- TOTAL --}}
    <div class="text-end" style="font-size:13px;color:#6c757d;font-weight:600">
        Total : {{ number_format($jamaah->total()) }} Jamaah
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
                        <th>Status Jamaah</th>
                        <th>Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jamaah as $i => $j)
                    <tr>
                        <td class="text-center" style="color:#adb5bd;font-weight:600">
                            {{ $jamaah->firstItem() + $i }}.
                        </td>
                        <td class="fw-600">
                            <a href="{{ route('jamaah.show', $j) }}" class="text-decoration-none" style="color:var(--navy)">
                                {{ $j->nama_lengkap }}
                            </a>
                        </td>
                        <td style="font-size:13px">{{ $j->paket->nama ?? '-' }}</td>
                        <td style="font-size:13px">{{ $j->status_jamaah }}</td>
                        <td>
                            <span class="bdg {{ $j->status_pembayaran === 'Lunas' ? 'bdg-lunas' : 'bdg-belum' }}">
                                {{ $j->status_pembayaran }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4" style="color:#adb5bd">
                            <i class="bi bi-inbox d-block mb-2" style="font-size:2rem"></i>
                            Belum ada jamaah untuk mitra ini.
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
                @if($jamaah->total() > 0)
                    Menampilkan {{ $jamaah->firstItem() }} - {{ $jamaah->lastItem() }}
                    dari {{ $jamaah->total() }} entri
                @else
                    Menampilkan 0 entri
                @endif
            </span>
            @if($jamaah->hasPages())
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
            @endif
        </div>
    </div>

</div>
@endsection