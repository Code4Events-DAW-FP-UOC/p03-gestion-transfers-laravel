<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Panel de administración</h1>
    </x-slot>
    <div class="py-4">
        {{-- Resumen de reservas por estado --}}
        <div class="row mb-4">
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">Total reservas</div>
                        <div class="h4 mb-0">{{ $stats['total_reservas'] }}</div>
                    </div>
                </div>
            </div>  
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">Pendientes</div>
                        <div class="h4 mb-0 text-warning">{{ $stats['reservas_pendientes'] }}</div>
                    </div>
                </div>
            </div>  
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">Realizadas</div>
                        <div class="h4 mb-0 text-success">{{ $stats['reservas_realizadas'] }}</div>
                    </div>
                </div>
            </div>  
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">Canceladas</div>
                        <div class="h4 mb-0 text-danger">{{ $stats['reservas_canceladas'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100 border-0 border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="small text-muted fw-bold">MOVIMIENTOS HOY</div>
                                <div class="h2 mb-0 fw-bold">{{ $stats['total_hoy'] }}</div>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-2 rounded text-primary">
                                <i class="bi bi-arrow-down-up fs-4"></i>
                            </div>
                        </div>

                        <div class="d-flex gap-3 pt-2 border-top">
                            <div class="small">
                                <span class="text-success fw-bold">
                                    <i class="bi bi-box-arrow-in-right"></i> {{ $stats['reservas_hoy'] }}
                                </span> 
                                <span class="text-muted">Entradas</span>
                            </div>
                            <div class="small border-start ps-3">
                                <span class="text-danger fw-bold">
                                    <i class="bi bi-box-arrow-right"></i> {{ $stats['salidas_hoy'] }}
                                </span> 
                                <span class="text-muted">Salidas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100 border-0 border-start border-info border-4">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="small text-muted fw-bold">Usuarios registrados</div>
                            <div class="h3 mb-0">{{ $stats['total_usuarios'] }}</div>
                        </div>
                        <div class="text-info opacity-50">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100 border-0 border-start border-dark border-4">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="small text-muted fw-bold">Hoteles asociados</div>
                            <div class="h3 mb-0">{{ $stats['total_hoteles'] }}</div>
                        </div>
                        <div class="text-dark opacity-50">
                            <i class="bi bi-building fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5 mb-2">Gestión de reservas</h2>
                        <p class="mb-3">
                            Revisa y controla todas las reservas de Isla Transfers.
                        </p>
                        <a href="{{ route('admin.reservas.index') }}" class="btn btn-primary btn-sm">Ver reservas</a>
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
                        <a href="{{ route('admin.configuracion.index') }}" class="btn btn-outline-primary btn-sm">Configurar</a>
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
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-primary btn-sm">Gestionar usuarios</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                        <h3 class="h6 mb-0 text-uppercase fw-bold">
                            <i class="bi bi-cash-stack me-2"></i>Liquidación de Comisiones - {{ now()->translatedFormat('F Y') }}
                        </h3>
                        <span class="h5 mb-0 fw-bold">{{ number_format($totalAPagarGlobal, 2, ',', '.') }}€</span>
                    </div>

                    <div class="card-body p-0">
                        {{-- Acordeón de Hoteles --}}
                        <div class="accordion accordion-flush" id="accordionComisiones">
                            @forelse($hotelesComisiones as $hotel)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $hotel->id_hotel }}">
                                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $hotel->id_hotel }}">
                                            <div class="d-flex justify-content-between w-100 me-3 align-items-center">
                                                <div>
                                                    <span class="fw-bold text-dark">{{ $hotel->nombre }}</span>
                                                    <span class="badge bg-light text-muted border ms-2">{{ $hotel->reservas->count() }} reservas</span>
                                                </div>
                                                <span class="fw-bold text-primary">
                                                    {{ number_format($hotel->reservas->sum('ganancia_hotel'), 2, ',', '.') }}€
                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $hotel->id_hotel }}" class="accordion-collapse collapse" data-bs-parent="#accordionComisiones">
                                        <div class="accordion-body bg-light">
                                            <div class="table-responsive rounded shadow-sm bg-white">
                                                <table class="table table-sm table-hover mb-0 small">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="ps-3">Localizador</th>
                                                            <th>Fecha</th>
                                                            <th>Viajero</th>
                                                            <th class="text-end">Importe</th>
                                                            <th class="text-end pe-3">Comisión ({{ $hotel->comision }}%)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($hotel->reservas as $res)
                                                            <tr>
                                                                <td class="ps-3 fw-bold">#{{ $res->localizador }}</td>
                                                                <td>{{ $res->fecha_entrada->format('d/m/Y') }}</td>
                                                                <td>{{ $res->viajero->nombre ?? 'N/A' }}</td>
                                                                <td class="text-end text-muted">{{ number_format($res->importe, 2, ',', '.') }}€</td>
                                                                <td class="text-end pe-3 fw-bold text-success">+{{ number_format($res->ganancia_hotel, 2, ',', '.') }}€</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="table-light fw-bold">
                                                        <tr>
                                                            <td colspan="4" class="text-end">Total a liquidar:</td>
                                                            <td class="text-end pe-3 text-primary h6 mb-0">
                                                                {{ number_format($hotel->reservas->sum('ganancia_hotel'), 2, ',', '.') }}€
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-5 text-center text-muted">
                                    <i class="bi bi-info-circle fs-2 d-block mb-2"></i>
                                    No hay comisiones acumuladas para este periodo.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
