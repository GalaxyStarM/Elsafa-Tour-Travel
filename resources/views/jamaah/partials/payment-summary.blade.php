@php
$total = $jamaah->paket->harga ?? 0;
$paid = $jamaah->total_pembayaran;
$sisa = max(0, $total - $paid);
@endphp

<div class="px-4 py-3 d-flex gap-4 flex-wrap bg-light border-bottom">
    <div>
        <small>Total Paket</small>
        <div class="fw-bold">Rp {{ number_format($total,0,',','.') }}</div>
    </div>

    <div>
        <small>Sudah Dibayar</small>
        <div class="fw-bold text-success">Rp {{ number_format($paid,0,',','.') }}</div>
    </div>

    <div>
        <small>Sisa</small>
        <div class="fw-bold {{ $sisa ? 'text-danger' : 'text-success' }}">
            Rp {{ number_format($sisa,0,',','.') }}
        </div>
    </div>

    <div class="ms-auto">
        <span class="{{ $jamaah->status_pembayaran == 'Lunas' ? 'bdg-lunas' : 'bdg-belum' }}">
            {{ $jamaah->status_pembayaran }}
        </span>
    </div>
</div>