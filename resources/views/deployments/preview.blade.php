<x-app-layout>
	<x-slot name="header">
		{{ __('Deployment Import Preview') }}
	</x-slot>

	<div class="container-fluid">
		<form action="{{ route('deployment.import.process') }}"
			method="POST">
			@csrf
			<table class="table">
				<thead>
					<tr>
						<th>Serial Number</th>
						<th>Company</th>
						<!-- Add other headers as needed -->
					</tr>
				</thead>
				<tbody>
					@foreach ($data as $row)
						<tr>
							<td>{{ $row['Serial Number'] }}</td>
							<td>
								<select class="form-select"
									name="companies[{{ $row['Serial Number'] }}]"
									data-placeholder="Select Company"
									required>
									<option value=""></option>
									@foreach ($companies as $company)
										<option value="{{ $company->id }}">{{ $company->company_name }}</option>
									@endforeach
								</select>
							</td>
							<!-- Add other fields as needed -->
						</tr>
					@endforeach
				</tbody>
			</table>
			<button class="btn btn-primary"
				type="submit">Import Data</button>
		</form>
	</div>

	@push('js')
		<script type="text/javascript">
			$('.form-select').each(function() {
				$(this).select2({
					theme: 'bootstrap-5',
					placeholder: $(this).data('placeholder'),
					debug: true,
					allowClear: true,
					language: 'en',
				});
			});
		</script>
	@endpush
</x-app-layout>
