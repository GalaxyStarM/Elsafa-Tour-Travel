<div class="d-flex justify-content-between align-items-center px-4 py-3">
    <small>
        Menampilkan {{ $data->firstItem() ?? 0 }} dari {{ $data->total() }}
    </small>

    <div>
        @if(!$data->onFirstPage())
            <a href="{{ $data->previousPageUrl() }}" class="page-btn">&lt;</a>
        @endif

        @foreach($data->getUrlRange(1, $data->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page == $data->currentPage() ? 'active' : '' }}">
                {{ $page }}
            </a>
        @endforeach

        @if($data->hasMorePages())
            <a href="{{ $data->nextPageUrl() }}" class="page-btn">&gt;</a>
        @endif
    </div>
</div>