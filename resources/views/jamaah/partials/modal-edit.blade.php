<div class="modal fade" id="editPayModal">
<div class="modal-dialog">
<form method="POST" id="editPayForm">
@csrf @method('PUT')

<div class="modal-content p-4">
<h6 class="fw-bold mb-3">Edit Pembayaran</h6>

<input type="date" id="editTanggal" name="tanggal_bayar" class="form-control mb-2">
<input type="number" id="editJumlah" name="jumlah_bayar" class="form-control mb-2">

<select id="editMetode" name="metode" class="form-select mb-2">
<option>Transfer Bank</option>
<option>Tunai</option>
</select>

<input type="text" id="editCatatan" name="catatan" class="form-control mb-3">

<button class="btn-elsafa-primary w-100">Update</button>
</div>

</form>
</div>
</div>