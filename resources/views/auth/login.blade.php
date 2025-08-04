<x-guest-layout>
	<!-- Session Status -->
	<x-auth-session-status class="mb-4" :status="session('status')" />

	<form method="POST" action="{{ route('login') }}">
		@csrf

		<!-- Email Address -->
		<div>
			<x-input-label for="email" :value="__('Email')" />
			<x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus
				autocomplete="username" />
			<x-input-error :messages="$errors->get('email')" class="mt-2" />
		</div>

		<!-- Password -->
		<div class="mt-4">
			<x-input-label for="password" :value="__('Password')" />

			<x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required
				autocomplete="current-password" />

			<x-input-error :messages="$errors->get('password')" class="mt-2" />
		</div>

		<!--begin::Row-->
		<div class="row mt-4 block">
			<div class="col-8 d-inline-flex align-items-center">
				<div class="form-check">
					<input id="flexCheckDefault" type="checkbox" value=""
						class="form-check-input"
						name="flexCheckDefault">
					<label for="flexCheckDefault" class="form-check-label">
						<span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
					</label>
				</div>
			</div> <!-- /.col -->
			<div class="col-4">
				<div class="d-grid gap-2">
					<x-primary-button class="d-grid gap-2">
						{{ __('Log in') }}
					</x-primary-button>
				</div>
			</div> <!-- /.col -->
		</div> <!--end::Row-->

		<div class="mt-4 flex items-center justify-end">
			@if (Route::has('password.request'))
				<a
					class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
					href="{{ route('password.request') }}">
					{{ __('Forgot your password?') }}
				</a>
			@endif

		</div>
	</form>
</x-guest-layout>
