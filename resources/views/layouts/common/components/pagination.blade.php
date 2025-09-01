@if ($paginator->hasPages())
<nav aria-label="Page navigation example" class="pagination-custom">
    <ul class="pagination justify-content-center mb-0">
        @if ($paginator->onFirstPage())
        <li class="page-item disabled">

        </li>
        @else
        <li class="page-item">
            <a href="{{ $paginator->previousPageUrl() }}" class="page-link">
                <span aria-hidden="true">
                    <i class="fas fa-chevron-left"></i>
                </span>
                <span class="ml-2">Sebelum</span>
            </a>
        </li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled">{{ $element }}</li>
            @endif

            @if (is_array($element))
                @foreach($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <a class="page-link" href="#">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach


        @if ($paginator->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                <span class="mr-2">Selanjutnya</span>
                <span aria-hidden="true">
                    <i class="fas fa-chevron-right"></i>
                </span>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link" href="#" aria-label="Next">
                <span class="mr-2">Selanjutnya</span>
                <span aria-hidden="true">
                    <i class="fas fa-chevron-right"></i>
                </span>
            </a>
        </li>
        @endif
    </ul>
</nav>
@endif
