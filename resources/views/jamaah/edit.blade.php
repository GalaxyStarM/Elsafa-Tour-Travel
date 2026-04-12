{{-- resources/views/jamaah/edit.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Edit Jamaah')

@section('content')
<div style="max-width:760px">
    <div class="card-elsafa p-4">
        <form method="POST" action="{{ route('jamaah.update', $jamaah) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- NAMA LENGKAP --}}
            <div class="mb-4">
                <label class="form-label-elsafa">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" class="form-control-elsafa"
                       value="{{ old('nama_lengkap', $jamaah->nama_lengkap) }}" required>
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
                           value="{{ old('kontak', $jamaah->kontak) }}">
                </div>
            </div>

            {{-- PAKET --}}
            <div class="mb-4">
                <label class="form-label-elsafa">Paket <span class="text-danger">*</span></label>
                <div class="position-relative">
                    <select name="paket_id" class="form-control-elsafa" required style="appearance:none">
                        @foreach($pakets as $p)
                            <option value="{{ $p->id }}" {{ old('paket_id', $jamaah->paket_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_paket }} — Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    <i class="bi bi-chevron-down position-absolute" style="right:14px;top:50%;transform:translateY(-50%);color:#94A3B8;pointer-events:none"></i>
                </div>
            </div>

            {{-- JENIS JAMAAH --}}
            <div class="mb-4">
                <label class="form-label-elsafa">Jenis Jamaah <span class="text-danger">*</span></label>
                <div class="d-flex gap-4">
                    @foreach(['Mandiri', 'Mitra'] as $jenis)
                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.9rem">
                        <input type="radio" name="jenis_jamaah" value="{{ $jenis }}"
                               {{ old('jenis_jamaah', $jamaah->jenis_jamaah) === $jenis ? 'checked' : '' }}
                               onchange="toggleMitra(this.value)">
                        {{ $jenis }}
                    </label>
                    @endforeach
                </div>
                <div id="mitraSelect" class="mt-3"
                     style="{{ old('jenis_jamaah', $jamaah->jenis_jamaah) === 'Mitra' ? '' : 'display:none' }}">
                    <div class="position-relative">
                        <select name="mitra_id" class="form-control-elsafa" style="appearance:none">
                            <option value="">Pilih Mitra</option>
                            @foreach($mitras as $m)
                                <option value="{{ $m->id }}" {{ old('mitra_id', $jamaah->mitra_id) == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama_mitra }}
                                </option>
                            @endforeach
                        </select>
                        <i class="bi bi-chevron-down position-absolute" style="right:14px;top:50%;transform:translateY(-50%);color:#94A3B8;pointer-events:none"></i>
                    </div>
                </div>
            </div>

            {{-- STATUS JAMAAH --}}
            <div class="mb-4">
                <label class="form-label-elsafa">Status Jamaah</label>
                <div class="position-relative">
                    <select name="status_jamaah" class="form-control-elsafa" style="appearance:none">
                        <option value="Akan Berangkat" {{ old('status_jamaah', $jamaah->status_jamaah) === 'Akan Berangkat' ? 'selected' : '' }}>Akan Berangkat</option>
                        <option value="Selesai" {{ old('status_jamaah', $jamaah->status_jamaah) === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <i class="bi bi-chevron-down position-absolute" style="right:14px;top:50%;transform:translateY(-50%);color:#94A3B8;pointer-events:none"></i>
                </div>
            </div>

            {{-- KATEGORI USIA --}}
            <div class="mb-4">
                <label class="form-label-elsafa">Kategori Usia <span class="text-danger">*</span></label>
                <div class="d-flex gap-4">
                    @foreach(['Anak-anak', 'Dewasa'] as $kat)
                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.9rem">
                        <input type="radio" name="kategori_usia" value="{{ $kat }}"
                               {{ old('kategori_usia', $jamaah->kategori_usia) === $kat ? 'checked' : '' }}
                               onchange="toggleDokumen(this.value)">
                        {{ $kat }}
                    </label>
                    @endforeach
                </div>
                @if($jamaah->kategori_usia !== old('kategori_usia', $jamaah->kategori_usia))
                    <p class="text-warning mt-1" style="font-size:.8rem">
                        <i class="bi bi-exclamation-triangle"></i>
                        Mengubah kategori usia akan menghapus dokumen yang tidak relevan.
                    </p>
                @endif
            </div>

            {{-- DOKUMEN — DEWASA --}}
            <div id="dokumen-dewasa" class="mb-4 p-3 rounded-3" style="background:#F8FAFC;border:1.5px solid #E2E8F0">
                <p class="fw-semibold mb-3" style="font-size:.85rem;color:#374151">
                    <i class="bi bi-file-earmark-text"></i> Dokumen Dewasa
                    <small class="text-muted fw-normal ms-1">— Upload baru untuk mengganti file lama</small>
                </p>

                @foreach([
                    ['dokumen_ktp',    'KTP'],
                    ['dokumen_kk',     'KK'],
                    ['dokumen_paspor', 'Paspor'],
                ] as [$key, $label])
                @php $dok = $dokumenMap[$label] ?? null; @endphp
                <div class="mb-3">
                    <label class="form-label-elsafa">
                        {{ $label }}
                        @if($dok)
                            <span class="badge bg-success ms-1" style="font-size:.65rem">✓ Ada</span>
                        @else
                            <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">Belum ada</span>
                        @endif
                        <small class="text-muted fw-normal">(Format .jpeg, .jpg, .png | Maks. 5MB)</small>
                    </label>
                    <input type="file" name="{{ $key }}" class="form-control-elsafa"
                           accept="image/jpeg,image/jpg,image/png" style="padding:6px 10px">
                </div>
                @endforeach
            </div>

            {{-- DOKUMEN — ANAK --}}
            <div id="dokumen-anak" class="mb-4 p-3 rounded-3" style="background:#F8FAFC;border:1.5px solid #E2E8F0;display:none">
                <p class="fw-semibold mb-3" style="font-size:.85rem;color:#374151">
                    <i class="bi bi-file-earmark-text"></i> Dokumen Anak-anak
                    <small class="text-muted fw-normal ms-1">— Upload baru untuk mengganti file lama</small>
                </p>

                @foreach([
                    ['dokumen_akta_kelahiran', 'Akta Kelahiran'],
                    ['dokumen_kk_anak',        'KK'],
                    ['dokumen_paspor_anak',    'Paspor'],
                ] as [$key, $label])
                @php
                    $lookupLabel = $label;
                    $dok = $dokumenMap[$lookupLabel] ?? null;
                @endphp
                <div class="mb-3">
                    <label class="form-label-elsafa">
                        {{ $label }}
                        @if($dok)
                            <span class="badge bg-success ms-1" style="font-size:.65rem">✓ Ada</span>
                        @else
                            <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">Belum ada</span>
                        @endif
                        <small class="text-muted fw-normal">(Format .jpeg, .jpg, .png | Maks. 5MB)</small>
                    </label>
                    <input type="file" name="{{ $key }}" class="form-control-elsafa"
                           accept="image/jpeg,image/jpg,image/png" style="padding:6px 10px">
                </div>
                @endforeach
            </div>

            {{-- SUBMIT --}}
            <div class="d-flex justify-content-end gap-3 mt-4 pt-3" style="border-top:1px solid #F1F5F9">
                <a href="{{ route('jamaah.show', $jamaah) }}" class="btn-elsafa-outline">Batal</a>
                <button type="submit" class="btn-elsafa-primary">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
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

document.addEventListener('DOMContentLoaded', function () {
    const checked = document.querySelector('input[name="kategori_usia"]:checked');
    if (checked) toggleDokumen(checked.value);
});
</script>
@endpush