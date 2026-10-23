@if ($paginator->hasPages())
    <nav class="fe-pagination" role="navigation" aria-label="Pagination">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="fe-page-btn disabled" aria-disabled="true">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="fe-page-btn" rel="prev" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="fe-page-dots">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="fe-page-num active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="fe-page-num">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="fe-page-btn" rel="next" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="fe-page-btn disabled" aria-disabled="true">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif

    </nav>
@endif