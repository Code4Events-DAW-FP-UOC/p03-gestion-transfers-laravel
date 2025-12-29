<ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('hotel.dashboard') ? 'active' : '' }}"
            href="{{ route('hotel.dashboard') }}">
            Panel
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('hotel.reservas') ? 'active' : '' }}"
            href="{{ route('hotel.reservas.index') }}">
            Reservas
        </a>
    </li>
</ul>
