<form id="{{ $formId }}"
	action="{{ $action }}"
	method="{{ $method }}">
	@csrf

	<x-primary-button class="d-grid mt-4 gap-2">
		{{ __('Update') }}
	</x-primary-button>
</form>

<script>
	$('#{{ $formId }}').on('submit', function(e) {
		e.preventDefault();
		let form = $(this);
		let url = form.attr('action');
		let method = form.attr('method');
		let data = form.serialize();
		let token = $('meta[name="csrf-token"]').attr('content');

		$.ajax({
			url: url,
			type: method,
			data: data,
			headers: {
				'X-CSRF-TOKEN': token
			}
			success: function(response) {
				$('#createModal').modal('hide');
				// Refresh the DataTable
				$('#stagings-table').DataTable().ajax.reload();
			},
			error: function(xhr) {
				// Handle errors
				console.log(xhr.responseText);
			}
		});
	});
</script>
