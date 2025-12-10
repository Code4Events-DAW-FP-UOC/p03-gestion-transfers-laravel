<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Panel de hotel</h1>
    </x-slot>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-2">Reservas de mis clientes</h2>
                    <p class="mb-3">
                        Crea y consulta reservas de transfer asociadas a tu hotel.
                    </p>
                    <a href="#" class="btn btn-primary btn-sm">Ver reservas</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-2">Datos del hotel</h2>
                    <p class="mb-3">
                        Revisa la información de tu hotel y la comisión asociada.
                    </p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Ver datos</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
