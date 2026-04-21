<div class="text-center mb-3">
    <div class="photo-wrap">
        <div class="photo-avatar" id="photoPreview">
            @if($jamaah->foto_profil)
                <img src="{{ Storage::url($jamaah->foto_profil) }}">
            @else
                <i class="bi bi-person-fill"></i>
            @endif
        </div>

        <label for="fotoInput" class="photo-camera-btn">
            <i class="bi bi-camera-fill"></i>
        </label>

        <input type="file" id="fotoInput" hidden onchange="uploadFoto(this)">
    </div>

    <h5 class="fw-bold mt-2">{{ $jamaah->nama_lengkap }}</h5>

    <div class="profile-kontak">
        {{ $jamaah->kontak_formatted ?? $jamaah->kontak }}
    </div>

    <span class="{{ $jamaah->jenis_jamaah === 'Mandiri' ? 'badge-mandiri' : 'badge-mitra' }}">
        {{ $jamaah->jenis_jamaah }}
    </span>

    <div class="d-flex justify-content-center gap-2 flex-wrap mt-2">
        <span class="px-3 py-1 rounded-pill bg-light">
            {{ $jamaah->paket->nama_paket ?? '-' }}
        </span>
        <span class="px-3 py-1 rounded-pill bg-light">
            {{ $jamaah->status_jamaah }}
        </span>
    </div>
</div>