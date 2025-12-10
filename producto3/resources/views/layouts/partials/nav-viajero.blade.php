<ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('viajero.dashboard') ? 'active' : '' }}"
            href="{{ route('viajero.dashboard') }}">
            Panel
        </a>
    </li>
    {{-- Más enlaces para viajero más adelante --}}
</ul>
