{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="d-flex flex-column gap-4">

    {{-- ═══ STAT CARDS ═══ --}}
    <div class="row g-3">

        {{-- Total Jamaah --}}
        <div class="col-md-4">
            <div class="stat-card stat-dark">
                <div class="stat-icon-wrap">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Total Jamaah</div>
                    <div class="stat-value">{{ number_format($totalJamaah) }}</div>
                </div>
            </div>
        </div>

        {{-- Jamaah Lunas --}}
        <div class="col-md-4">
            <div class="stat-card stat-green">
                <div class="stat-icon-wrap">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Jamaah Lunas</div>
                    <div class="stat-value">{{ number_format($jamaahLunas) }}</div>
                </div>
            </div>
        </div>

        {{-- Belum Lunas --}}
        <div class="col-md-4">
            <div class="stat-card stat-cream">
                <div class="stat-icon-wrap">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-body">
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
                    <i class="bi bi-receipt" style="font-size:1.2rem;color:var(--elsafa-gold)"></i>
                    <span class="fw-bold" style="font-size:1rem">Ringkasan Pembayaran</span>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-6">
                        <div style="font-size:.8rem;color:#64748B;font-weight:600;margin-bottom:4px">
                            Total Pembayaran
                        </div>
                        <div style="font-size:1.15rem;font-weight:700;color:var(--elsafa-dark)">
                            IDR {{ number_format($totalPembayaran, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:.8rem;color:#64748B;font-weight:600;margin-bottom:4px">
                            Tunggakan Jamaah
                        </div>
                        <div style="font-size:1.15rem;font-weight:700;color:var(--elsafa-red)">
                            IDR {{ number_format($tunggakan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="progress-wrap">
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $progressPersen }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span style="font-size:.75rem;color:#64748B">
                            Terkumpul {{ $progressPersen }}%
                        </span>
                        <span style="font-size:.75rem;color:#64748B">
                            Sisa {{ 100 - $progressPersen }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Perhatian --}}
        <div class="col-lg-5">
            <div class="card-elsafa p-4 h-100">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-bell-fill" style="font-size:1.1rem;color:var(--elsafa-gold)"></i>
                    <span class="fw-bold" style="font-size:1rem">Perlu Perhatian</span>
                </div>

                <div class="d-flex flex-column gap-2">

                    {{-- Dokumen tidak lengkap --}}
                    <a href="{{ route('jamaah.index', ['dokumen' => 'tidak_lengkap']) }}"
                       class="alert-item {{ $dokumenTidakLengkap > 0 ? 'alert-item-warning' : 'alert-item-ok' }}">
                        <i class="bi bi-file-earmark-x"></i>
                        <span>Dokumen tidak lengkap</span>
                        <span class="alert-count {{ $dokumenTidakLengkap > 0 ? 'count-warning' : 'count-ok' }}">
                            {{ $dokumenTidakLengkap }}
                        </span>
                    </a>

                    {{-- Belum ada pembayaran --}}
                    <a href="{{ route('jamaah.index', ['status_pembayaran' => 'Belum Lunas']) }}"
                       class="alert-item {{ $belumAdaPembayaran > 0 ? 'alert-item-danger' : 'alert-item-ok' }}">
                        <i class="bi bi-cash-stack"></i>
                        <span>Belum ada pembayaran</span>
                        <span class="alert-count {{ $belumAdaPembayaran > 0 ? 'count-danger' : 'count-ok' }}">
                            {{ $belumAdaPembayaran }}
                        </span>
                    </a>

                    {{-- Cicilan terakhir > 30 hari --}}
                    <a href="{{ route('jamaah.index') }}"
                       class="alert-item {{ $cicilan30Hari > 0 ? 'alert-item-warning' : 'alert-item-ok' }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Cicilan terakhir &gt;30 hari</span>
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
        <div class="d-flex align-items-center gap-2 p-4 pb-3"
             style="border-bottom:1px solid #F1F5F9">
            <i class="bi bi-box-seam" style="font-size:1.1rem;color:var(--elsafa-gold)"></i>
            <span class="fw-bold" style="font-size:1rem">Paket Aktif</span>
        </div>

        <div class="table-responsive">
            <table class="table-elsafa">
                <thead>
                    <tr>
                        <th style="width:55px">NO.</th>
                        <th>Nama Paket</th>
                        <th>Jenis</th>
                        <th>Tanggal Keberangkatan</th>
                        <th>Kuota</th>
                        <th>Total Jamaah</th>
                        <th>Sisa Kuota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paketAktif as $i => $paket)
                    <tr>
                        <td class="text-center fw-semibold" style="color:#94A3B8">{{ $i + 1 }}.</td>
                        <td class="fw-semibold">
                            <a href="{{ route('paket.index') }}" style="color:var(--elsafa-dark);text-decoration:none">
                                {{ $paket->nama_paket }}
                            </a>
                        </td>
                        <td>{{ $paket->jenis }}</td>
                        <td>
                            {{ $paket->tanggal_keberangkatan
                                ? \Carbon\Carbon::parse($paket->tanggal_keberangkatan)->locale('id')->isoFormat('D MMMM YYYY')
                                : '-' }}
                        </td>
                        <td class="text-center">{{ $paket->kuota ?? '—' }}</td>
                        <td class="text-center fw-semibold">{{ $paket->jamaah_count }}</td>
                        <td class="text-center">
                            @php $sisa = ($paket->kuota ?? 0) - $paket->jamaah_count; @endphp
                            @if($paket->kuota)
                                <span class="{{ $sisa <= 0 ? 'badge-belum-lunas' : ($sisa <= 10 ? '' : 'badge-lunas') }}"
                                      style="{{ $sisa > 0 && $sisa <= 10 ? 'background:#FEF3C7;color:#92400E;padding:4px 12px;border-radius:20px;font-size:.78rem;font-weight:600' : '' }}">
                                    {{ $sisa <= 0 ? 'Penuh' : $sisa }}
                                </span>
                            @else
                                <span style="color:#94A3B8">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4" style="color:#94A3B8">
                            <i class="bi bi-inbox" style="font-size:1.5rem;display:block;margin-bottom:6px"></i>
                            Belum ada paket aktif.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paketAktif->isNotEmpty())
        <div class="px-4 py-3" style="border-top:1px solid #F1F5F9">
            <a href="{{ route('paket.index') }}" style="font-size:.85rem;color:var(--elsafa-gold);text-decoration:none;font-weight:600">
                Lihat semua paket →
            </a>
        </div>
        @endif
    </div>

</div>
@endsection
