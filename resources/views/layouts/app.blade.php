<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<!--begin::Head-->

	<head>
		<meta http-equiv="Content-Type"
			content="text/html">
		<meta charset="utf-8">
		<meta name="csrf-token"
			content="{{ csrf_token() }}">
		<title>{{ config('app.name', 'Laravel') }} | {{ $header }}</title>
		<!--begin::Primary Meta Tags-->

		{{-- <link href="{{ asset('site.webmanifest') }}"
			rel="manifest"> --}}
		<link href="/apple-touch-icon.png"
			rel="apple-touch-icon"
			sizes="180x180">
		<link type="image/png"
			href="/favicon-32x32.png"
			rel="icon"
			sizes="32x32">
		<link type="image/png"
			href="/favicon-16x16.png"
			rel="icon"
			sizes="16x16">
		<link href="/site.webmanifest"
			rel="manifest">
		<link href="/safari-pinned-tab.svg"
			rel="mask-icon"
			color="#5bbad5">
		<meta name="msapplication-TileColor"
			content="#9f00a7">
		<meta name="theme-color"
			content="#ffffff">

		<meta name="viewport"
			content="width=device-width, initial-scale=1.0">
		<meta name="title"
			content="{{ config('app.name', 'Laravel') }}">
		<meta name="author"
			content="Agung Trisutaji Aprian">
		<meta name="keywords"
			content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, laravel, php, laravel php, laravel php dashboard">
		<!--end::Primary Meta Tags-->

		<script src="https://cdn.ckeditor.com/ckeditor5/11.1.1/classic/ckeditor.js"></script>

		<!--begin::Fonts-->
		<link href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
			rel="stylesheet"
			integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
			crossorigin="anonymous">
		<!--end::Fonts-->

		<!--begin::Third Party Plugin(OverlayScrollbars)-->
		<link href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css"
			rel="stylesheet"
			integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg="
			crossorigin="anonymous">
		<!--end::Third Party Plugin(OverlayScrollbars)-->

		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
			rel="stylesheet" />
		<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
			rel="stylesheet" />

		<link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.14.0/sweetalert2.css"
			rel="stylesheet"
			integrity="sha512-Gebe6n4xsNr0dWAiRsMbjWOYe1PPVar2zBKIyeUQKPeafXZ61sjU2XCW66JxIPbDdEH3oQspEoWX8PQRhaKyBA=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer" />

		<!-- apexcharts -->
		<link href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
			rel="stylesheet"
			integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
			crossorigin="anonymous">

		@vite(['resources/js/app.js'])

	</head>
	<!--end::Head-->

	<!--begin::Body-->
	{{-- forecast->stock->PR->Staging->PO->Delivery->Deployment --}}

	<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary">
		<!--begin::App Wrapper-->
		<div class="app-wrapper">
			<!--begin::Header-->
			@include('components.navbars.navbar')
			<!--begin::Sidebar-->
			@include('components.sidebars.sidebar')
			<!--end::Sidebar-->

			<!--begin::App Main-->
			<main class="app-main">
				<!--begin::App Content Header-->
				<div class="app-content-header">
					<!--begin::Container-->
					<div class="container-fluid">

						<!--begin::Row-->
						@isset($header)
							<div class="row">
								<div class="col-sm-6">
									<h3 class="mb-0">
										{{ $header }}
									</h3>
								</div>

								@isset($breadcrumb)
									<div class="col-sm-6">
										<div class="float-end">
											{{ $breadcrumb }}
										</div>
									</div>
								@endisset

							</div>
							<!--end::Row-->
						@endisset
					</div>
					<!--end::Container-->
				</div>
				<!--end::App Content Header-->
				<!--begin::App Content-->
				<div class="app-content">
					{{ $slot }}
				</div>
				<!--end::App Content-->
			</main>
			<!--end::App Main-->
			<!--begin::Footer-->
			<footer class="app-footer">
				<!--begin::To the end-->
				<div class="d-none d-sm-inline float-end">{{ $header }}</div>
				<!--end::To the end-->
				<!--begin::Copyright-->
				<strong>
					Copyright &copy; 2014-2024&nbsp;
					<a class="text-decoration-none"
						href="#">{{ config('app.company') }}</a>.
				</strong>
				Profesional IT Solutions.
				<!--end::Copyright-->
			</footer>
			<!--end::Footer-->
		</div>
		<!--end::App Wrapper-->
		<!--begin::Script-->

		<!--begin::Third Party Plugin(OverlayScrollbars)-->
		<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js"
			integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w="
			crossorigin="anonymous"></script>
		<!--end::Third Party Plugin(OverlayScrollbars)-->

		<!--begin::Required Plugin(popperjs for Bootstrap 5)-->
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
			integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I="
			crossorigin="anonymous"></script>
		<!--end::Required Plugin(popperjs for Bootstrap 5)-->

		<!-- sortablejs -->
		<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
			integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ="
			crossorigin="anonymous"></script>
		<!-- sortablejs -->

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
			integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer"></script>

		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.14.0/sweetalert2.min.js"
			integrity="sha512-OlF0YFB8FRtvtNaGojDXbPT7LgcsSB3hj0IZKaVjzFix+BReDmTWhntaXBup8qwwoHrTHvwTxhLeoUqrYY9SEw=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer"></script>

		<!-- apexcharts -->
		<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
			integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
			crossorigin="anonymous"></script>
		<script>
			const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
			const Default = {
				scrollbarTheme: "os-theme-light",
				scrollbarAutoHide: "leave",
				scrollbarClickScroll: true,
			};
			document.addEventListener("DOMContentLoaded", function() {
				const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
				if (
					sidebarWrapper &&
					typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
				) {
					OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
						scrollbars: {
							theme: Default.scrollbarTheme,
							autoHide: Default.scrollbarAutoHide,
							clickScroll: Default.scrollbarClickScroll,
						},
					});
				}
			});
		</script>
		<!--end::OverlayScrollbars Configure-->
		<!-- OPTIONAL SCRIPTS -->
		<script>
			const connectedSortables =
				document.querySelectorAll(".connectedSortable");
			connectedSortables.forEach((connectedSortable) => {
				let sortable = new Sortable(connectedSortable, {
					group: "shared",
					handle: ".card-header",
				});
			});

			const cardHeaders = document.querySelectorAll(
				".connectedSortable .card-header",
			);
			cardHeaders.forEach((cardHeader) => {
				cardHeader.style.cursor = "move";
			});
		</script>
		<!--end::Script-->
		<script>
			// Color Mode Toggler
			(() => {
				"use strict";

				const storedTheme = localStorage.getItem("theme");

				const getPreferredTheme = () => {
					if (storedTheme) {
						return storedTheme;
					}

					return window.matchMedia("(prefers-color-scheme: dark)").matches ?
						"dark" :
						"light";
				};

				const setTheme = function(theme) {
					if (
						theme === "auto" &&
						window.matchMedia("(prefers-color-scheme: dark)").matches
					) {
						document.documentElement.setAttribute("data-bs-theme", "dark");
					} else {
						document.documentElement.setAttribute("data-bs-theme", theme);
					}
				};

				setTheme(getPreferredTheme());

				const showActiveTheme = (theme, focus = false) => {
					const themeSwitcher = document.querySelector("#bd-theme");

					if (!themeSwitcher) {
						return;
					}

					const themeSwitcherText = document.querySelector("#bd-theme-text");
					const activeThemeIcon = document.querySelector(
						".theme-icon-active i"
					);
					const btnToActive = document.querySelector(
						`[data-bs-theme-value="${theme}"]`
					);
					const svgOfActiveBtn = btnToActive
						.querySelector("i")
						.getAttribute("class");

					for (const element of document.querySelectorAll(
							"[data-bs-theme-value]"
						)) {
						element.classList.remove("active");
						element.setAttribute("aria-pressed", "false");
					}

					btnToActive.classList.add("active");
					btnToActive.setAttribute("aria-pressed", "true");
					activeThemeIcon.setAttribute("class", svgOfActiveBtn);
					const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`;
					themeSwitcher.setAttribute("aria-label", themeSwitcherLabel);

					if (focus) {
						themeSwitcher.focus();
					}
				};

				window
					.matchMedia("(prefers-color-scheme: dark)")
					.addEventListener("change", () => {
						if (storedTheme !== "light" || storedTheme !== "dark") {
							setTheme(getPreferredTheme());
						}
					});

				window.addEventListener("DOMContentLoaded", () => {
					showActiveTheme(getPreferredTheme());

					for (const toggle of document.querySelectorAll(
							"[data-bs-theme-value]"
						)) {
						toggle.addEventListener("click", () => {
							const theme = toggle.getAttribute("data-bs-theme-value");
							localStorage.setItem("theme", theme);
							setTheme(theme);
							showActiveTheme(theme, true);
						});
					}
				});
			})();
		</script>

		@stack('js')

	</body>
	<!--end::Body-->

</html>
