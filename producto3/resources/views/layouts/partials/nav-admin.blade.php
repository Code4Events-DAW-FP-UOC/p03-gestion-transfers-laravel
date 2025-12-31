<ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">
            Panel
        </a>
    </li>
</ul>
