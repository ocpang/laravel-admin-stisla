<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <img src="https://avatars.githubusercontent.com/u/10754039?s=400&u=1fe8bca3ada2aa8bb75913dee46e3b2243f66f2c&v=4" alt="OCTAVIAN PNG" height="50">
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">OCTAVIAN PNG</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{request()->is('back/dashboard', 'back/profile') ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>
            @if(auth()->user()->can('user.index'))
                <li class="menu-header">Admin</li>
                <li class="{{request()->is('back/user', 'back/user/*') ? 'active' : ''}}">
                    <a class="nav-link" href="{{ route('admin.user.index') }}">
                        <i class="fas fa-users"></i> <span>Users</span>
                    </a>
                </li>
            @endif
            @if(auth()->user()->can('role.index'))
                <li class="{{request()->is('back/role', 'back/role/*') ? 'active' : ''}}">
                    <a class="nav-link" href="{{ route('admin.role.index') }}">
                        <i class="fas fa-user-secret"></i> <span>Roles</span>
                    </a>
                </li>
            @endif
            @if(auth()->user()->can('permission.index'))
                <li class="{{request()->is('back/permission', 'back/permission/*') ? 'active' : ''}}">
                    <a class="nav-link" href="{{ route('admin.permission.index') }}">
                        <i class="fas fa-user-lock"></i> <span>Permissions</span>
                    </a>
                </li>
            @endif
            <li class="menu-header">Pages</li>
            @if(auth()->user()->can('product.index'))
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="far fa-file-alt"></i>
                        <span>Products</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="forms-advanced-form.html">Advanced Form</a></li>
                        <li><a class="nav-link" href="forms-editor.html">Editor</a></li>
                        <li><a class="nav-link" href="forms-validation.html">Validation</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </aside>
</div>
