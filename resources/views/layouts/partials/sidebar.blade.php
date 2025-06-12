<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"><span>Main</span></li>
                <li class="{{ setActive('admin/dashboard') }}">
                    <a href="{{ route('admin.dashboard.index') }}"><i data-feather="home"></i>
                        <span>Dashboard</span></a>
                </li>

                @if(auth()->user()->can('roles.index') || auth()->user()->can('permission.index') || auth()->user()->can('users.index'))
                    <li class="submenu {{ setActive('admin/role'). setActive('admin/permission'). setActive('admin/user') }}">
                        <a href="#"><i data-feather="user"></i> <span> Manajemen Pengguna</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            @can('permissions.index')
                                <li class="{{ setActive('admin/permission') }}"><a
                                        href="{{ route('admin.permission.index') }}">
                                        <i data-feather="key"></i> Permission</a></li>
                            @endcan

                            @can('roles.index')
                                <li class="{{ setActive('admin/role') }}"><a
                                        href="{{ route('admin.role.index') }}">
                                        <i data-feather="unlock"></i> Roles</a></li>
                            @endcan

                            @can('users.index')
                                <li class="{{ setActive('admin/user') }}"><a href="{{ route('admin.user.index') }}">
                                        <i data-feather="users"></i> Pengguna</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                <li class="menu-title">
                    <span>Master Data</span>
                </li>

                @if(auth()->user()->can('securities.index') || auth()->user()->can('submissions.index') || auth()->user()->can('announcements.index'))
                    <li class="submenu {{ setActive('admin/security'). setActive('admin/submission'). setActive('admin/announcement') }}">
                        <a href="#"><i data-feather="list"></i> <span> Manajemen Data</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            @can('securities.index')
                                <li class="{{ setActive('admin/security') }}"><a
                                        href="{{ route('admin.security.index') }}">
                                        <i data-feather="shield"></i> Keamanan</a></li>
                            @endcan

                            @can('submissions.index')
                                <li class="{{ setActive('admin/submission') }}"><a
                                        href="{{ route('admin.submission.index') }}">
                                        <i data-feather="file"></i> Administrasi</a></li>
                            @endcan

                            @can('announcements.index')
                                <li class="{{ setActive('admin/announcement') }}"><a
                                        href="{{ route('admin.announcement.index') }}">
                                        <i class="fa fa-bullhorn mr-1"></i> Pengumuman</a></li>
                            @endcan


                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('services.index'))
                    <li class="submenu {{ setActive('admin/service') }}">
                        <a href="#"><i data-feather="list"></i> <span> Manajemen Administrasi</span> <span
                                class="menu-arrow"></span></a>
                        <ul>

                            @can('services.index')
                                <li class="{{ setActive('admin/service') }}"><a
                                        href="{{ route('admin.service.index') }}">
                                        <i data-feather="file"></i> Data Layanan</a></li>
                            @endcan

                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('blood_types.index'))
                    <li class="submenu {{ setActive('admin/blood') }}">
                        <a href="#"><i data-feather="list"></i> <span> Manajemen Kesehatan</span> <span
                                class="menu-arrow"></span></a>
                        <ul>

                            @can('blood_types.index')
                                <li class="{{ setActive('admin/blood') }}"><a href="{{ route('admin.blood.index') }}">
                                        <i data-feather="droplet"></i> Data Golongan Darah</a></li>
                            @endcan

                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('sliders.index') || auth()->user()->can('categories.index'))
                    <li class="submenu {{ setActive('admin/slider'). setActive('admin/category') }}">
                        <a href="#"><i data-feather="list"></i> <span> Manajemen Ekonomi</span> <span
                                class="menu-arrow"></span></a>
                        <ul>

                            @can('sliders.index')
                                <li class="{{ setActive('admin/slider') }}"><a href="{{ route('admin.slider.index') }}">
                                        <i data-feather="image"></i> Data Banner</a></li>
                            @endcan

                            @can('categories.index')
                                <li class="{{ setActive('admin/category') }}"><a href="{{ route('admin.category.index') }}">
                                        <i data-feather="list"></i> Data Kategori</a></li>
                            @endcan

                        </ul>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>
