<ul class="nav flex-column pt-3 pt-md-0">
    <li class="nav-item">
        <a href="{{ route('home') }}" class="nav-link d-flex align-items-center">
            <span class="sidebar-icon me-3">
                <img src="{{ asset('images/brand/light.svg') }}" height="20" width="20" alt="Volt Logo">
            </span>
            <span class="mt-1 ms-1 sidebar-text">
                Work Log Management
            </span>
        </a>
    </li>


    <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <a href="{{ route('home') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Dashboard') }}</span>
        </a>
    </li>

   @if(auth()->user()->is_admin)
        <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="nav-link">
                <span class="sidebar-icon me-3">
                    <i class="fas fa-user-alt fa-fw"></i>
                </span>
                <span class="sidebar-text">{{ __('Users') }}</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('projects.*') ? 'active' : '' }}">
            <a href="{{ route('projects.index') }}" class="nav-link">
                <span class="sidebar-icon me-3">
                    <i class="fa fa-folder-open"></i>
                </span>
                <span class="sidebar-text">{{ __('Projects') }}</span>
            </a>
        </li>
   @endif

    <li class="nav-item {{ request()->routeIs('work-logs.*') ? 'active' : '' }}">
        <a href="{{ route('work-logs.index') }}" class="nav-link">
            <span class="sidebar-icon me-3">
                <i class="fa fa-book-open"></i>
            </span>
            <span class="sidebar-text">{{ __('Work Logs') }}</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('work-logs.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <span class="sidebar-icon me-3">
                <i class="fas fa-sign-out-alt"></i>
            </span>
            <span class="sidebar-text">{{ __('Logout') }}</span>
        </a>
    </li>


</ul>
