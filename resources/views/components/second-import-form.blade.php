@props([
    'withSelect' => false,
    'withAssetGroup' => false,
    'name',
    'title',
    'route',
    'method',
    'fields' => [],
    'parentUrl' => 'api/fetch-companies',
    'submitButtonText' => 'Submit',
    'otherButton' => [],
    'withOperationalUnit' => false,
    'withDate' => false,
    'dateFields' => [],
])

<div class="modal fade"
	id="{{ $name }}-modal-2"
	aria-hidden="true"
	aria-labelledby="{{ $name }}-modal-2-Label"
	tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5"
					id="{{ $name }}-modal-2-Label">{{ ucwords(str_replace('-', ' ', $name)) }}, All in one File</h1>
				<button class="btn-close"
					data-bs-dismiss="modal"
					type="button"
					aria-label="Close"></button>
			</div>
			<form id="importAllForm"
				method="{{ $method }}"
				action="{{ $route }}"
				enctype="multipart/form-data">
				@csrf

				<div class="modal-body">
					<div class="input-group my-3">
						<input class="form-control"
							id="file-all"
							name="file-all"
							type="file">
						<label class="input-group-text"
							for="file-all">Upload</label>
					</div>
				</div>

				<div class="modal-footer">
					<div class="col">
						<button class="btn btn-secondary"
							data-bs-dismiss="modal"
							type="button">Close</button>

						<button class="btn btn-outline-primary ms-1"
							data-bs-target=".{{ $name }}-modal"
							data-bs-toggle="modal"
							type="button">Back</button>
					</div>

					<div class="spinner-grow spinner d-none text-primary"
						role="status">
						<span class="visually-hidden">Loading...</span>
					</div>

					<div class="col d-flex justify-content-end">
						@if ($otherButton)

							@if ($otherButton['link'])
								<a class="btn btn-{{ $otherButton['color'] }} me-2"
									href="{{ $otherButton['link'] }}">{{ $otherButton['text'] }}</a>
							@else
								<button class="btn btn-primary"
									type="submit">{{ $otherButton['text'] }}</button>
							@endif
						@endif

						<button class="btn btn-primary"
							type="submit">{{ $submitButtonText }}</button>
					</div>

			</form>

		</div>
	</div>
</div>
</div>

@push('js')
	<script>
		$(document).ready(function() {
			$('#{{ $name }}-modal-2').on('submit', function(e) {
				// Disable submit button
				$('.btn').addClass('disabled');
				$('.form-select').addClass('disabled').prop('disabled', true);
				$('.spinner').removeClass('d-none');
			});
		});
	</script>
@endpush
