@if ($paginator->hasPages())
  <nav aria-label="Page navigation">
    <ul class="pagination pagination-rounded">

      {{-- First Page --}}
      <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->url(1) }}">
          <i class="icon-base ti tabler-chevrons-left icon-sm"></i>
        </a>
      </li>

      {{-- Previous Page --}}
      <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
          <i class="icon-base ti tabler-chevron-left icon-sm"></i>
        </a>
      </li>

      {{-- Page Numbers --}}
      @foreach ($elements as $element)
        @if (is_string($element))
          <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
        @endif

        @if (is_array($element))
          @foreach ($element as $page => $url)
            <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
              <a class="page-link" href="{{ $url }}">{{ $page }}</a>
            </li>
          @endforeach
        @endif
      @endforeach

      {{-- Next Page --}}
      <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
          <i class="icon-base ti tabler-chevron-right icon-sm"></i>
        </a>
      </li>

      {{-- Last Page --}}
      <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">
          <i class="icon-base ti tabler-chevrons-right icon-sm"></i>
        </a>
      </li>

    </ul>
  </nav>
@endif
