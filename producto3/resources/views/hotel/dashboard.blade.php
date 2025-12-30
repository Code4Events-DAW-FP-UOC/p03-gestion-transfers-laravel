<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Panel de hotel</h1>
    </x-slot>

    <div class="row g-4">
        <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3">
                    <div class="card text-center shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="small text-muted text-uppercase small fw-bold">Total reservas</div>
                            <div class="h4 mb-0 font-weight-bold text-dark">{{ $totalReservas }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card text-center shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="small text-muted text-uppercase small fw-bold">Pendientes</div>
                            <div class="h4 mb-0 text-warning font-weight-bold">{{ $reservasPendientes }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card text-center shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="small text-muted text-uppercase small fw-bold">Realizadas</div>
                            <div class="h4 mb-0 text-success font-weight-bold">{{ $reservasRealizadas }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card text-center shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="small text-muted text-uppercase small fw-bold">Canceladas</div>
                            <div class="h4 mb-0 text-danger font-weight-bold">{{ $reservasCanceladas }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Card de Comisiones del Mes --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 bg-primary text-white">
                        <div class="card-body d-flex justify-content-between align-items-center py-4">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold mb-1">
                                    Comisiones Acumuladas ({{ now()->translatedFormat('F Y') }})
                                </div>
                                <div class="h2 mb-0 font-weight-bold">
                                    {{ number_format($totalComisionesMes, 2, ',', '.') }}€
                                </div>
                            </div>
                            <div class="text-white-50">
                                <i class="fas fa-wallet fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="col-12 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-2">Reservas de mis clientes</h2>
                    <p class="mb-3">
                        Crea y consulta reservas de transfer asociadas a tu hotel.
                    </p>
                    <a href="{{ route('hotel.reservas.index') }}" class="btn btn-primary btn-sm">Ver reservas</a>
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
                    <a href="{{ route('hotel.datos.index') }}" class="btn btn-outline-primary btn-sm">Ver datos</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
