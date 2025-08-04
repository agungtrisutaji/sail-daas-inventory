@props(['value' => null, 'name', 'required' => false, 'disabled' => false])

<div class="input-group"
	id="datetimepickerModal"
	data-td-target-input="nearest">
	<div class="input-group log-event datetimepicker"
		id="datetimepicker"
		data-td-target-input="nearest"
		data-td-target-toggle="nearest">
		<input class="form-control"
			id="datetimepickerInput"
			name="{{ $name }}"
			data-td-target=".datetimepicker"
			type="text"
			value="{{ $value != null ? $value : null }}"
			autocomplete="off"
			{{ $required ? 'required' : '' }}
			{{ $disabled ? 'disabled' : '' }} />
		<span class="input-group-text"
			data-td-target=".datetimepicker"
			data-td-toggle="datetimepicker">
			<i class="fas fa-calendar"></i>
		</span>
	</div>

	<x-input-error class="mt-2"
		:messages="$errors->get('{{ $name }}')" />
</div>
