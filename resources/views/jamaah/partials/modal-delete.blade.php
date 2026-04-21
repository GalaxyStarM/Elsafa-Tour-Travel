<div class="modal fade" id="deletePembayaranModal">
<div class="modal-dialog modal-dialog-centered">
<form method="POST" id="deletePembayaranForm">
@csrf @method('DELETE')

<div class="modal-content p-4 text-center">
    <div class="delete-icon-wrap">
        <i class="bi bi-trash"></i>
    </div>

    <p>Yakin hapus pembayaran ini?</p>

    <div class="d-flex gap-2 justify-content-center">
        <button class="btn-elsafa-primary">Hapus</button>
        <button type="button" data-bs-dismiss="modal" class="btn-elsafa-outline">Batal</button>
    </div>
</div>

</form>
</div>
</div>