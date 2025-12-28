{{-- resources/views/layouts/app.blade.php --}}
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Bootstrap Icons por CDN -->    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
</head>

<body class="bg-light">
    <div class="min-vh-100 d-flex flex-column">
        @include('layouts.navigation')
        @isset($header)
            <header class="bg-white border-bottom shadow-sm">
                <div class="container py-3">
                    {{ $header }}
                </div>
            </header>
        @endisset
        <main class="flex-grow-1 py-4">
            <div class="container">
                {{-- Mensajes flash (status, error, validación) --}}
                @include('layouts.partials.flash-messages')
                {{ $slot }}
            </div>
        </main>

        <footer class="footer">
            <div class="container text-center">
                &copy; {{ now()->year }} Isla Transfers
            </div>
        </footer>
    </div>
    {{-- Bootstrap JS (bundle incluye Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
    @stack('scripts')
</body>

</html>