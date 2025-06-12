<div class="header">

    <div class="header-left">
        <a href="{{route('admin.dashboard.index')}}" class="logo">
            <img src="{{ asset('assets/img/logo.png')}}" alt="Logo">
        </a>
        <a href="{{route('admin.dashboard.index')}}" class="logo logo-small">
            <img src="{{ asset('assets/img/logo-small.png')}}" alt="Logo" width="30" height="30">
        </a>
    </div>

    <a href="javascript:void(0);" id="toggle_btn">
        <i class="fas fa-bars"></i>
    </a>

    <a class="mobile_btn" id="mobile_btn">
        <i class="fas fa-bars"></i>
    </a>

    <ul class="nav user-menu">

        <li class="nav-item dropdown has-arrow main-drop">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                <span class="user-img">
                    <img src="{{auth()->user()->image}}" alt="">
                    <span class="status online"></span>
                </span>
                <span>{{ auth()->user()->name }}</span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="profile.html"><i data-feather="user" class="mr-1"></i> Profile</a>
                <a class="dropdown-item" href="javascript:void(0)" style="cursor: pointer" onclick="logoutFunc()"><i
                        data-feather="log-out" class="mr-1"></i> Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>

    </ul>

</div>
