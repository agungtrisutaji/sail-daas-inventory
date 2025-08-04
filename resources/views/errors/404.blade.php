<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport"
			content="width=device-width, initial-scale=1">
		<title>404 Page not found</title>

		<!-- Google Font: Source Sans Pro -->
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
			rel="stylesheet">
		@vite(['resources/js/app.js'])
	</head>

	<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary">
		<div class="app-wrapper">

			<!-- Content Wrapper. Contains page content -->
			<div class="app-main">
				<div class="d-flex align-items-center justify-content-center container-fluid h-100">
					<!-- Content Header (Page header) -->
					{{-- <section class="app-content-header">
						<div class="container-fluid justify-content-center">
							<div class="row mb-2">
								<h1>404 Error Page</h1>
							</div>
						</div><!-- /.container-fluid -->
					</section> --}}

					<!-- Main content -->
					<section class="content">
						<div class="error-page text-center">
							<h1 class="headline text-warning"> 404</h1>

							<div class="error-content">
								<h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>

								<p>
									We could not find the page you were looking for.
									Meanwhile, you may <a href="/">return to dashboard</a> or try using the search form.
								</p>

								<form class="search-form">
									<div class="input-group">
										<input class="form-control"
											name="search"
											type="text"
											placeholder="Search">

										<div class="input-group-append">
											<button class="btn btn-warning"
												name="submit"
												type="submit"><i class="fas fa-search"></i>
											</button>
										</div>
									</div>
									<!-- /.input-group -->
								</form>
							</div>
							<!-- /.error-content -->
						</div>
						<!-- /.error-page -->
					</section>
				</div>
				<!-- /.content -->
			</div>
		</div>
		<!-- /.content-wrapper -->

		</div>
		<!-- ./wrapper -->

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
			integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8="
			crossorigin="anonymous"></script>
		<!--end::Required Plugin(Bootstrap 5)-->
		<!-- sortablejs -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
			integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer"></script>
	</body>

</html>
