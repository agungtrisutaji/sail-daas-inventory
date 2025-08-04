<x-app-layout>
	<x-slot name="header">
		{{ __('Dashboard') }}
	</x-slot>
	<!-- Main row -->
	<div class="container-fluid"> <!-- Info boxes -->

		<div class="row"> <!-- Start col -->
			<div class="col-md-12"> <!--begin::Row-->

				<div class="card mb-4">
					<div class="card-header">
						<h3 class="card-title">Ongoing Operation</h3>
						<div class="card-tools"> <button class="btn btn-tool"
								data-lte-toggle="card-collapse"
								type="button"> <i class="bi bi-plus-lg"
									data-lte-icon="expand"></i> <i class="bi bi-dash-lg"
									data-lte-icon="collapse"></i> </button> <button class="btn btn-tool"
								data-lte-toggle="card-remove"
								type="button"> <i class="bi bi-x-lg"></i> </button> </div>
					</div> <!-- /.card-header -->
					<div class="card-body"> <!--begin::Row-->
						<div class="row">
							<div class="col-lg-4 col-6"> <!--begin::Small Box Widget 1-->
								<div class="small-box text-bg-primary">
									<div class="inner">
										<h3>{{ $stagingCount }}</h3>
										<p>Staging</p>
									</div> <svg class="small-box-icon"
										aria-hidden="true"
										fill="currentColor"
										viewBox="0 0 24 24"
										xmlns="http://www.w3.org/2000/svg">
										<path
											d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z">
										</path>
									</svg> <a class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
										href="{{ route('staging') }}">
										More info <i class="bi bi-link-45deg"></i> </a>
								</div> <!--end::Small Box Widget 1-->
							</div> <!--end::Col-->

							<div class="col-lg-4 col-6"> <!--begin::Small Box Widget 2-->
								<div class="small-box text-bg-success">
									<div class="inner">
										<h3>{{ $deliveryCount }}</h3>
										<p>Delivery</p>
									</div> <svg class="small-box-icon"
										aria-hidden="true"
										fill="currentColor"
										viewBox="0 0 24 24"
										xmlns="http://www.w3.org/2000/svg">
										<path
											d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z">
										</path>
									</svg> <a class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
										href="{{ route('delivery') }}">
										More info <i class="bi bi-link-45deg"></i> </a>
								</div> <!--end::Small Box Widget 2-->
							</div> <!--end::Col-->

							<div class="col-lg-4 col-6"> <!--begin::Small Box Widget 3-->
								<div class="small-box text-bg-warning">
									<div class="inner">
										<h3>{{ $deploymentCount }}</h3>
										<p>Deployment</p>
									</div> <svg class="small-box-icon"
										aria-hidden="true"
										fill="currentColor"
										viewBox="0 0 24 24"
										xmlns="http://www.w3.org/2000/svg">
										<path
											d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
										</path>
									</svg> <a class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover"
										href="{{ route('deployment') }}">
										More info <i class="bi bi-link-45deg"></i> </a>
								</div> <!--end::Small Box Widget 3-->
							</div> <!--end::Col-->

							<div class="col-lg-4 col-6"> <!--begin::Small Box Widget 4-->
								<div class="small-box text-bg-danger">
									<div class="inner">
										<h3>{{ $terminationCount }}</h3>
										<p>Termination</p>
									</div> <svg class="small-box-icon"
										aria-hidden="true"
										fill="currentColor"
										viewBox="0 0 24 24"
										xmlns="http://www.w3.org/2000/svg">
										<path clip-rule="evenodd"
											fill-rule="evenodd"
											d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z">
										</path>
										<path clip-rule="evenodd"
											fill-rule="evenodd"
											d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z">
										</path>
									</svg> <a class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
										href="{{ route('termination') }}">
										More info <i class="bi bi-link-45deg"></i> </a>
								</div> <!--end::Small Box Widget 4-->
							</div> <!--end::Col-->

							<div class="col-lg-4 col-6"> <!--begin::Small Box Widget 4-->
								<div class="small-box text-bg-info">
									<div class="inner">
										<h3>0</h3>
										<p>Claim</p>
									</div>
									<svg class="small-box-icon"
										id="svg1976"
										aria-hidden="true"
										viewBox="0 0 6.3500002 6.3500002"
										version="1.1"
										fill="currentColor"
										xmlns="http://www.w3.org/2000/svg"
										xmlns:cc="http://creativecommons.org/ns#"
										xmlns:dc="http://purl.org/dc/elements/1.1/"
										xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
										xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
										xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
										xmlns:svg="http://www.w3.org/2000/svg">

										<defs id="defs1970" />

										<g id="layer1"
											style="display:inline">

											<path id="path688"
												clip-rule="evenodd"
												fill-rule="evenodd"
												d="M 5.2190346,0.50408661 C 5.1491502,0.50384028 5.0778291,0.51623363 5.0071589,0.54491103 3.589479,1.1201723 2.9862396,0.86372787 2.2290338,0.62345921 c 0.012197,0.0548756 0.019129,0.1111676 0.019129,0.1694987 V 2.2466161 c 0.9612793,0.4710107 2.1380401,0.37279 3.188436,-0.1524455 A 0.2645835,0.2645835 0 0 1 5.4758736,2.07815 V 2.6620937 C 4.4344497,3.1285483 3.2672191,3.2323208 2.2481632,2.8238409 V 4.6257981 C 3.1705404,4.8256334 4.1479562,4.815116 5.1808023,4.5121101 5.3756811,4.4549098 5.5718326,4.3692748 5.7316727,4.2206549 5.8915128,4.072035 6.0050402,3.8471026 6.0050402,3.5917527 V 1.4569991 c 0,-0.2578899 -0.094456,-0.50246094 -0.2578655,-0.69298079 C 5.6246541,0.62112823 5.4286931,0.50482506 5.2190425,0.50408582 Z" />

											<path id="rect690"
												clip-rule="evenodd"
												fill-rule="evenodd"
												d="m 1.1379299,1.9502e-4 c -0.43516003,0 -0.79297003,0.3578 -0.79297003,0.79296 V 5.556835 c 0,0.43516 0.35781,0.79297 0.79297003,0.79297 0.43516,0 0.79297,-0.35781 0.79297,-0.79297 V 0.79315502 c 0,-0.43516 -0.35781,-0.79296 -0.79297,-0.79296 z" />

										</g>

									</svg>
									<a class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
										href="#">
										More info <i class="bi bi-link-45deg"></i> </a>
								</div> <!--end::Small Box Widget 4-->
							</div> <!--end::Col-->

							<div class="col-lg-4 col-6"> <!--begin::Small Box Widget 4-->
								<div class="small-box text-bg-light">
									<div class="inner">
										<h3>0</h3>
										<p>Asset Transfer</p>
									</div>

									<svg class="small-box-icon"
										id="svg1976"
										aria-hidden="true"
										viewBox="0 0 6.3500002 6.3500002"
										version="1.1"
										xmlns="http://www.w3.org/2000/svg"
										xmlns:cc="http://creativecommons.org/ns#"
										xmlns:dc="http://purl.org/dc/elements/1.1/"
										xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
										xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
										xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
										xmlns:svg="http://www.w3.org/2000/svg"
										fill="currentColor">
										<g id="SVGRepo_bgCarrier"
											stroke-width="0"></g>
										<g id="SVGRepo_tracerCarrier"
											stroke-linecap="round"
											stroke-linejoin="round"></g>
										<g id="SVGRepo_iconCarrier">
											<defs id="defs1970"></defs>
											<g id="layer1"
												style="display:inline">
												<path id="path660"
													d="M 4.8168655,0.00127766 C 4.6890373,0.00562477 4.5613573,0.02597387 4.4370455,0.06070573 3.9398008,0.19964558 3.4688398,0.53025887 3.0211092,0.92421897 2.1256507,1.712159 1.3105118,2.7893392 0.8806723,3.2191694 a 0.26460945,0.26460945 0 0 0 0,0.374654 L 2.7849474,5.4981 a 0.26460945,0.26460945 0 0 0 0.3731049,0 C 3.590482,5.06566 4.6594992,4.2400777 5.4374986,3.3344079 5.8265128,2.8815777 6.1505428,2.4049009 6.2849934,1.9045209 6.4194441,1.4041409 6.3263981,0.85817657 5.9041389,0.4358767 5.5874195,0.1191588 5.2003552,-0.01176471 4.8168655,0.00127766 Z M 4.4845858,1.0885499 c 0.2062348,3.439e-4 0.4126019,0.07877 0.5684415,0.234611 0.3116818,0.3116808 0.3116791,0.8236527 0,1.1353314 -0.3116845,0.3116855 -0.8251984,0.3127142 -1.1368829,0.00103 -0.3116792,-0.3116813 -0.3116845,-0.8262295 0,-1.1379151 C 4.0719866,1.1657698 4.2783537,1.0882027 4.4845858,1.0885483 Z M 2.1395096,2.6026696 3.7699012,4.2330609 C 3.6323708,4.3548619 3.4989362,4.4745025 3.3735422,4.5849774 L 1.7901776,3.0036787 C 1.9002601,2.8770351 2.0184998,2.7418139 2.1395096,2.6026696 Z">
												</path>
												<path id="path666"
													d="M 1.562284,1.1097372 A 0.26460945,0.26460945 0 0 0 1.3824494,1.1857017 L 0.1034588,2.4667605 a 0.26460945,0.26460945 0 0 0 0.1658805,0.451652 l 0.5307171,0.041858 C 1.2014267,2.5085594 1.746389,1.8496148 2.3720545,1.2156739 L 1.6051756,1.1112876 a 0.26460945,0.26460945 0 0 0 -0.042892,-0.00156 z M 1.9426226,2.5773481 C 1.8103071,2.7294949 1.6817937,2.876367 1.5607335,3.0155643 A 0.26460945,0.26460945 0 0 0 1.7359168,2.9220299 L 1.9875806,2.6223064 Z m 3.2142748,1.407666 C 4.5262947,4.6218117 3.8676357,5.1766596 3.4154019,5.5828493 l 0.041341,0.5229656 A 0.26460945,0.26460945 0 0 0 3.9078788,6.2716962 L 5.1894517,4.9921878 A 0.26460945,0.26460945 0 0 0 5.2633498,4.7699791 Z M 3.7523303,4.3870566 3.4531236,4.6387209 A 0.26460945,0.26460945 0 0 0 3.3595907,4.8201052 C 3.4985102,4.6975791 3.6454942,4.5664428 3.7972909,4.4320152 Z">
												</path>
												<path id="path672"
													d="m 1.3239925,4.7619079 a 0.2645835,0.2645835 0 0 0 -0.17968,0.0762 l -1.0586,1.05859 a 0.2645835,0.2645835 0 0 0 0,0.375 0.2645835,0.2645835 0 0 0 0.375,0 l 1.0586,-1.05859 a 0.2645835,0.2645835 0 0 0 0,-0.375 0.2645835,0.2645835 0 0 0 -0.19532,-0.0762 z">
												</path>
												<path id="path674"
													d="m 0.7947025,3.9669879 a 0.2645835,0.2645835 0 0 0 -0.17969,0.0781 l -0.5293,0.52929 a 0.2645835,0.2645835 0 0 0 0,0.375 0.2645835,0.2645835 0 0 0 0.375,0 l 0.5293,-0.52929 a 0.2645835,0.2645835 0 0 0 0,-0.375 0.2645835,0.2645835 0 0 0 -0.19531,-0.0781 z">
												</path>
												<path id="path676"
													d="m 2.1169625,5.2912079 a 0.2645835,0.2645835 0 0 0 -0.17773,0.0762 l -0.5293,0.5293 a 0.2645835,0.2645835 0 0 0 0,0.375 0.2645835,0.2645835 0 0 0 0.37305,0 l 0.52929,-0.5293 a 0.2645835,0.2645835 0 0 0 0,-0.375 0.2645835,0.2645835 0 0 0 -0.19531,-0.0762 z">
												</path>
											</g>
										</g>
									</svg>
									<a class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
										href="#">
										More info <i class="bi bi-link-45deg"></i> </a>
								</div> <!--end::Small Box Widget 4-->
							</div> <!--end::Col-->

						</div> <!--end::Row-->
					</div> <!-- /.card-body -->
				</div> <!-- /.card -->

			</div> <!-- /.col -->

		</div> <!--end::Row-->

		<div class="row"> <!-- Start col -->
			<div class="col-md-12"> <!--begin::Row-->
				<div class="row g-4 mb-4">

					<div class="col-md-4">

						<div class="card mb-4">
							<div class="card-header">
								<h3 class="card-title">Unit Status</h3>
								<div class="card-tools"> <button class="btn btn-tool"
										data-lte-toggle="card-collapse"
										type="button"> <i class="bi bi-plus-lg"
											data-lte-icon="expand"></i> <i class="bi bi-dash-lg"
											data-lte-icon="collapse"></i> </button> <button class="btn btn-tool"
										data-lte-toggle="card-remove"
										type="button"> <i class="bi bi-x-lg"></i> </button> </div>
							</div> <!-- /.card-header -->
							<div class="card-body"> <!--begin::Row-->
								<div class="row">
									<div class="col-12">
										<div id="pie-chart"></div>
									</div> <!-- /.col -->
								</div> <!--end::Row-->
							</div> <!-- /.card-body -->
						</div> <!-- /.card -->

					</div>

					<div class="col-md-8">
						<div class="card mb-4">
							<div class="card-header">
								<h5 class="card-title">Monthly Performance by <strong>SLA</strong></h5>
								<div class="card-tools"> <button class="btn btn-tool"
										data-lte-toggle="card-collapse"
										type="button"> <i class="bi bi-plus-lg"
											data-lte-icon="expand"></i> <i class="bi bi-dash-lg"
											data-lte-icon="collapse"></i> </button>
									<button class="btn btn-tool"
										data-lte-toggle="card-remove"
										type="button"> <i class="bi bi-x-lg"></i> </button>
								</div>
							</div> <!-- /.card-header -->
							<div class="card-body"> <!--begin::Row-->
								<div class="row">
									<div class="col-md-8">

										<div id="operationChart"></div>
									</div> <!-- /.col -->

									<div class="col-md-4">
										<p class="text-center"> <strong>Performance Precentage</strong> </p>
										<div class="progress-group">
											Staging
											<span class="float-end">{{ $chartPerformanceData['staging']['performance'] }}%</span>
											<div class="progress progress-sm">
												<div class="progress-bar text-bg-primary"
													style="width: {{ $chartPerformanceData['staging']['performance'] }}%"></div>
											</div>
										</div> <!-- /.progress-group -->

										<div class="progress-group">
											Deployment
											<span class="float-end">{{ $chartPerformanceData['deployment']['performance'] }}%</span>
											<div class="progress progress-sm">
												<div class="progress-bar text-bg-success"
													style="width: {{ $chartPerformanceData['deployment']['performance'] }}%"></div>
											</div>
										</div> <!-- /.progress-group -->

										<div class="progress-group"> <span class="progress-text">Termination</span> <span
												class="float-end">{{ $chartPerformanceData['termination']['performance'] }}%</span>
											<div class="progress progress-sm">
												<div class="progress-bar text-bg-danger"
													style="width: {{ $chartPerformanceData['termination']['performance'] }}%"></div>
											</div>
										</div> <!-- /.progress-group -->

									</div> <!-- /.col -->
								</div> <!--end::Row-->
							</div> <!-- ./card-body -->
						</div> <!-- /.card -->
					</div> <!-- /.col -->

				</div> <!--end::Row--> <!--begin::Latest Order Widget-->

			</div> <!-- /.col -->

		</div> <!--end::Row-->

		<div class="row"> <!-- Start col -->
			<div class="col-md-12"> <!--begin::Row-->

				<div class="card mb-4">
					<div class="card-header">
						<h3 class="card-title">Available Units by Model</h3>
						<div class="card-tools"> <button class="btn btn-tool"
								data-lte-toggle="card-collapse"
								type="button"> <i class="bi bi-plus-lg"
									data-lte-icon="expand"></i> <i class="bi bi-dash-lg"
									data-lte-icon="collapse"></i> </button> <button class="btn btn-tool"
								data-lte-toggle="card-remove"
								type="button"> <i class="bi bi-x-lg"></i> </button> </div>
					</div> <!-- /.card-header -->
					<div class="card-body"> <!--begin::Row-->
						<div class="row">
							<div class="col-12">
								<div id="available-units-chart2"></div>
							</div> <!-- /.col -->
						</div> <!--end::Row-->
					</div> <!-- /.card-body -->
				</div> <!-- /.card -->

			</div> <!-- /.col -->

		</div> <!--end::Row-->

	</div> <!--end::Container-->

	@push('js')
		<script>
			const unitAvailableChart = async (config) => {
				let currentChart = null; // Untuk menyimpan instance chart

				const createChart = async () => {
					const theme = document.documentElement.getAttribute('data-bs-theme');
					const {
						endpoint,
						elementId,
						isHorizontal = false,
						seriesName = '',
						height = 350,
						colors = ['#0d6efd']
					} = config;

					try {
						const response = await fetch(endpoint);
						const data = await response.json();

						const chartOptions = {
							series: [{
								name: seriesName,
								data: data.counts
							}],
							chart: {
								type: 'bar',
								height: height
							},
							plotOptions: {
								bar: {
									horizontal: isHorizontal,
									columnWidth: '55%',
									endingShape: 'rounded'
								},
							},
							dataLabels: {
								enabled: false
							},
							xaxis: {
								categories: data.models,
							},
							theme: {
								mode: theme,
								palette: 'palette1',
							},
							colors: colors,
							tooltip: {
								y: {
									formatter: function(val) {
										return val + " units"
									}
								}
							}
						};


						// Destroy chart lama jika ada
						if (currentChart) {
							currentChart.destroy();
						}

						// Buat chart baru
						currentChart = new ApexCharts(
							document.querySelector(elementId),
							chartOptions
						);
						currentChart.render();

						return currentChart;
					} catch (error) {
						console.error('Error fetching chart data:', error);
					}
				};

				// Observer untuk memantau perubahan tema
				const observer = new MutationObserver((mutations) => {
					mutations.forEach((mutation) => {
						if (mutation.attributeName === 'data-bs-theme') {
							createChart(); // Buat ulang chart ketika tema berubah
						}
					});
				});

				// Mulai memantau perubahan pada tag html
				observer.observe(document.documentElement, {
					attributes: true,
					attributeFilter: ['data-bs-theme']
				});

				// Buat chart pertama kali
				await createChart();

				// Return fungsi cleanup untuk membuang observer jika diperlukan
				return () => {
					observer.disconnect();
					if (currentChart) {
						currentChart.destroy();
					}
				};
			};

			const unitStatusChart = async (config) => {
				let currentChart = null; // Untuk menyimpan instance chart

				const createChart = async () => {
					const theme = document.documentElement.getAttribute('data-bs-theme');
					const {
						endpoint,
						elementId,
					} = config;

					try {
						const response = await fetch(endpoint);
						const data = await response.json();

						const chartOptions = {
							series: data.series,
							chart: {
								type: "donut",
							},
							labels: data.labels,
							dataLabels: {
								enabled: false,
							},
							colors: [
								"#0d6efd", "#20c997", "#ffc107", "#d63384",
								"#6f42c1", "#adb5bd", "#198754", "#dc3545",
								"#0dcaf0", "#fd7e14", "#6610f2", "#d63384",
								"#198754", "#0dcaf0", "#6f42c1", "#adb5bd"
							],
							theme: {
								mode: theme,
								palette: 'palette1',
							},
						};


						// Destroy chart lama jika ada
						if (currentChart) {
							currentChart.destroy();
						}

						// Buat chart baru
						currentChart = new ApexCharts(
							document.querySelector(elementId),
							chartOptions
						);
						currentChart.render();

						return currentChart;
					} catch (error) {
						console.error('Error fetching chart data:', error);
					}
				};

				// Observer untuk memantau perubahan tema
				const observer = new MutationObserver((mutations) => {
					mutations.forEach((mutation) => {
						if (mutation.attributeName === 'data-bs-theme') {
							createChart(); // Buat ulang chart ketika tema berubah
						}
					});
				});

				// Mulai memantau perubahan pada tag html
				observer.observe(document.documentElement, {
					attributes: true,
					attributeFilter: ['data-bs-theme']
				});

				// Buat chart pertama kali
				await createChart();

				// Return fungsi cleanup untuk membuang observer jika diperlukan
				return () => {
					observer.disconnect();
					if (currentChart) {
						currentChart.destroy();
					}
				};
			};

			const monthlyPerformanceChart = async (config) => {
				let currentChart = null; // Untuk menyimpan instance chart

				const createChart = async () => {
					const theme = document.documentElement.getAttribute('data-bs-theme');

					const chartData = @json($chartData);

					// Atau langsung di JavaScript
					function getLastSixMonths() {
						const dates = [];
						const today = new Date();

						for (let i = 6; i >= 0; i--) {
							const date = new Date(today);
							date.setMonth(today.getMonth() - i);
							dates.push(date.toISOString().split('T')[0]);
						}

						return dates;
					}
					const {
						elementId,
					} = config;

					try {
						const chartOptions = {
							series: [{
									name: 'Staging',
									data: chartData.staging.meet,

								},
								{
									name: 'Deployment',
									data: chartData.deployment.meet

								},
								{
									name: 'Termination',
									data: chartData.termination.meet

								},
							],
							chart: {
								type: 'area',
								height: 250,
								// toolbar: {
								// 	show: false,
								// },
							},
							stroke: {
								curve: "smooth",
							},
							legend: {
								show: false,
							},
							title: {
								text: 'SLA Performance'
							},
							xaxis: {
								type: "datetime",
								categories: chartData.lastSixthMonth,
							},
							yaxis: {
								title: {
									text: 'Number of Cases'
								},
							},
							tooltip: {
								x: {
									format: "MMMM yyyy",
								},
							},
							colors: ['#0d6efd', '#00E396', '#FF4560'],

							theme: {
								mode: theme,
								palette: 'palette1',
							},
						};


						// Destroy chart lama jika ada
						if (currentChart) {
							currentChart.destroy();
						}

						// Buat chart baru
						currentChart = new ApexCharts(
							document.querySelector(elementId),
							chartOptions
						);
						currentChart.render();

						return currentChart;
					} catch (error) {
						console.error('Error fetching chart data:', error);
					}
				};

				// Observer untuk memantau perubahan tema
				const observer = new MutationObserver((mutations) => {
					mutations.forEach((mutation) => {
						if (mutation.attributeName === 'data-bs-theme') {
							createChart(); // Buat ulang chart ketika tema berubah
						}
					});
				});

				// Mulai memantau perubahan pada tag html
				observer.observe(document.documentElement, {
					attributes: true,
					attributeFilter: ['data-bs-theme']
				});

				// Buat chart pertama kali
				await createChart();

				// Return fungsi cleanup untuk membuang observer jika diperlukan
				return () => {
					observer.disconnect();
					if (currentChart) {
						currentChart.destroy();
					}
				};
			};

			$(function() {
				"use strict";

				// Horizontal chart
				unitAvailableChart({
					endpoint: '/api/available-units-by-model',
					elementId: '#available-units-chart2',
					isHorizontal: true,
					seriesName: 'Available Units',
					height: 1000,
				});

				unitStatusChart({
					endpoint: '/api/unit-status-chart',
					elementId: '#pie-chart',
				});

				monthlyPerformanceChart({
					elementId: '#operationChart',
				});
			});
		</script> <!--end::Script-->
	@endpush
</x-app-layout>
