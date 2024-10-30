<link rel="stylesheet" href="/css/paginate.css">

@if ($paginator->hasPages())
<div class="pagination__wrap">
    <div class="pagination">
        <ul class="pagination__nav" role="navigation">
            {{-- Previous Page Link --}}
            @if (!$paginator->onFirstPage())
            <li class="pagination__list">
                <a class="pagination__item" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                    ‹
                </a>
            </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
            <li class="pagination__list" aria-disabled="true">{{ $element }}</li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
            @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <li class="pagination__list" aria-current="page">
                <a class="pagination__item active" href="#">{{ $page }}</a>
            </li>
            @else
            <li class="pagination__list">
                <a class="pagination__item" href="{{ $url }}">{{ $page }}</a>
            </li>
            @endif
            @endforeach
            @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <li class="pagination__list">
                <a class="pagination__item" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                    ›
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>
@endif