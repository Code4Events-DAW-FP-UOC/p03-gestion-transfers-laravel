<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Panel de administración</h1>
    </x-slot>

    <div class="row g-4">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-2">Gestión de reservas</h2>
                    <p class="mb-3">
                        Revisa y controla todas las reservas de Isla Transfers.
                    </p>
                    <a href="#" class="btn btn-primary btn-sm">Ver reservas</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-2">Zonas, vehículos y precios</h2>
                    <p class="mb-3">
                        Configura destinos, flota y tarifas de los transfers.
                    </p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Configurar</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-2">Usuarios</h2>
                    <p class="mb-3">
                        Gestiona viajeros, hoteles y cuentas de acceso.
                    </p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Gestionar usuarios</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
