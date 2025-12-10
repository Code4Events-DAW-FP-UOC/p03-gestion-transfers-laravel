<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Isla Transfers') }}</title>

    <!-- Fuente Montserrat desde Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS por CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

</head>

<body class="bg-light">
    <div class="min-vh-100 d-flex flex-column">
        <header>
            <nav class="navbar navbar-expand-md navbar-dark shadow-sm">
                <div class="container">
                    <!-- Logo + nombre: siempre visible -->
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                        <x-application-logo class="me-2" style="height: 32px;" />
                        <span>Isla Transfers</span>
                    </a>
                    <!-- Botón hamburgesa (visible en movil) -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#guestNavbar" aria-controls="guestNavBar" aria-expanded="false"
                        aria-label="Mostrar navegación">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!-- Contenido colapsable -->
                    <div class="collapse navbar-collapse justify-content-end" id="guestNavbar">
                        <ul class="navbar-nav ms-auto align-items-md-center gap-2">
                            @if (Route::has('login'))
                                @auth
                                    <li class="nav-item">
                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">Ir al
                                            panel</a>
                                    </li>
                                @else
                                    <li class="nav-item"><a href="{{ route('login') }}"
                                            class="btn btn-primary btn-sm">Iniciar sesión</a></li>

                                    @if (Route::has('register'))
                                        <li class="nav-item"><a href="{{ route('register') }}"
                                                class="btn btn-outline-primary btn-sm">Registrarse</a></li>
                                    @endif
                                @endauth
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main class="flex-grow-1 d-flex align-items-center">
            <div class="w-100">
                {{ $slot }}
            </div>
        </main>
        <footer class="py-3 border-top">
            <div class="container text-center small text-muted">
                &copy; {{ now()->year }} Isla Transfers
            </div>
        </footer>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
