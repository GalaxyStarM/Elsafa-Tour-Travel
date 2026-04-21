<ul class="nav doc-tabs mb-3">
@foreach($dokumenDiperlukan as $i => $jenis)
    <li class="nav-item">
        <a class="nav-link {{ $i==0 ? 'active' : '' }}"
           data-bs-toggle="tab"
           href="#tab{{ $i }}">
            {{ $jenis }}
        </a>
    </li>
@endforeach
</ul>

<div class="tab-content">
@foreach($dokumenDiperlukan as $i => $jenis)
    @php $dok = $dokumenMap[$jenis] ?? null; @endphp

    <div class="tab-pane fade {{ $i==0 ? 'show active' : '' }}" id="tab{{ $i }}">

        @if($dok)
            <img src="{{ Storage::url($dok->file_path) }}" class="doc-preview mb-2">

            <label class="btn-elsafa-outline cursor-pointer">
                Ganti
                <input type="file" hidden onchange="uploadDokumen(this, '{{ $jenis }}')">
            </label>
        @else
            <div class="upload-zone"
                 onclick="this.nextElementSibling.click()">
                <i class="bi bi-cloud-upload"></i>
                <p>Choose a file or drag & drop it here</p>
                <small>JPEG, JPG, PNG up to 5MB</small>
                <div><span class="browse-btn mt-2">Browse</span></div>
            </div>

            <input type="file" hidden onchange="uploadDokumen(this, '{{ $jenis }}')">
        @endif

    </div>
@endforeach
</div>