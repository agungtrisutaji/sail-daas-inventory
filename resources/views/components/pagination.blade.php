@props(['paginator', 'rowsPerPage'])

<nav aria-label="Page navigation">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form action="{{ request()->fullUrlWithQuery(['rowsPerPage' => '']) }}" method="GET">
            <div>
                <p class="mb-0">Show</p>
                <select class="form-control form-control-sm d-inline-block w-auto" id="rowsPerPage" name="rowsPerPage"
                    onchange="this.form.submit()">
                    <option value="10" @if($rowsPerPage==10) selected @endif>10</option>
                    <option value="25" @if($rowsPerPage==25) selected @endif>25</option>
                    <option value="50" @if($rowsPerPage==50) selected @endif>50</option>
                    <option value="100" @if($rowsPerPage==100) selected @endif>100</option>
                </select>
            </div>
        </form>

        <ul class="pagination justify-content-center">
            @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}">Previous</a>
            </li>
            @endif

            @foreach ($elements as $element)
            @if (is_string($element))
            <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            @if (is_array($element))
            @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <li class="page-item active">
                <span class="page-link">{{ $page }}</span>
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
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Next</a>
            </li>
            @else
            <li class="page-item disabled">
                <a class="page-link" href="#">Next</a>
            </li>
            @endif
        </ul>
    </div>
</nav>