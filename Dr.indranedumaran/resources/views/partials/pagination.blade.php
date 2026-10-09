@if ($paginator->hasPages())
<nav class="flex flex-wrap items-center justify-center gap-2" role="navigation" aria-label="Pagination">
    @if ($paginator->onFirstPage())
        <span class="rounded-full px-4 py-2 text-sm text-mute/50" aria-disabled="true">Previous</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rounded-full px-4 py-2 text-sm font-medium text-lilac hover:bg-ice-100">Previous</a>
    @endif

    @if (method_exists($paginator, 'getUrlRange'))
        @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span aria-current="page" class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-600 text-sm font-semibold text-white">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="flex h-10 w-10 items-center justify-center rounded-full text-sm text-lilac hover:bg-ice-100" aria-label="Page {{ $page }}">{{ $page }}</a>
            @endif
        @endforeach
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rounded-full px-4 py-2 text-sm font-medium text-lilac hover:bg-ice-100">Next</a>
    @else
        <span class="rounded-full px-4 py-2 text-sm text-mute/50" aria-disabled="true">Next</span>
    @endif
</nav>
@endif
