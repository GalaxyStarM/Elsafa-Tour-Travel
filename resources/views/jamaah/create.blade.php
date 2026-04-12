{{-- resources/views/jamaah/create.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Tambah Jamaah')

@section('content')
<div style="max-width:760px">
    <div class="card-elsafa p-4">
        <form method="POST" action="{{ route('jamaah.store') }}" enctype="multipart/form-data" id="createForm">
            @csrf

            {{-- NAMA LENGKAP --}}
            <div class="mb-4">
                <label class="form-label-elsafa">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" class="form-control-elsafa"
                       value="{{ old('nama_lengkap') }}" required>
                @error('nama_lengkap')
                    <div class="text-danger mt-1" style="font-size:.8rem">{{ $message }}</div>
                @enderror
            </div>

            {{-- KONTAK --}}
            <div class="mb-4">
                <label class="form-label-elsafa">Kontak</label>
                <div class="phone-wrap">
                    <div class="phone-prefix">🇮🇩 +62</div>
                    <input type="text" name="kontak" class="phone-input"
                           placeholder="8xxxxxxxxxx" value="{{ old('kontak') }}">
                </div>
            </div>

            {{-- PAKET --}}
            <div class="mb-4">
                <label class="form-label-elsafa">Paket <span class="text-danger">*</span></label>
                <div class="position-relative">
                    <select name="paket_id" class="form-control-elsafa" required style="appearance:none">
                        <option value="">Pilih Paket</option>
                        @foreach($pakets as $p)
                            <option value="{{ $p->id }}" {{ old('paket_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_paket }} — Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    <i class="bi bi-chevron-down position-absolute" style="right:14px;top:50%;transform:translateY(-50%);color:#94A3B8;pointer-events:none"></i>
                </div>
                @error('paket_id')
                    <div class="text-danger mt-1" style="font-size:.8rem">{{ $message }}</div>
                @enderror
            </div>

            {{-- JENIS JAMAAH --}}
            <div class="mb-4">
                <label class="form-label-elsafa">Jenis Jamaah <span class="text-danger">*</span></label>
                <div class="d-flex gap-4">
                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.9rem">
                        <input type="radio" name="jenis_jamaah" value="Mandiri"
                               {{ old('jenis_jamaah', 'Mandiri') === 'Mandiri' ? 'checked' : '' }}
                               onchange="toggleMitra(this.value)"> Mandiri
                    </label>
                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.9rem">
                        <input type="radio" name="jenis_jamaah" value="Mitra"
                               {{ old('jenis_jamaah') === 'Mitra' ? 'checked' : '' }}
                               onchange="toggleMitra(this.value)"> Mitra
                    </label>
                </div>
                <div id="mitraSelect" class="mt-3" style="{{ old('jenis_jamaah') === 'Mitra' ? '' : 'display:none' }}">
                    <div class="position-relative">
                        <select name="mitra_id" class="form-control-elsafa" style="appearance:none">
                            <option value="">Pilih Mitra</option>
                            @foreach($mitras as $m)
                                <option value="{{ $m->id }}" {{ old('mitra_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama_mitra }}
                                </option>
                            @endforeach
                        </select>
                        <i class="bi bi-chevron-down position-absolute" style="right:14px;top:50%;transform:translateY(-50%);color:#94A3B8;pointer-events:none"></i>
                    </div>
                </div>
            </div>

            {{-- STATUS --}}
            <div class="mb-4">
                <label class="form-label-elsafa">Status</label>
                <div class="position-relative">
                    <select name="status_jamaah" class="form-control-elsafa" style="appearance:none">
                        <option value="Akan Berangkat">Akan Berangkat</option>
                        <option value="Selesai" {{ old('status_jamaah') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <i class="bi bi-chevron-down position-absolute" style="right:14px;top:50%;transform:translateY(-50%);color:#94A3B8;pointer-events:none"></i>
                </div>
            </div>

            {{-- KATEGORI USIA --}}
            <div class="mb-4">
                <label class="form-label-elsafa">Kategori Usia <span class="text-danger">*</span></label>
                <div class="d-flex gap-4">
                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.9rem">
                        <input type="radio" name="kategori_usia" value="Anak-anak"
                               {{ old('kategori_usia') === 'Anak-anak' ? 'checked' : '' }}
                               onchange="toggleDokumen(this.value)"> Anak-anak
                    </label>
                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.9rem">
                        <input type="radio" name="kategori_usia" value="Dewasa"
                               {{ old('kategori_usia', 'Dewasa') === 'Dewasa' ? 'checked' : '' }}
                               onchange="toggleDokumen(this.value)"> Dewasa
                    </label>
                </div>
            </div>

            {{-- DOKUMEN UPLOAD — DEWASA: KTP, KK, Paspor --}}
            <div id="dokumen-dewasa" class="mb-4 p-3 rounded-3" style="background:#F8FAFC;border:1.5px solid #E2E8F0">
                <p class="fw-semibold mb-3" style="font-size:.85rem;color:#374151">
                    <i class="bi bi-file-earmark-text"></i> Dokumen Dewasa
                </p>

                {{-- KTP --}}
                <div class="mb-3">
                    <label class="form-label-elsafa">
                        KTP <small class="text-muted fw-normal">(Format .jpeg, .jpg, .png | Maks. 5MB)</small>
                    </label>
                    <input type="file" name="dokumen_ktp" class="form-control-elsafa"
                           accept="image/jpeg,image/jpg,image/png" style="padding:6px 10px">
                    @error('dokumen_ktp')
                        <div class="text-danger mt-1" style="font-size:.8rem">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KK --}}
                <div class="mb-3">
                    <label class="form-label-elsafa">
                        KK <small class="text-muted fw-normal">(Format .jpeg, .jpg, .png | Maks. 5MB)</small>
                    </label>
                    <input type="file" name="dokumen_kk" class="form-control-elsafa"
                           accept="image/jpeg,image/jpg,image/png" style="padding:6px 10px">
                </div>

                {{-- PASPOR --}}
                <div>
                    <label class="form-label-elsafa">
                        Paspor <small class="text-muted fw-normal">(Format .jpeg, .jpg, .png | Maks. 5MB)</small>
                    </label>
                    <input type="file" name="dokumen_paspor" class="form-control-elsafa"
                           accept="image/jpeg,image/jpg,image/png" style="padding:6px 10px">
                </div>
            </div>

            {{-- DOKUMEN UPLOAD — ANAK: Akta Kelahiran, KK, Paspor --}}
            <div id="dokumen-anak" class="mb-4 p-3 rounded-3" style="background:#F8FAFC;border:1.5px solid #E2E8F0;display:none">
                <p class="fw-semibold mb-3" style="font-size:.85rem;color:#374151">
                    <i class="bi bi-file-earmark-text"></i> Dokumen Anak-anak
                </p>

                {{-- AKTA KELAHIRAN --}}
                <div class="mb-3">
                    <label class="form-label-elsafa">
                        Akta Kelahiran <small class="text-muted fw-normal">(Format .jpeg, .jpg, .png | Maks. 5MB)</small>
                    </label>
                    <input type="file" name="dokumen_akta_kelahiran" class="form-control-elsafa"
                           accept="image/jpeg,image/jpg,image/png" style="padding:6px 10px">
                    @error('dokumen_akta_kelahiran')
                        <div class="text-danger mt-1" style="font-size:.8rem">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KK --}}
                <div class="mb-3">
                    <label class="form-label-elsafa">
                        KK <small class="text-muted fw-normal">(Format .jpeg, .jpg, .png | Maks. 5MB)</small>
                    </label>
                    <input type="file" name="dokumen_kk_anak" class="form-control-elsafa"
                           accept="image/jpeg,image/jpg,image/png" style="padding:6px 10px">
                </div>

                {{-- PASPOR --}}
                <div>
                    <label class="form-label-elsafa">
                        Paspor <small class="text-muted fw-normal">(Format .jpeg, .jpg, .png | Maks. 5MB)</small>
                    </label>
                    <input type="file" name="dokumen_paspor_anak" class="form-control-elsafa"
                           accept="image/jpeg,image/jpg,image/png" style="padding:6px 10px">
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="d-flex justify-content-end gap-3 mt-4 pt-3" style="border-top:1px solid #F1F5F9">
                <a href="{{ route('jamaah.index') }}" class="btn-elsafa-outline">Batal</a>
                <button type="submit" class="btn-elsafa-primary">
                    <i class="bi bi-check-lg"></i> Simpan Jamaah
                </button>
            </div>
        </form>
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
    document.getElementById('dokumen-anak').style.display   = isDewasa ? 'none'  : 'block';
}

// Set default on page load berdasarkan old() value
document.addEventListener('DOMContentLoaded', function() {
    const checked = document.querySelector('input[name="kategori_usia"]:checked');
    if (checked) toggleDokumen(checked.value);
});
</script>
@endpush