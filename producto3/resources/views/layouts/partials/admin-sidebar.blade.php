{{-- resources/views/layouts/partials/admin-sidebar.blade.php --}}
@php
    // para marcar el enlace activo
    $isActive = fn (string $pattern) => request()->routeIs($pattern) ? 'active' : '';
@endphp

<aside class="bg-white border-end shadow-sm">
    <div class="d-flex flex-column h-100 p-3">

        {{-- Botón colapsable SOLO en móvil --}}
        <button class="btn btn-outline-secondary d-md-none w-100 mb-3"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#adminSidebarNav"
                aria-controls="adminSidebarNav"
                aria-expanded="false"
                aria-label="{{ __('Mostrar menú administración') }}">
            <i class="bi bi-list me-2"></i>
            {{ __('Menú administración') }}
        </button>

        {{-- Menú (siempre visible en md+ gracias a d-md-block) --}}
        <div class="collapse d-md-block" id="adminSidebarNav">
            <ul class="nav nav-pills flex-column gap-2 small">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link d-flex align-items-center {{ $isActive('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>

                {{-- Reservas --}}
                <li class="nav-item">
                    <a href="{{ route('admin.reservas.index') }}"
                       class="nav-link d-flex align-items-center {{ $isActive('admin.reservas.*') }}">
                        <i class="bi bi-calendar-check me-2"></i>
                        <span>{{ __('Reservas') }}</span>
                    </a>
                </li>

                {{-- Usuarios (viajeros + hoteles) --}}
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                       class="nav-link d-flex align-items-center {{ $isActive('admin.users.*') }}">
                        <i class="bi bi-people me-2"></i>
                        <span>{{ __('Usuarios') }}</span>
                    </a>
                </li>

                {{-- Hoteles --}}
                <li class="nav-item">
                    <a href="{{ route('admin.hoteles.index') }}"
                       class="nav-link d-flex align-items-center {{ $isActive('admin.hoteles.*') }}">
                        <i class="bi bi-building me-2"></i>
                        <span>{{ __('Hoteles') }}</span>
                    </a>
                </li>

                {{-- Vehículos --}}
                <li class="nav-item">
                    <a href="{{ route('admin.vehiculos.index') }}"
                       class="nav-link d-flex align-items-center {{ $isActive('admin.vehiculos.*') }}">
                        <i class="bi bi-bus-front me-2"></i>
                        <span>{{ __('Vehículos') }}</span>
                    </a>
                </li>

                {{-- Tipos de reserva --}}
                <li class="nav-item">
                    <a href="{{ route('admin.tiposReserva.index') }}"
                       class="nav-link d-flex align-items-center {{ $isActive('admin.tiposReserva.*') }}">
                        <i class="bi bi-list-check me-2"></i>
                        <span>{{ __('Tipos de reserva') }}</span>
                    </a>
                </li>

                {{-- Precios --}}
                <li class="nav-item">
                    <a href="{{ route('admin.precios.index') }}"
                       class="nav-link d-flex align-items-center {{ $isActive('admin.precios.*') }}">
                        <i class="bi bi-cash-coin me-2"></i>
                        <span>{{ __('Precios') }}</span>
                    </a>
                </li>

                {{-- Comisiones --}}
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.comisiones.index') }}"
                    class="nav-link d-flex align-items-center {{ $isActive('admin.comisiones.*') }}">
                        <i class="bi bi-receipt me-2"></i>
                        <span>{{ __('Comisiones') }}</span>
                    </a>
                </li>

                {{-- Zonas --}}
                <li class="nav-item">
                    <a href="{{ route('admin.zonas.index') }}"
                       class="nav-link d-flex align-items-center {{ $isActive('admin.zonas.*') }}">
                        <i class="bi bi-geo-alt me-2"></i>
                        <span>{{ __('Zonas') }}</span>
                    </a>
                </li>

            </ul>
        </div>

    </div>
</aside>