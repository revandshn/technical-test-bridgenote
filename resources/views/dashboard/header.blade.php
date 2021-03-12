{{-- Navbar --}}
<nav class="navbar navbar-expand-sm bg-light">
    <div class="container">
        <a href="{{route('auth.index')}}"><img src="{{asset('logo.jpg')}}" alt="" width="30" height="24"
                class="d-inline-block"></a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{Auth::user()->name}}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{route('dashboard.edit-profile', Auth::user()->id)}}">Edit
                        Profile</a>
                    @if(Auth::user()->role == 1)
                    <a class="dropdown-item" href="{{route('dashboard.admin-user')}}">User list</a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{route('auth.logout')}}">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>