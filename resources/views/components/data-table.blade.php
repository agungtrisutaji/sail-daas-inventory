@props(['id' => 'inventoryTable', 'columns' => []])

<table id="{{ $id }}"
	style="width:100%"
	{{ $attributes->merge(['class' => 'table-sm table-responsive table-nowrap table']) }}>
	<thead class="thead-dark">
		<tr class="text-nowrap">
			@foreach ($columns as $column)
				<th scope="col">{{ $column }}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<p class="table_placeholder placeholder-glow">
	<span class="placeholder bg-info col-12"></span>
	<span class="placeholder bg-info col-12"></span>
	<span class="placeholder bg-info col-12"></span>
	<span class="placeholder bg-info col-12"></span>
	<span class="placeholder bg-info col-12"></span>
	<span class="placeholder bg-info col-12"></span>
	<span class="placeholder bg-info col-12"></span>
	<span class="placeholder bg-info col-12"></span>
	<span class="placeholder bg-info col-12"></span>
</p>

<div
	class="border-border-200 text-secondary inline-block h-20 w-20 animate-spin rounded-full border-8 border-solid border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
	role="status"><span class="sr-only">Loading...</span>
</div>
