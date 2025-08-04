@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
    'selectedId' => '',
    'datas' => [],
    'label' => '',
    'labelField' => 'label',
    'valueField' => 'value',
])

<select class="form-select mb-3"
	id="company_id"
	name="company_id[]">
	<option value="">Select Company</option>
	@foreach ($datas as $data)
		<option value="{{ $data->id }}"
			{{ $selectedId == $data->id ? 'selected' : '' }}>
			{{ $data->company_name }} - {{ $data->address->location }}
		</option>
	@endforeach
</select>

{{-- select2 implement --}}

@push('js')
	<script type="text/javascript">
		$('#company_id').select2({
			theme: 'bootstrap-5',
			// placeholder: 'Select {{ ucfirst($label) }}',
			allowClear: true,
			language: 'en',
		})
	</script>
@endpush
