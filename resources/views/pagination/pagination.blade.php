@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
        @if (!$paginator->onFirstPage())
            <li class="page-item">
                <a class="page-item" href="{{ $paginator->previousPageUrl() }}">
                    <span class="page-link">
                        <b><<</b>
                    </span>
                </a>
            </li>
        @endif
        @foreach ($elements as $element)
            @foreach ($element as $key => $url)
                @if ($key == $paginator->currentPage())
                    <li class="page-item">
                        <a class="page-item active" href="{{ $url }}"><span class="page-link">{{ $key }}</span></a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-item" href="{{ $url }}"><span class="page-link">{{ $key }}</span></a>
                    </li>
                @endif
            @endforeach
        @endforeach
        @if ($paginator->currentPage() != $paginator->lastPage())
            <li class="page-item">
                <a class="page-item" href="{{ $paginator->nextPageUrl() }}">
                    <span class="page-link">
                        <b>>></b>
                    </span>
                </a>
            </li>
        @endif
        </ul>
    </nav>
@endif
