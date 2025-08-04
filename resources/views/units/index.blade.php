@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="row">
    <div class="mt-2">
        <table id="unitTable" class="table-sm table-border table-hover table-compressed table-striped table"
            style="width:100%">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">No</th>
                    <th>
                        <a class="nav-link"
                            href="{{ route('inventory', ['sortBy' => 'serial', 'sortOrder' => $sortOrder == 'asc' ? 'desc' : 'asc']) }}">Serial
                            Number
                            @if ($sortBy == 'serial')
                            <i class="{{ $sortOrder == 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a class="nav-link"
                            href="{{ route('inventory', ['sortBy' => 'service_name', 'sortOrder' => $sortOrder == 'asc' ? 'desc' : 'asc']) }}">Service
                            Name
                            @if ($sortBy == 'service_name')
                            <i class="{{ $sortOrder == 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th scope="col">Brand</th>
                    <th scope="col">Model</th>
                    <th scope="col">SLA</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="action text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($units as $unit)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $unit->serial }}</td>
                    <td>{{ $unit->service ? $unit->service->name : '-- Service Tidak Ada --' }}</td>
                    <td>{{ $unit->brand }}</td>
                    <td>{{ $unit->model }}</td>
                    <td>{{ $unit->sla }}</td>
                    <td>{{ $unit->status }}</td>
                    <td class="action text-center">
                        <a href="{{ route('unit.edit', $unit->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('unit.destroy', $unit->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- {{ $units->render('components.pagination', ['rowsPerPage' => $rowsPerPage]) }} --}}
</div>
