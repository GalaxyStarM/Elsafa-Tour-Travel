<div class="table-responsive">
<table class="table-elsafa">
    <thead>
        <tr>
            <th>NO</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Metode</th>
            <th>Catatan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    @forelse($pembayaran as $i => $p)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $p->tanggal_bayar->format('d-m-Y') }}</td>
            <td class="fw-bold">{{ $p->jumlah_bayar_formatted }}</td>
            <td>{{ $p->metode }}</td>
            <td>{{ $p->catatan ?? '-' }}</td>
            <td>
                <div class="d-flex gap-1">
                    <button class="btn-action btn-action-edit"
                        onclick="openEditModal({{ $p->id }}, '{{ $p->tanggal_bayar->format('Y-m-d') }}', {{ $p->jumlah_bayar }}, '{{ $p->metode }}', '{{ addslashes($p->catatan) }}')">
                        <i class="bi bi-pencil"></i>
                    </button>

                    <button class="btn-action btn-action-delete"
                        onclick="confirmDeletePembayaran({{ $p->id }})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center text-muted py-4">
                Belum ada pembayaran
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>