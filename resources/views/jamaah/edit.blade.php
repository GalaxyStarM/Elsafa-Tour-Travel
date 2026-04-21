{{-- resources/views/jamaah/edit.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Edit Jamaah')

@section('content')
<div style="max-width:720px;margin:auto">
    <div class="card-elsafa">
        <div class="card-body">

            <form method="POST" action="{{ route('jamaah.update', $jamaah) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- NAMA LENGKAP --}}
                <div class="mb-4">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control"
                           value="{{ old('nama_lengkap', $jamaah->nama_lengkap) }}" required>
                    @error('nama_lengkap')
                        <div class="text-danger mt-1" style="font-size:.8rem">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KONTAK --}}
                <div class="mb-4">
                    <label class="form-label">Kontak</label>
                    <div class="phone-wrap">
                        <div class="phone-prefix">&#127470;&#127465; +62</div>
                        <input type="text" name="kontak" class="phone-input"
                               placeholder="8xxxxxxxxxx"
                               value="{{ old('kontak', $jamaah->kontak) }}">
                    </div>
                </div>

                {{-- PAKET --}}
                <div class="mb-4">
                    <label class="form-label">Paket <span class="text-danger">*</span></label>
                    <select name="paket_id" class="form-select" required>
                        <option value="">Pilih Paket</option>
                        @foreach($pakets as $p)
                            <option value="{{ $p->id }}"
                                {{ old('paket_id', $jamaah->paket_id) == $p->id ? 'selected' : '' }}>
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
                        @foreach(['Mandiri', 'Mitra'] as $jenis)
                        <label class="d-flex align-items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_jamaah" value="{{ $jenis }}"
                                   {{ old('jenis_jamaah', $jamaah->jenis_jamaah) === $jenis ? 'checked' : '' }}
                                   onchange="toggleMitra(this.value)">
                            {{ $jenis }}
                        </label>
                        @endforeach
                    </div>
                    <div id="mitraSelect" class="mt-3"
                         style="{{ old('jenis_jamaah', $jamaah->jenis_jamaah) === 'Mitra' ? '' : 'display:none' }}">
                        <select name="mitra_id" class="form-select">
                            <option value="">Pilih Mitra</option>
                            @foreach($mitras as $m)
                                <option value="{{ $m->id }}"
                                    {{ old('mitra_id', $jamaah->mitra_id) == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama_mitra }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status_jamaah" class="form-select">
                        <option value="Akan Berangkat"
                            {{ old('status_jamaah', $jamaah->status_jamaah) === 'Akan Berangkat' ? 'selected' : '' }}>
                            Akan Berangkat
                        </option>
                        <option value="Selesai"
                            {{ old('status_jamaah', $jamaah->status_jamaah) === 'Selesai' ? 'selected' : '' }}>
                            Selesai
                        </option>
                    </select>
                </div>

                {{-- KATEGORI USIA --}}
                <div class="mb-4">
                    <label class="form-label">Kategori Usia <span class="text-danger">*</span></label>
                    <div class="d-flex gap-4">
                        @foreach(['Anak-anak', 'Dewasa'] as $kat)
                        <label class="d-flex align-items-center gap-2 cursor-pointer">
                            <input type="radio" name="kategori_usia" value="{{ $kat }}"
                                   {{ old('kategori_usia', $jamaah->kategori_usia) === $kat ? 'checked' : '' }}
                                   onchange="toggleDokumen(this.value)">
                            {{ $kat }}
                        </label>
                        @endforeach
                    </div>
                    <p class="text-warning mt-2 mb-0" style="font-size:.8rem">
                        <i class="bi bi-exclamation-triangle"></i>
                        Mengubah kategori usia akan menghapus dokumen yang tidak relevan.
                    </p>
                </div>

                {{-- DOKUMEN DEWASA --}}
                <div id="dokumen-dewasa" class="mb-4">
                    <p class="fw-600 mb-1">
                        Dokumen Dewasa
                        <small class="text-muted fw-normal" style="font-size:12px">
                            — Upload baru untuk mengganti file lama
                        </small>
                    </p>

                    @foreach([
                        ['dokumen_ktp',    'KTP'],
                        ['dokumen_kk',     'KK'],
                        ['dokumen_paspor', 'Paspor'],
                    ] as [$key, $label])
                    @php $dok = $dokumenMap[$label] ?? null; @endphp
                    <div class="mb-3">
                        <label class="form-label">
                            {{ $label }}
                            @if($dok)
                                <span class="badge bg-success ms-1" style="font-size:.65rem">&#10003; Ada</span>
                            @else
                                <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">Belum ada</span>
                            @endif
                            <small class="text-muted">(JPG/PNG • Maks 5MB)</small>
                        </label>
                        <div class="upload-box" onclick="this.querySelector('input').click()">
                            <i class="bi bi-upload upload-icon"></i>
                            <p>{{ $dok ? 'Klik untuk ganti file '.$label : 'Klik atau drag file '.$label }}</p>
                            <small>Upload dokumen</small>
                            <input type="file" name="{{ $key }}" hidden
                                   accept="image/jpeg,image/jpg,image/png">
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- DOKUMEN ANAK --}}
                <div id="dokumen-anak" class="mb-4" style="display:none">
                    <p class="fw-600 mb-1">
                        Dokumen Anak-anak
                        <small class="text-muted fw-normal" style="font-size:12px">
                            — Upload baru untuk mengganti file lama
                        </small>
                    </p>

                    @foreach([
                        ['dokumen_akta_kelahiran', 'Akta Kelahiran'],
                        ['dokumen_kk_anak',        'KK'],
                        ['dokumen_paspor_anak',    'Paspor'],
                    ] as [$key, $label])
                    @php $dok = $dokumenMap[$label] ?? null; @endphp
                    <div class="mb-3">
                        <label class="form-label">
                            {{ $label }}
                            @if($dok)
                                <span class="badge bg-success ms-1" style="font-size:.65rem">&#10003; Ada</span>
                            @else
                                <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">Belum ada</span>
                            @endif
                            <small class="text-muted">(JPG/PNG • Maks 5MB)</small>
                        </label>
                        <div class="upload-box" onclick="this.querySelector('input').click()">
                            <i class="bi bi-upload upload-icon"></i>
                            <p>{{ $dok ? 'Klik untuk ganti file '.$label : 'Klik atau drag file '.$label }}</p>
                            <small>Upload dokumen</small>
                            <input type="file" name="{{ $key }}" hidden
                                   accept="image/jpeg,image/jpg,image/png">
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- SUBMIT --}}
                <div class="d-flex justify-content-end gap-3 mt-4 pt-3" style="border-top:1px solid #F1F5F9">
                    <a href="{{ route('jamaah.show', $jamaah) }}" class="btn-elsafa-outline">Batal</a>
                    <button type="submit" class="btn-elsafa">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
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
    document.getElementById('dokumen-anak').style.display   = isDewasa ? 'none'  : 'block';
}
document.addEventListener('DOMContentLoaded', function () {
    const jenis = document.querySelector('input[name="jenis_jamaah"]:checked');
    const usia  = document.querySelector('input[name="kategori_usia"]:checked');
    if (jenis) toggleMitra(jenis.value);
    if (usia)  toggleDokumen(usia.value);
});
</script>
@endpush