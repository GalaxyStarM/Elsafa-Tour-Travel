<div class="modal fade" id="addPayModal">
<div class="modal-dialog">
<form method="POST" action="{{ route('jamaah.pembayaran.store',$jamaah) }}">
@csrf

<div class="modal-content p-4">
<h6 class="fw-bold mb-3">Tambah Pembayaran</h6>

<input type="date" name="tanggal_bayar" class="form-control mb-2" required>
<input type="number" name="jumlah_bayar" class="form-control mb-2" required>

<select name="metode" class="form-select mb-2">
<option>Transfer Bank</option>
<option>Tunai</option>
</select>

<input type="text" name="catatan" class="form-control mb-3">

<button class="btn-elsafa-primary w-100">Simpan</button>
</div>

</form>
</div>
</div>