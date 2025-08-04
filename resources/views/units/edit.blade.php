<x-app-layout>

	<x-slot name="header">
		{{ __('Edit Unit') }}
	</x-slot>
	<x-slot name="breadcrumb">
		{{ Breadcrumbs::render('unit.edit', $unit) }}
	</x-slot>

	<div class="w-50 container mt-5">
		<div class="card p-3">
			<div class="card-header">
				<div class="card-title">{{ $unit->serial }}</div>
			</div>
			@if ($errors->any())
				<div class="alert alert-danger alert-dismissible fade show">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
					<button class="btn-close"
						data-bs-dismiss="alert"
						type="button"
						aria-label="Close"></button>
				</div>
			@endif
			@if ($message = Session::get('success'))
				<div class="alert alert-info alert-dismissible fade show">
					{{ $message }}
					<button class="btn-close"
						data-bs-dismiss="alert"
						type="button"
						aria-label="Close"></button>
				</div>
			@endif
			<div class="card-body">
				<form action="{{ route('unit.update', $unit->id) }}"
					method="POST">
					@csrf
					@method('PUT')
					<div class="form-group p-2">
						<x-input-label for="serial"
							:value="__('Serial Number')" />
						<x-text-input class="form-control mt-1 block w-full"
							id="serial"
							name="serial"
							type="text"
							:value="$unit->serial"
							required
							autofocus />
						<x-input-error class="mt-2"
							:messages="$errors->get('serial')" />
					</div>

					<div class="form-group p-2">
						<x-input-label for="brand"
							:value="__('Brand')" />
						<x-text-input class="form-control mt-1 block w-full"
							id="brand"
							name="brand"
							type="text"
							:value="$unit->brand"
							required
							autofocus />
						<x-input-error class="mt-2"
							:messages="$errors->get('brand')" />
					</div>

					<div class="form-group p-2">
						<x-input-label for="category"
							:value="__('Category')" />
						<x-select-input name="category"
							:options="$categories"
							:selected="$unit->category->value" />
					</div>

					<div class="form-group p-2">
						<x-input-label for="status"
							:value="__('Status')" />
						<x-select-input name="status"
							:label="'Status'"
							:options="$statusOptions"
							:selected="$unit->status->value"
							:key="'status'" />
					</div>

					<x-primary-button class="d-grid mt-4 gap-2">
						{{ __('Update') }}
					</x-primary-button>
				</form>

			</div>
		</div>
	</div>
</x-app-layout>
