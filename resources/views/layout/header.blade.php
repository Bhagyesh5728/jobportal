<nav class="navbar navbar-expand-custom navbar-mainbg">
    <a class="navbar-brand navbar-logo" href="{{ route('site.index') }}">Job Portal</a>
    <button class="navbar-toggler" type="button" aria-controls="navbarSupportedContent" aria-expanded="false"
        aria-label="Toggle navigation">
        <i class="fas fa-bars text-white"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent" style="margin-right: 20px;">
        <ul class="navbar-nav ml-auto">
            <div class="hori-selector">
                <div class="left"></div>
                <div class="right"></div>
            </div>
            <li class="nav-item {{ Route::is('site.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('site.index') }}"><i class="fas fa-tachometer-alt"></i>Jobs</a>
            </li>
            @Auth
                <li class="nav-item  ">
                    <a class="nav-link {{ Route::is('my.resume') ? 'active text-primary' : '' }} " href="{{ route('my.resume') }}">My Resume</a>
                </li>
                <li class="nav-item {{ Route::is('save.jobs') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('save.jobs') }}"><i class="far fa-clone"></i>Saved Jobs</a>
                </li>
            @endauth
        </ul>
    </div>
    @if (Auth::check())
        <div style="margin-right: 20px;">
            <a href="{{ route('logout') }}" class="btn btn-sm btn-primary w-full w-lg-auto">
                Logout
            </a>
        </div>
    @else
        <div style="margin-right: 20px;">
            <a href="#" class="btn btn-sm btn-primary w-full w-lg-auto" data-bs-toggle="modal"
                data-bs-target="#loginModal">
                Login
            </a>
        </div>

        <div class="d-flex align-items-lg-center mt-3 mt-lg-0" style="margin-right: 20px;">
            <a href="#" class="btn btn-sm btn-secondary w-full w-lg-auto" data-bs-toggle="modal"
                data-bs-target="#registerModal">
                Register
            </a>
        </div>
    @endif

</nav>
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>

            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Register</h5>

            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
