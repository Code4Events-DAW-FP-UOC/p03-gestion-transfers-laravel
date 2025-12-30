<x-app-layout>
    {{-- Título de la página en la cabecera del Layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Información y Comisiones del Hotel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Contenedor de Bootstrap para los datos --}}
            <div class="container-fluid">
                <div class="row">
                    
                    {{-- COLUMNA IZQUIERDA: INFORMACIÓN BÁSICA --}}
                    <div class="col-xl-4 col-lg-5 mb-4">
                        <div class="card shadow border-0">
                            <div class="card-header py-3 bg-white">
                                <h6 class="m-0 font-weight-bold text-primary text-uppercase small">
                                    <i class="fas fa-hotel me-2"></i>{{ __('Ficha Técnica') }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $hotel->nombre }}</div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase">
                                        {{ $hotel->zona->descripcion ?? 'Sin zona asignada' }}
                                    </div>
                                </div>
                                
                                <div class="list-group list-group-flush small">
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                        <span class="text-muted">Email:</span>
                                        <span class="font-weight-bold">{{ $hotel->email }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                        <span class="text-muted">Teléfono:</span>
                                        <span class="font-weight-bold">{{ $hotel->telefono }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                        <span class="text-muted">Estado:</span>
                                        @if(auth()->user()->activo)
                                            <span class="badge badge-success px-3" style="border-radius: 20px;">Activo</span>
                                        @else
                                            <span class="badge badge-danger px-3" style="border-radius: 20px;">Inactivo</span>
                                        @endif
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom-0">
                                        <span class="text-muted">Comisión Acordada:</span>
                                        <span class="h6 mb-0 font-weight-bold text-primary">{{ number_format($hotel->comision, 0) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- COLUMNA DERECHA: LISTADO DE RESERVAS Y GANANCIAS --}}
                    <div class="col-xl-8 col-lg-7 mb-4">
                        <div class="card shadow border-0">
                            {{-- Header de la tabla con el Total Mensual --}}
                            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
                                <h6 class="m-0 font-weight-bold small text-uppercase">
                                    {{ __('Comisiones') }}: {{ now()->translatedFormat('F Y') }}
                                </h6>
                                <div class="h5 mb-0 font-weight-bold">
                                    {{ number_format($totalGanadoMes, 2, ',', '.') }}€
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light text-muted small text-uppercase">
                                            <tr>
                                                <th class="ps-4 border-0">Reserva</th>
                                                <th class="border-0">Viajero</th>
                                                <th class="border-0 text-end">Importe Base</th>
                                                <th class="border-0 text-end pe-4">Ganancia</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            @forelse($reservasDelMes as $reserva)
                                                <tr>
                                                    <td class="ps-4">
                                                        <span class="text-dark fw-bold">#{{ $reserva->localizador }}</span>
                                                        <div class="text-muted" style="font-size: 0.7rem;">
                                                            {{ $reserva->fecha_entrada->format('d/m/Y') }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $reserva->viajero->email ?? 'N/A' }}</td>
                                                    <td class="text-end text-muted">
                                                        {{ number_format($reserva->importe, 2, ',', '.') }}€
                                                    </td>
                                                    <td class="text-end pe-4 font-weight-bold text-success">
                                                        +{{ number_format($reserva->ganancia_hotel, 2, ',', '.') }}€
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-5 text-muted">
                                                        {{ __('No hay reservas registradas en este mes.') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            {{-- Resumen final en el pie de la tarjeta --}}
                            <div class="card-footer bg-white text-end py-3 border-top">
                                <span class="text-muted small me-2">{{ __('Total acumulado este mes') }}:</span>
                                <span class="h5 mb-0 font-weight-bold text-dark">
                                    {{ number_format($totalGanadoMes, 2, ',', '.') }}€
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>