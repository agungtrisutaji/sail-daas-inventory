<li class="nav-item dropdown user-menu">
	@if (Auth::check())
		<a class="nav-link dropdown-toggle"
			data-bs-toggle="dropdown"
			href="#">
			<img class="user-image rounded-circle shadow"
				src="{{ Vite::asset('resources/images/user.jpg') }}"
				alt="User">
			<span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
		</a>
		<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
			<li class="user-header text-bg-primary">
				<img class="rounded-circle shadow"
					src="{{ Vite::asset('resources/images/user.jpg') }}"
					alt="User">
				<p>
					{{ Auth::user()->name }}
					<small>{{ Auth::user()->email }}</small>
				</p>
			</li>
			{{-- <li class="user-body">
            <div class="row">
                <div class="col-4 text-center">
                    <a href="#">Followers</a>
                </div>
                <div class="col-4 text-center">
                    <a href="#">Sales</a>
                </div>
                <div class="col-4 text-center">
                    <a href="#">Friends</a>
                </div>
            </div>
        </li> --}}
			<li class="user-footer">
				<a class="btn btn-success btn-flat"
					href="{{ route('profile.edit') }}">Profile</a>
				<form class="float-end"
					method="POST"
					action="{{ route('logout') }}">
					@csrf

					<a class="btn btn-danger btn-flat"
						href="route('logout')"
						onclick="event.preventDefault();
                                    this.closest('form').submit();">
						{{ __('Log Out') }}
					</a>
				</form>
			</li>
		</ul>
	@else
		<a class="nav-link"
			href="{{ route('login') }}">
			Login
		</a>
	@endif
</li>
