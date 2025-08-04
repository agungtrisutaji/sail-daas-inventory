<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		<meta charset="utf-8">
		<meta name="viewport"
			content="width=device-width, initial-scale=1">
		<meta name="csrf-token"
			content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Fonts -->
		<link href="https://fonts.bunny.net"
			rel="preconnect">
		<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
			rel="stylesheet" />

		<!-- Scripts -->
		@vite('resources/js/app.js')
	</head>

	<body class="login-page bg-body-secondary">
		<div class="login-box">

			<div class="card card-outline card-primary">
				<div class="card-header text-center">
					<a class="link-dark link-offset-2 link-opacity-100 link-opacity-50-hover text-center"
						href="/"><img class="h-20 w-20 fill-current text-gray-500"
							src="{{ Vite::asset('resources/images/logo.png') }}"
							alt="{{ config('app.name') }} Logo" /></a>
					<strong class="text-3xl text-gray-800 dark:text-gray-200">{{ config('app.name') }}</strong>
				</div>

				<div class="card-body login-card-body">
					<p class="login-box-msg">Sign in to start your session</p>
					{{ $slot }}
				</div>
			</div>
		</div>
	</body>

</html>
