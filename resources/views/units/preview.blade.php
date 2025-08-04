<x-app-layout>
	<x-slot name="header">
		{{ __('Unit Import Preview') }}
	</x-slot>

	<div class="container-fluid">
		<h2>Preview Batch Update Data</h2>

		@if ($data->isEmpty())
			<div class="alert alert-warning">
				No data found in the uploaded file.
			</div>
		@else
			<div class="table-responsive">
				<table class="table-bordered table-striped table"
					id="previewTable">
					<thead>
						<tr>
							<th>Excel Row</th>
							@foreach ($data->first() as $key => $value)
								@if ($key !== 'message' && $key !== 'row_number')
									<th>{{ $key }}</th>
								@endif
							@endforeach
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>

			<form action="{{ route('inventory.batch.update') }}"
				method="post">
				@csrf
				<input name="file_path"
					type="hidden"
					value="{{ session('temp_file_path') }}">

				@if ($hasErrorMessage)
					<div class="alert alert-danger alert-dismissible fade show mt-3">
						<strong>Warning!</strong>
						<ul>
							@foreach ($data as $row)
								@if (!empty($row['message']))
									<li>Row {{ $row['row_number'] }}: {{ $row['message'] }}</li>
								@endif
							@endforeach
						</ul>
						<button class="btn-close"
							data-bs-dismiss="alert"
							type="button"
							aria-label="Close"></button>
					</div>
					<a class="btn btn-primary mt-3"
						href="{{ route('inventory') }}">Back</a>
				@else
					<div class="row">
						<div class="col-md-6">
							<button class="btn btn-primary mt-3"
								type="submit">Process Batch Update</button>
						</div>
						<div class="col-md-6 d-flex justify-content-end">
							<a class="btn btn-primary mt-3"
								href="{{ route('inventory') }}">Back</a>
						</div>
					</div>
				@endif
			</form>
		@endif
	</div>

	<style>
		#previewTable {
			width: 100% !important;
		}

		#previewTable th,
		#previewTable td {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			/* max-width: 200px; */
		}
	</style>

	@push('js')
		<script>
			$(function() {
				$('#previewTable').DataTable({
					processing: true,
					serverSide: true,
					scrollX: true,
					paging: true,
					responsive: true,
					searching: false,
					buttons: [],
					lengthMenu: [
						[10, 25, 50, 100, -1],
						[10, 25, 50, 100, 'All'],
					],
					dom: '<"top"Bl><"clear">rt<"bottom"ip>',
					language: {
						processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>',
						emptyTable: 'No data available in table',
						info: 'Showing _START_ to _END_ of _TOTAL_ entries',
						infoEmpty: 'Showing 0 to 0 of 0 entries',
						infoFiltered: '(filtered from _MAX_ total entries)',
						lengthMenu: 'Show _MENU_ entries',
						loadingRecords: 'Loading...',
						search: 'Search:',
						zeroRecords: 'No matching records found',
					},
					ajax: "{{ route('inventory.preview.data') }}",
					columns: [{
							data: 'row_number',
							name: 'row_number'
						},
						@foreach ($data->first() as $key => $value)
							@if ($key !== 'message' && $key !== 'row_number')
								{
									data: '{{ $key }}',
									name: '{{ $key }}'
								},
							@endif
						@endforeach {
							data: 'status',
							name: 'status',
							orderable: false,
							searchable: false
						}
					],
					createdRow: function(row, data, dataIndex) {
						if (data.message) {
							$(row).addClass('table-danger');
						}
					}
				});
			});
		</script>
	@endpush
</x-app-layout>
