{{-- resources/views/jamaah/invoice.blade.php --}}
{{-- Print-ready invoice, layout persis contoh Elsafa --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice - {{ $jamaah->nama_lengkap }}</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman&family=Arial:wght@400;700&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: Arial, sans-serif;
        font-size: 11pt;
        color: #000;
        background: #fff;
        padding: 0;
    }

    .page {
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto;
        padding: 20mm 20mm 20mm 25mm;
        background: #fff;
    }

    /* ── HEADER ── */
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .company-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-img {
        width: 70px;
        height: 70px;
        object-fit: contain;
    }

    .company-info h1 {
        font-size: 20pt;
        font-weight: 900;
        color: #C9A84C;
        letter-spacing: 2px;
        line-height: 1;
    }

    .company-info p {
        font-size: 8pt;
        color: #444;
        margin-top: 2px;
    }

    .invoice-label-top {
        text-align: right;
    }

    .invoice-label-top h2 {
        font-size: 22pt;
        font-weight: 700;
        color: #1C2536;
        letter-spacing: 3px;
    }

    /* ── DIVIDER ── */
    .divider-gold {
        border: none;
        border-top: 3px solid #C9A84C;
        margin: 8px 0 14px 0;
    }

    .divider-thin {
        border: none;
        border-top: 1px solid #ccc;
        margin: 10px 0;
    }

    /* ── INVOICE META ── */
    .invoice-meta {
        margin-bottom: 16px;
    }

    .invoice-meta table {
        border-collapse: collapse;
    }

    .invoice-meta td {
        padding: 2px 0;
        font-size: 11pt;
        vertical-align: top;
    }

    .invoice-meta td:first-child {
        font-weight: 700;
        width: 160px;
        color: #1C2536;
    }

    .invoice-meta td.sep {
        width: 20px;
        text-align: center;
    }

    /* ── MAIN TABLE ── */
    .main-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    .main-table thead tr {
        background: #1C2536;
        color: #fff;
    }

    .main-table thead th {
        padding: 7px 8px;
        font-size: 10pt;
        font-weight: 700;
        text-align: center;
        border: 1px solid #1C2536;
    }

    .main-table thead th:nth-child(3) { text-align: left; }

    .main-table tbody td {
        padding: 6px 8px;
        font-size: 10pt;
        border: 1px solid #ccc;
        vertical-align: middle;
    }

    .main-table .row-category {
        background: #F5EDD6;
        font-weight: 700;
        font-size: 10pt;
        color: #1C2536;
    }

    .main-table .row-category td {
        padding: 5px 8px;
        border: 1px solid #ccc;
    }

    .main-table .row-total-tagihan td {
        font-weight: 700;
        background: #EEEEEE;
        border: 1px solid #999;
    }

    .main-table .row-sisa td {
        font-weight: 700;
        background: #fff;
        border: 1px solid #ccc;
    }

    .text-center { text-align: center; }
    .text-right  { text-align: right; }
    .text-left   { text-align: left; }

    /* ── CICILAN TABLE ── */
    .cicilan-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0;
    }

    .cicilan-table td {
        padding: 5px 8px;
        font-size: 10pt;
        border: 1px solid #ccc;
        vertical-align: top;
    }

    .cicilan-table .cicilan-header {
        background: #F5EDD6;
        font-weight: 700;
        font-size: 9.5pt;
        color: #1C2536;
    }

    .cicilan-table .cicilan-total {
        font-weight: 700;
        background: #EEEEEE;
    }

    /* ── KETERANGAN ── */
    .section-title {
        font-weight: 700;
        font-size: 11pt;
        margin-bottom: 4px;
        color: #1C2536;
    }

    .keterangan-list {
        margin-left: 2px;
        margin-bottom: 6px;
    }

    .keterangan-list li {
        font-size: 10pt;
        margin-bottom: 3px;
        list-style: none;
        padding-left: 12px;
        text-indent: -12px;
    }

    /* ── REKENING ── */
    .rekening-section {
        margin: 10px 0;
    }

    .rekening-section .rek-title {
        font-weight: 700;
        font-size: 11pt;
        text-decoration: underline;
        margin-bottom: 4px;
    }

    .rekening-item {
        font-size: 10pt;
        margin-bottom: 2px;
        display: flex;
        gap: 8px;
    }

    .rekening-item .rek-label { width: 24px; text-align: right; }
    .rekening-item .rek-bank  { width: 40px; font-weight: 700; }
    .rekening-item .rek-sep   { width: 12px; }

    /* ── BOOKING ── */
    .booking-info {
        margin: 8px 0;
    }

    .booking-info table td {
        font-size: 10pt;
        padding: 2px 0;
        vertical-align: top;
    }

    .booking-info table td:first-child {
        width: 200px;
        font-weight: 600;
    }

    .booking-info table td.sep {
        width: 20px;
    }

    /* ── TTD ── */
    .ttd-section {
        display: flex;
        justify-content: flex-end;
        margin-top: 16px;
    }

    .ttd-box {
        text-align: center;
        width: 200px;
    }

    .ttd-box .ttd-name {
        font-weight: 700;
        font-size: 11pt;
        margin-top: 50px; /* ruang ttd */
        border-top: 1px solid #000;
        padding-top: 4px;
    }

    .ttd-box .ttd-title {
        font-size: 10pt;
        font-weight: 700;
    }

    /* ── FOOTER COMPANY ── */
    .footer-company {
        margin-top: 20px;
        border-top: 2px solid #C9A84C;
        padding-top: 8px;
        font-size: 9pt;
        color: #444;
        text-align: center;
    }

    .footer-company strong {
        font-size: 10pt;
        color: #1C2536;
    }

    /* ── PRINT ── */
    @media print {
        body { padding: 0; }
        .page {
            width: 100%;
            padding: 15mm 18mm 15mm 20mm;
            margin: 0;
        }
        .no-print { display: none; }
    }

    @page {
        size: A4;
        margin: 0;
    }
</style>
</head>
<body>

{{-- PRINT BUTTON (hilang saat print) --}}
<div class="no-print" style="text-align:center;padding:14px;background:#f0f2f5;border-bottom:1px solid #ddd">
    <button onclick="window.print()"
            style="background:#1C2536;color:#fff;border:none;padding:10px 28px;border-radius:6px;font-size:14px;cursor:pointer;font-weight:600">
        🖨️ Cetak Invoice
    </button>
    <button onclick="window.close()"
            style="background:#fff;color:#1C2536;border:1.5px solid #1C2536;padding:10px 20px;border-radius:6px;font-size:14px;cursor:pointer;font-weight:600;margin-left:10px">
        ✕ Tutup
    </button>
</div>

<div class="page">

    {{-- ═══ HEADER ═══ --}}
    <div class="header-top">
        <div class="company-logo">
            <div class="company-info">
                <h1>ELSAFA</h1>
                <p>PT. ELSAFA TOUR DAN TRAVEL</p>
                <p style="font-size:7.5pt;color:#666;margin-top:2px">
                    Jl. Taman Karya Ruko No. 8, Simpang Perumahan Mas Raya<br>
                    Kec. Tampan, Kota Pekanbaru, Provinsi Riau<br>
                    Email: travelelsafa@gmail.com
                </p>
            </div>
        </div>
        <div class="invoice-label-top">
            <h2>INVOICE</h2>
            <div style="font-size:9pt;color:#666;margin-top:6px">
                No: {{ $nomorInvoice }}
            </div>
        </div>
    </div>

    <hr class="divider-gold">

    {{-- ═══ INVOICE META ═══ --}}
    <div class="invoice-meta">
        <table>
            <tr>
                <td>Invoice Number</td>
                <td class="sep">:</td>
                <td><strong>{{ $nomorInvoice }}</strong></td>
            </tr>
            <tr>
                <td>Date</td>
                <td class="sep">:</td>
                <td>{{ strtoupper(now()->locale('id')->isoFormat('D MMMM YYYY')) }}</td>
            </tr>
            <tr>
                <td>Invoice to</td>
                <td class="sep">:</td>
                <td><strong>{{ $jamaah->nama_lengkap }}</strong></td>
            </tr>
            @if($jamaah->kontak)
            <tr>
                <td>Kontak</td>
                <td class="sep">:</td>
                <td>{{ $jamaah->kontak }}</td>
            </tr>
            @endif
            @if($jamaah->mitra)
            <tr>
                <td>Melalui Mitra</td>
                <td class="sep">:</td>
                <td>{{ $jamaah->mitra->nama_mitra }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- ═══ TABEL UTAMA PAKET ═══ --}}
    @php
        $hargaPaket  = $jamaah->paket->harga ?? 0;
        $totalBayar  = $jamaah->pembayaran->sum('jumlah_bayar');
        $sisaTagihan = max(0, $hargaPaket - $totalBayar);
        $depDate     = $jamaah->paket->tanggal_keberangkatan ?? null;
    @endphp

    <table class="main-table">
        <thead>
            <tr>
                <th style="width:30px">NO</th>
                <th style="width:90px">DEP DATE</th>
                <th>DESCRIPTION</th>
                <th style="width:35px">QTY</th>
                <th style="width:110px">PRICE</th>
                <th style="width:110px">AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            {{-- Judul kategori --}}
            <tr class="row-category">
                <td colspan="6">
                    Pendaftaran paket {{ $jamaah->paket->jenis ?? 'umroh' }}
                    {{ $depDate ? strtoupper(\Carbon\Carbon::parse($depDate)->locale('id')->isoFormat('MMMM YYYY')) : '' }}
                </td>
            </tr>

            {{-- Row paket --}}
            <tr>
                <td class="text-center">1</td>
                <td class="text-center">
                    {{ $depDate ? strtoupper(\Carbon\Carbon::parse($depDate)->locale('id')->isoFormat('MMMM YYYY')) : '-' }}
                </td>
                <td>
                    Pendaftaran {{ strtolower($jamaah->paket->jenis ?? 'umroh') }}
                    — {{ $jamaah->paket->nama_paket ?? '-' }}
                </td>
                <td class="text-center">1</td>
                <td class="text-right">Rp {{ number_format($hargaPaket, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($hargaPaket, 0, ',', '.') }}</td>
            </tr>

            {{-- Spacer rows --}}
            <tr>
                <td colspan="5" style="border-right:none">&nbsp;</td>
                <td style="border-left:none"></td>
            </tr>
            <tr>
                <td colspan="5" style="border-right:none">&nbsp;</td>
                <td style="border-left:none"></td>
            </tr>

            {{-- TOTAL TAGIHAN --}}
            <tr class="row-total-tagihan">
                <td colspan="5" class="text-right" style="font-weight:700">TOTAL TAGIHAN</td>
                <td class="text-right" style="font-weight:700">
                    Rp {{ number_format($hargaPaket, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- ═══ TABEL CICILAN / RIWAYAT PEMBAYARAN ═══ --}}
    <table class="cicilan-table">
        <tr class="cicilan-header">
            <td style="width:130px">Tanggal</td>
            <td>Keterangan</td>
            <td style="width:130px;text-align:right">Jumlah</td>
        </tr>

        @foreach($jamaah->pembayaran->sortBy('tanggal_bayar') as $p)
        <tr>
            <td>{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}</td>
            <td>{{ $p->catatan ?? $p->metode }}</td>
            <td class="text-right">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
        </tr>
        @endforeach

        @if($jamaah->pembayaran->isEmpty())
        <tr>
            <td colspan="3" style="text-align:center;color:#999;font-style:italic">
                Belum ada pembayaran
            </td>
        </tr>
        @endif

        {{-- TOTAL --}}
        <tr class="cicilan-total">
            <td colspan="2" class="text-right">TOTAL</td>
            <td class="text-right">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
        </tr>

        {{-- SISA TAGIHAN --}}
        <tr>
            <td colspan="2" class="text-right" style="font-weight:700">Sisa Tagihan</td>
            <td class="text-right" style="font-weight:700;color:{{ $sisaTagihan > 0 ? '#C0392B' : '#2E7D52' }}">
                @if($sisaTagihan > 0)
                    Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                @else
                    <span style="color:#2E7D52">✓ LUNAS</span>
                @endif
            </td>
        </tr>
    </table>

    <hr class="divider-thin" style="margin-top:14px">

    {{-- ═══ KETERANGAN ═══ --}}
    <div style="margin-top:10px">
        <div class="section-title">Keterangan :</div>
        <div style="font-size:10pt;margin-bottom:6px">Ketentuan Reservasi dan Pembayaran</div>
        <ul class="keterangan-list">
            <li>- &nbsp;Pembayaran Deposit Rp. 5.000.000 dilakukan 2 Hari setelah invoice diterbitkan</li>
            <li>- &nbsp;Pelunasan sisa pembayaran di lakukan paling lambat H-1 bulan sebelum keberangkatan.</li>
        </ul>
    </div>

    <hr class="divider-thin">

    {{-- ═══ REKENING PEMBAYARAN ═══ --}}
    <div class="rekening-section">
        <div class="rek-title">REKENING PEMBAYARAN</div>
        <div class="rekening-item" style="margin-top:4px">
            <span class="rek-label">-</span>
            <span class="rek-bank">BRI</span>
            <span class="rek-sep">:</span>
            <span>109801001199561 &nbsp;<em>(a.n PT. ELSAFA TOUR DAN TRAVEL)</em></span>
        </div>
    </div>

    <hr class="divider-thin">

    {{-- ═══ BOOKING & PAYMENT CONFIRMATION ═══ --}}
    <div class="booking-info">
        <table>
            <tr>
                <td>Booking Payment</td>
                <td class="sep">:</td>
                <td>ASRI HARSADI</td>
            </tr>
            <tr>
                <td>Payment Confirmation</td>
                <td class="sep">:</td>
                <td>ASRI HARSADI &nbsp;&nbsp; 0852-7158-6343</td>
            </tr>
        </table>
    </div>

    {{-- ═══ TTD ═══ --}}
    <div class="ttd-section">
        <div class="ttd-box">
            <div style="font-size:10pt">Pekanbaru, {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
            <div class="ttd-name">ASRI HARSADI, S.Pi</div>
            <div class="ttd-title">DIREKTUR</div>
            <div style="font-size:9.5pt;margin-top:2px">PT. ELSAFA TOUR DAN TRAVEL</div>
        </div>
    </div>

    {{-- ═══ FOOTER PERUSAHAAN ═══ --}}
    <div class="footer-company">
        <strong>PT. ELSAFA TOUR DAN TRAVEL</strong><br>
        Jl. Taman Karya Ruko No. 8 &nbsp;|&nbsp; Simpang Perumahan Mas Raya Kec. Tampan, Kota Pekanbaru, Provinsi Riau<br>
        Email: travelelsafa@gmail.com
    </div>

</div>

</body>
</html>