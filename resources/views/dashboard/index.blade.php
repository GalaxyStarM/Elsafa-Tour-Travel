{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="d-flex flex-column gap-4">

    {{-- ═══ STAT CARDS ═══ --}}
    <div class="row g-3">

        {{-- Total Jamaah --}}
        <div class="col-md-4">
            <div class="stat-card navy">
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Jamaah</div>
                    <div class="stat-value">{{ number_format($totalJamaah) }}</div>
                </div>
            </div>
        </div>

        {{-- Jamaah Lunas --}}
        <div class="col-md-4">
            <div class="stat-card green">
                <div class="stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Jamaah Lunas</div>
                    <div class="stat-value">{{ number_format($jamaahLunas) }}</div>
                </div>
            </div>
        </div>

        {{-- Belum Lunas --}}
        <div class="col-md-4">
            <div class="stat-card gold">
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="stat-label">Belum Lunas</div>
                    <div class="stat-value">{{ number_format($jamaahBelumLunas) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ ROW 2: RINGKASAN + ALERT ═══ --}}
    <div class="row g-3">

        {{-- Ringkasan Pembayaran --}}
        <div class="col-lg-7">
            <div class="card-elsafa p-4 h-100">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-receipt text-gold" style="font-size:1.2rem"></i>
                    <span class="fw-700" style="font-size:15px">Ringkasan Pembayaran</span>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:12px;font-weight:600">Total Pembayaran</div>
                        <div class="fw-700 text-navy" style="font-size:17px">
                            IDR {{ number_format($totalPembayaran, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:12px;font-weight:600">Tunggakan Jamaah</div>
                        <div class="fw-700 text-red" style="font-size:17px">
                            IDR {{ number_format($tunggakan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="progress-wrap">
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $progressPersen }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="progress-label">Terkumpul {{ $progressPersen }}%</span>
                        <span class="progress-label">Sisa {{ 100 - $progressPersen }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Perhatian --}}
        <div class="col-lg-5">
            <div class="card-elsafa p-4 h-100">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-bell-fill text-gold" style="font-size:1.1rem"></i>
                    <span class="fw-700" style="font-size:15px">Perlu Perhatian</span>
                </div>

                <div class="d-flex flex-column gap-2">

                    <a href="{{ route('jamaah.index', ['perhatian' => 'dokumen_tidak_lengkap']) }}"
                       class="alert-item {{ $dokumenTidakLengkap > 0 ? 'warning' : 'ok' }}">
                        <i class="bi bi-file-earmark-x"></i>
                        <span class="flex-grow-1">Dokumen tidak lengkap</span>
                        <span class="alert-count {{ $dokumenTidakLengkap > 0 ? 'count-warning' : 'count-ok' }}">
                            {{ $dokumenTidakLengkap }}
                        </span>
                    </a>

                    <a href="{{ route('jamaah.index', ['perhatian' => 'belum_ada_pembayaran']) }}"
                       class="alert-item {{ $belumAdaPembayaran > 0 ? 'danger' : 'ok' }}">
                        <i class="bi bi-cash-stack"></i>
                        <span class="flex-grow-1">Belum ada pembayaran</span>
                        <span class="alert-count {{ $belumAdaPembayaran > 0 ? 'count-danger' : 'count-ok' }}">
                            {{ $belumAdaPembayaran }}
                        </span>
                    </a>

                    <a href="{{ route('jamaah.index', ['perhatian' => 'cicilan_30_hari']) }}"
                       class="alert-item {{ $cicilan30Hari > 0 ? 'warning' : 'ok' }}">
                        <i class="bi bi-clock-history"></i>
                        <span class="flex-grow-1">Cicilan terakhir &gt;30 hari</span>
                        <span class="alert-count {{ $cicilan30Hari > 0 ? 'count-warning' : 'count-ok' }}">
                            {{ $cicilan30Hari }}
                        </span>
                    </a>

                </div>
            </div>
        </div>
    </div>

    {{-- ═══ PAKET AKTIF ═══ --}}
    <div class="card-elsafa">
        <div class="d-flex align-items-center gap-2 p-4 pb-3" style="border-bottom:1px solid #f0f0f0">
            <i class="bi bi-box-seam text-gold" style="font-size:1.1rem"></i>
            <span class="fw-700" style="font-size:15px">Paket Aktif</span>
        </div>

        <div class="table-responsive">
            <table class="table-elsafa">
                <thead>
                    <tr>
                        <th style="width:55px">NO.</th>
                        <th>Nama Paket</th>
                        <th>Jenis</th>
                        <th>Tanggal Keberangkatan</th>
                        <th class="text-center">Kuota</th>
                        <th class="text-center">Total Jamaah</th>
                        <th class="text-center">Sisa Kuota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paketAktif as $i => $paket)
                    <tr>
                        <td class="text-center" style="color:#adb5bd;font-weight:600">{{ $i + 1 }}.</td>
                        <td class="fw-600">
                            <a href="{{ route('paket.index') }}" class="text-navy" style="text-decoration:none">
                                {{ $paket->nama }}
                            </a>
                        </td>
                        <td>{{ ucfirst($paket->jenis) }}</td>
                        <td>
                            {{ $paket->tanggal_keberangkatan
                                ? \Carbon\Carbon::parse($paket->tanggal_keberangkatan)->locale('id')->isoFormat('D MMMM YYYY')
                                : '-' }}
                        </td>
                        <td class="text-center">{{ $paket->kuota ?? '—' }}</td>
                        <td class="text-center fw-600">{{ $paket->jamaah_count }}</td>
                        <td class="text-center">
                            @php $sisa = ($paket->kuota ?? 0) - $paket->jamaah_count; @endphp
                            @if($paket->kuota)
                                @if($sisa <= 0)
                                    <span class="bdg bdg-nonaktif">Penuh</span>
                                @elseif($sisa <= 10)
                                    <span class="bdg bdg-belum">{{ $sisa }}</span>
                                @else
                                    <span class="fw-600">{{ $sisa }}</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4" style="color:#adb5bd">
                            <i class="bi bi-inbox d-block mb-2" style="font-size:1.5rem"></i>
                            Belum ada paket aktif.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paketAktif->isNotEmpty())
        <div class="px-4 py-3" style="border-top:1px solid #f0f0f0">
            <a href="{{ route('paket.index') }}" class="text-gold fw-600" style="font-size:13.5px;text-decoration:none">
                Lihat semua paket →
            </a>
        </div>
        @endif
    </div>

</div>
@endsection