<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Panel de viajero</h1>
    </x-slot>
    <div class="py-4">
        {{-- Resumen de reservas por estado --}}
        <div class="row mb-4">
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">Total reservas</div>
                        <div class="h4 mb-0">{{ $totalReservas }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">Pendientes</div>
                        <div class="h4 mb-0 text-warning">{{ $reservasPendientes }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">Realizadas</div>
                        <div class="h4 mb-0 text-success">{{ $reservasRealizadas }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">Canceladas</div>
                        <div class="h4 mb-0 text-danger">{{ $reservasCanceladas }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5 mb-2">Mis reservas</h2>
                        <p class="mb-3">
                            Consulta y gestiona tus reservas de transfer.
                        </p>
                        <a href="{{ route('viajero.reservas.index') }}" class="btn btn-primary btn-sm">Ver mis
                            reservas</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5 mb-2">Nueva reserva</h2>
                        <p class="mb-3">
                            Crea un nuevo transfer desde el aeropuerto al hotel o viceversa.
                        </p>
                        <a href="{{ route('viajero.reservas.create') }}" class="btn btn-outline-primary btn-sm">Crear
                            reserva</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>