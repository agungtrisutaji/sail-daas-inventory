<div class="input-group">
	<span class="input-group-text">Rp</span>
	<input class="form-control currency-input"
		id="{{ $name }}"
		name="{{ $name }}"
		type="text"
		value="{{ $value }}"
		placeholder="{{ $placeholder }}">
</div>

@push('js')
	<script>
		$(document).ready(function() {
			$('.currency-input').each(function() {
				new Cleave(this, {
					numeral: true,
					numeralThousandsGroupStyle: 'thousand',
					numeralDecimalMark: ',',
					delimiter: '.',
					numeralDecimalScale: 2
				});
			});
		});
	</script>
@endpush
