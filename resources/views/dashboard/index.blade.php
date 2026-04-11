@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card navy">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-label">Total Jamaah</div>
                <div class="stat-value">{{ number_format($totalJamaah) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="stat-label">Jamaah Lunas</div>
                <div class="stat-value">{{ number_format($jamaahLunas) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card gold">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-label">Belum Lunas</div>
                <div class="stat-value">{{ number_format($jamaahBelumLunas) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Ringkasan Pembayaran --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:var(--navy);">
                    <i class="bi bi-receipt me-2" style="color:var(--gold);"></i>Ringkasan Pembayaran
                </h6>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:13px;">Total Pembayaran</div>
                        <div class="fw-bold" style="font-size:16px;color:var(--gold);">
                            IDR {{ number_format($totalPembayaran, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:13px;">Tunggakan Jamaah</div>
                        <div class="fw-bold" style="font-size:16px;color:#dc3545;">
                            IDR {{ number_format($tunggakan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @php
                    $total = $totalPembayaran + $tunggakan;
                    $pct   = $total > 0 ? round($totalPembayaran / $total * 100) : 0;
                @endphp
                <div class="d-flex rounded-pill overflow-hidden" style="height:10px;">
                    <div @style(['width:'.$pct.'%', 'background:var(--navy)'])></div>
                    <div @style(['width:'.(100-$pct).'%', 'background:var(--gold)'])></div>
                </div>
                <div class="d-flex justify-content-between mt-1" style="font-size:11px;color:#adb5bd;">
                    <span>Terbayar {{ $pct }}%</span>
                    <span>Tunggakan {{ 100-$pct }}%</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Perlu Perhatian --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:var(--navy);">
                    <i class="bi bi-bell-fill me-2" style="color:var(--gold);"></i>Perlu Perhatian
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between align-items-center py-2"
                        style="border-bottom:1px solid #f0f0f0;font-size:13.5px;">
                        <span><i class="bi bi-file-earmark-x text-danger me-2"></i>Dokumen tidak lengkap</span>
                        <strong class="text-danger">{{ $dokumenTidakLengkap }}</strong>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-2"
                        style="border-bottom:1px solid #f0f0f0;font-size:13.5px;">
                        <span><i class="bi bi-cash-stack text-warning me-2"></i>Belum ada pembayaran</span>
                        <strong class="text-warning">{{ $belumAdaPembayaran }}</strong>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-2 font-size:13.5px;">
                        <span><i class="bi bi-clock-history text-danger me-2"></i>Cicilan terakhir >30 hari</span>
                        <strong class="text-danger">{{ $cicilan30Hari }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Paket Aktif --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3" style="color:var(--navy);">
            <i class="bi bi-box-seam-fill me-2" style="color:var(--gold);"></i>Paket Aktif
        </h6>
        <div class="table-responsive">
            <table class="table table-elsafa rounded-3 overflow-hidden mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">NO.</th>
                        <th>Nama Paket</th>
                        <th>Jenis</th>
                        <th>Total Jamaah</th>
                        <th>Kuota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paketAktif as $i => $paket)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $paket->nama }}</td>
                        <td>{{ ucfirst($paket->jenis) }}</td>
                        <td>{{ $paket->jamaah_count }}</td>
                        <td>{{ $paket->kuota }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-inbox me-1"></i> Belum ada paket aktif.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection