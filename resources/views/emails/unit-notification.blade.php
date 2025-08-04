<!DOCTYPE html>
<html>

	<head>
		<title>Unit {{ $modelName }} Notification</title>
	</head>

	<body>
		<h1>Hello {{ $user->name }}</h1>

		<div>
			@if ($hasUnits)
				Some unit has been updated to {{ $modelName }} status at <span
					style="font-weight: bold;">{{ $timestamp }}</span>

				Here is the list of units:

				<table style="width: 100%; border-collapse: collapse; margin: 25px 0;">
					<thead>
						<tr style="background-color: #f8f9fa;">
							<th style="border: 1px solid #ddd; padding: 5px; text-align: left;">Serial</th>
							<th style="border: 1px solid #ddd; padding: 5px; text-align: left;">Deployment ID</th>
							<th style="border: 1px solid #ddd; padding: 5px; text-align: left;">{{ $modelName }} ID</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($units as $index => $unit)
							<tr>
								<td style="border: 1px solid #ddd; padding: 5px; text-align: left;"><strong>{{ $unit }}</strong></td>
								<td style="border: 1px solid #ddd; padding: 5px; text-align: left;">
									<strong>{{ $deploymentIds[$index] }}</strong>
								</td>
								<td style="border: 1px solid #ddd; padding: 5px; text-align: left;"><strong>{{ $modelIds[$index] }}</strong>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<span style="font-weight: bold;">{{ $timestamp }}</span>, No Unit has been updated to {{ $modelName }} status.
			@endif
		</div>

		<p>Thanks,</p>

		<p>
			{{ config('app.name') }}
		</p>
	</body>

</html>
