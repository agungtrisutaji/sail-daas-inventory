<div class="card">
	<h5 class="card-header">{{ $delivery->delivery_number }}</h5>
	<div class="card-body">
		<p class="card-text">{{ $delivery->company->company_name ?? 'N/A' }} - {{ $address->location ?? 'N/A' }} </p>
		<p class="card-text">Courier: {{ $delivery->deliveryService->courier->name }}</p>
		<p class="card-text">Tracking Number: {{ $delivery->tracking_number }}</p>
		<p class="card-text">Date: {{ $delivery->delivery_date }}</p>
		<p class="card-text">Status: {{ $delivery->statusLabel }}</p>
		@if ($delivery->status->value == 2)
			<form id="deliveryForm"
				action="{{ route('delivery.mark-as-delivered', $delivery) }}"
				method="POST">
				@csrf
				@method('PATCH')
				<div class="row">
					<div class="form-group col-md-6 p-2">
						<x-input-label for="actual_arrival_date"
							:value="__('Arrival Date')" />

						<x-datetime-picker name="actual_arrival_date"
							value="{{ old('actual_arrival_date') }}"
							required />

						<x-input-error class="mt-2"
							:messages="$errors->get('actual_arrival_date')" />
					</div>
					<div class="position-relative form-group col-md-6 p-2">
						<button class="position-absolute btn btn-success top-50 end-0 me-2"
							type="submit">Mark as Delivered</button>
					</div>
				</div>
			</form>
		@endif
	</div>
</div>
<h2 class="mt-4">Delivered Items</h2>
<table class="table">
	<thead>
		<tr>
			<th>Unit Serial</th>
			<th>Service</th>
			<th>Unit Brand</th>
		</tr>
	</thead>
	<tbody>
		@if ($delivery->units->count() > 0)
			@foreach ($delivery->units as $item)
				<tr>
					<td>{{ $item->serial }}</td>
					<td>
						{{ $item->latestStaging ? $item->latestStaging->service->label : $item->category }}
					</td>
					<td>{{ $item->brand }}</td>
				</tr>
			@endforeach
		@endif
	</tbody>
</table>

@push('js')
	<script>
		$('#deliveryForm').on('submit', function(e) {
			e.preventDefault();
			let form = $(this);
			let url = form.attr('action');
			let method = form.attr('method');
			let data = form.serialize();

			$.ajax({
				url: url,
				type: method,
				data: data,
				success: function(response) {
					$('#createModal').modal('hide');
					// Refresh the DataTable
					$('#deliveryTable').DataTable().ajax.reload();

				},
				error: function(xhr) {
					// Handle errors
					console.log(xhr.responseText);
				}
			});
		});
	</script>
@endpush
