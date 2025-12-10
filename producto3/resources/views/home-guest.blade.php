<x-guest-layout>
    <div class="container py-5">
        <div class="row align-items-center mb-5">
            <div class="col-12 col-lg-7 mb-4 mb-lg-0">
                <h1 class="display-5 fw-bold mb-3 text-center text-lg-start">Bienvenido a Isla Transfers</h1>
                <p class="lead mb-4 text-center text-lg-start">Gestiona las reservas de transfers entre aeropuerto y
                    hotel de forma rápida, segura y centralizada para viajeros, hoteles y administradores.</p>
                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center justify-content-lg-start">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">Crear cuenta de viajero</a>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5 mb-3 text-center text-lg-start">Accesos rápidos</h2>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">✅ Viajero: consulta y gestiona tus reservas.</li>
                            <li class="mb-2">✅ Hotel: crea reservas para tus clientes.</li>
                            <li class="mb-0">✅ Admin: controla destinos, vehículos y tarifas.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="h5 mb-2">Para viajeros</h3>
                        <p class="mb-0">Reserva tu transfer en pocos pasos y recibe toda la información del servicio
                            en tu correo electrónico.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="h5 mb-2">Para hoteles</h3>
                        <p class="mb-0">Centraliza las reservas de tus clientes y consulta el histórico de servicios
                            realizados desde un único panel.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="h5 mb-2">Para administración</h3>
                        <p class="mb-0">Gestiona zonas, vehículos, tarifas y usuarios manteniendo el control de toda
                            la operativa de Isla Transfers.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
