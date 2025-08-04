<div class="row">
	<table class="table"
		id="deliveryTable">
		<thead>
			<tr>
				<th>Delivery Number</th>
				<th>Customer</th>
				<th>Delivery Date</th>
				<th>Estimated Arrival Date</th>
				<th>Actual Arrival Date</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($deliveries as $delivery)
				<tr>
					<td><x-action :url="route('delivery.show', $delivery->id)"
							:label="$delivery->delivery_number"
							:modal="true"
							:modalName="'createModal'" />

					</td>
					<td>{{ $delivery->company->company_name ?? null }}</td>
					<td> {{ $delivery->delivery_date ? date('Y-m-d H:i', strtotime($delivery->delivery_date)) : '' }}</td>
					<td>
						{{ $delivery->estimated_arrival_date ? date('Y-m-d H:i', strtotime($delivery->estimated_arrival_date)) : '' }}
					</td>
					<td> {{ $delivery->actual_arrival_date ? date('Y-m-d H:i', strtotime($delivery->actual_arrival_date)) : '' }}
					</td>
					<td>{{ $delivery->status->getLabel() }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
