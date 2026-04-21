{{-- resources/views/jamaah/create.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Tambah Jamaah')

@section('content')
<div style="max-width:720px;margin:auto">
    <div class="card-elsafa">
        <div class="card-body">

            <form method="POST" action="{{ route('jamaah.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- NAMA LENGKAP --}}
                <div class="mb-4">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control"
                           value="{{ old('nama_lengkap') }}" required>
                    @error('nama_lengkap')
                        <div class="text-danger mt-1" style="font-size:.8rem">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KONTAK --}}
                <div class="mb-4">
                    <label class="form-label">Kontak</label>
                    <div class="phone-wrap">
                        <div class="phone-prefix">🇮🇩 +62</div>
                        <input type="text" name="kontak" class="phone-input"
                               placeholder="8xxxxxxxxxx" value="{{ old('kontak') }}">
                    </div>
                </div>

                {{-- PAKET --}}
                <div class="mb-4">
                    <label class="form-label">Paket <span class="text-danger">*</span></label>
                    <select name="paket_id" class="form-select" required>
                        <option value="">Pilih Paket</option>
                        @foreach($paket as $p)
                            <option value="{{ $p->id }}" {{ old('paket_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} — Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('paket_id')
                        <div class="text-danger mt-1" style="font-size:.8rem">{{ $message }}</div>
                    @enderror
                </div>

                {{-- JENIS JAMAAH --}}
                <div class="mb-4">
                    <label class="form-label">Jenis Jamaah <span class="text-danger">*</span></label>
                    <div class="d-flex gap-4">
                        <label class="d-flex align-items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_jamaah" value="Mandiri"
                                   {{ old('jenis_jamaah', 'Mandiri') === 'Mandiri' ? 'checked' : '' }}
                                   onchange="toggleMitra(this.value)"> Mandiri
                        </label>
                        <label class="d-flex align-items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_jamaah" value="Mitra"
                                   {{ old('jenis_jamaah') === 'Mitra' ? 'checked' : '' }}
                                   onchange="toggleMitra(this.value)"> Mitra
                        </label>
                    </div>

                    <div id="mitraSelect" class="mt-3" style="display:none">
                        <select name="mitra_id" class="form-select">
                            <option value="">Pilih Mitra</option>
                            @foreach($mitra as $m)
                                <option value="{{ $m->id }}" {{ old('mitra_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status_jamaah" class="form-select">
                        <option value="Akan Berangkat">Akan Berangkat</option>
                        <option value="Selesai" {{ old('status_jamaah') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                {{-- KATEGORI USIA --}}
                <div class="mb-4">
                    <label class="form-label">Kategori Usia <span class="text-danger">*</span></label>
                    <div class="d-flex gap-4">
                        <label class="d-flex align-items-center gap-2 cursor-pointer">
                            <input type="radio" name="kategori_usia" value="Anak-anak"
                                   {{ old('kategori_usia') === 'Anak-anak' ? 'checked' : '' }}
                                   onchange="toggleDokumen(this.value)"> Anak-anak
                        </label>
                        <label class="d-flex align-items-center gap-2 cursor-pointer">
                            <input type="radio" name="kategori_usia" value="Dewasa"
                                   {{ old('kategori_usia', 'Dewasa') === 'Dewasa' ? 'checked' : '' }}
                                   onchange="toggleDokumen(this.value)"> Dewasa
                        </label>
                    </div>
                </div>

                {{-- DOKUMEN DEWASA --}}
                <div id="dokumen-dewasa" class="mb-4">
                    <p class="fw-600 mb-3">Dokumen Dewasa</p>

                    @foreach(['ktp' => 'KTP', 'kk' => 'KK', 'paspor' => 'Paspor'] as $key => $label)
                        <div class="mb-3">
                            <label class="form-label">
                                {{ $label }} <small class="text-muted">(JPG/PNG • Maks 5MB)</small>
                            </label>
                            <div class="upload-box" onclick="this.querySelector('input').click()">
                                <i class="bi bi-upload upload-icon"></i>
                                <p>Klik atau drag file {{ $label }}</p>
                                <small>Upload dokumen</small>
                                <input type="file" name="dokumen_{{ $key }}" hidden>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- DOKUMEN ANAK --}}
                <div id="dokumen-anak" class="mb-4" style="display:none">
                    <p class="fw-600 mb-3">Dokumen Anak-anak</p>

                    @foreach(['akta_kelahiran' => 'Akta Kelahiran', 'kk_anak' => 'KK', 'paspor_anak' => 'Paspor'] as $key => $label)
                        <div class="mb-3">
                            <label class="form-label">
                                {{ $label }} <small class="text-muted">(JPG/PNG • Maks 5MB)</small>
                            </label>
                            <div class="upload-box" onclick="this.querySelector('input').click()">
                                <i class="bi bi-upload upload-icon"></i>
                                <p>Klik atau drag file {{ $label }}</p>
                                <small>Upload dokumen</small>
                                <input type="file" name="dokumen_{{ $key }}" hidden>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- SUBMIT --}}
                <div class="d-flex justify-content-end gap-3 mt-4 pt-3" style="border-top:1px solid #F1F5F9">
                    <a href="{{ route('jamaah.index') }}" class="btn-elsafa-outline">Batal</a>
                    <button type="submit" class="btn-elsafa">
                        <i class="bi bi-check-lg"></i> Simpan Jamaah
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleMitra(val) {
    document.getElementById('mitraSelect').style.display = val === 'Mitra' ? 'block' : 'none';
}

function toggleDokumen(val) {
    const isDewasa = val === 'Dewasa';
    document.getElementById('dokumen-dewasa').style.display = isDewasa ? 'block' : 'none';
    document.getElementById('dokumen-anak').style.display = isDewasa ? 'none' : 'block';
}

document.addEventListener('DOMContentLoaded', function() {
    const jenis = document.querySelector('input[name="jenis_jamaah"]:checked');
    if (jenis) toggleMitra(jenis.value);

    const usia = document.querySelector('input[name="kategori_usia"]:checked');
    if (usia) toggleDokumen(usia.value);
});
</script>
@endpush