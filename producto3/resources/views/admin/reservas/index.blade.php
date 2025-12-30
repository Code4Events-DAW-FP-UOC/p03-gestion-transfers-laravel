<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Listado Global de Reservas</h2>
        </div>
    </x-slot>
    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ route('admin.reservas.create') }}" class="btn btn-primary">Nueva reserva</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success mt-3">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Localizador</th>
                            <th>Viajero</th>
                            <th>Tipo</th>
                            <th>Hotel</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Coste</th>
                            <th>Estado</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $reserva)
                            @php
                                $fechaEntrada = $reserva->fecha_entrada 
                                    ? \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') 
                                    : '-';

                                $campoSalida = $reserva->fecha_vuelo_salida ?? $reserva->fecha_salida;

                                $fechaSalida = $campoSalida 
                                    ? \Carbon\Carbon::parse($campoSalida)->format('d/m/Y') 
                                    : '-';
                                    $reservaBloqueada = in_array($reserva->estado, ['cancelada', 'realizada'], true);
                                @endphp
                            <tr>
                                <td><span class="fw-bold">{{ $reserva->localizador }}</span></td>
                                <td>
                                    <div class="small">
                                        <div class="fw-bold">{{ $reserva->viajero->user->name ?? 'N/A' }}</div>
                                        <div class="text-muted">{{ $reserva->viajero->user->email ?? '' }}</div>
                                    </div>
                                </td>
                                <td>{{ $reserva->tipoReserva->descripcion ?? '-' }}</td>
                                <td>{{ $reserva->hotelDestino->nombre ?? '-' }}</td>
                                <td>{{ $fechaEntrada }}</td>
                                <td>{{ $fechaSalida }}</td>
                                <td>
                                    {{ $reserva->total ? number_format($reserva->total, 2, ',', '.') . ' €' : '-' }}
                                </td>
                                <td>
                                    <span>
                                        {{ ucfirst($reserva->estado) }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    {{-- BOTÓN VER --}}
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="modal" data-bs-target="#modal-detalle-{{ $reserva->id_reserva }}"
                                        title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if ($reservaBloqueada)
                                        {{-- EDITAR DESHABILITADO --}}
                                        <button type="button" class="btn btn-sm btn-outline-primary ms-1"
                                            disabled title="No editable en este estado">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        {{-- CANCELAR DESHABILITADO --}}
                                        <button type="button" class="btn btn-sm btn-outline-secondary ms-1"
                                            disabled title="No se puede cancelar">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @else
                                        {{-- EDITAR ACTIVO --}}
                                        <a href="{{ route('admin.reservas.edit', $reserva) }}"
                                            class="btn btn-sm btn-outline-primary ms-1" title="Editar reserva">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        {{-- CANCELAR ACTIVO --}}
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-1"
                                            data-bs-toggle="modal" data-bs-target="#modal-cancelar-{{ $reserva->id_reserva }}"
                                            title="Cancelar reserva">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">No hay reservas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($reservas instanceof \Illuminate\Pagination\AbstractPaginator && $reservas->hasPages())
            <div class="card-footer bg-white">
                {{ $reservas->links() }}
            </div>
        @endif
    </div>

{{-- Modales de detalle de cada reserva y cancelar --}}
@foreach ($reservas as $reserva)
    <x-modal name="modal-detalle-{{ $reserva->id_reserva }}">
        <div class="modal-header">
            <h5 class="modal-title">
                Reserva {{ $reserva->localizador ?? '—' }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
            {{-- Bloque resumen principal --}}
            <div class="mb-3">
                <span class="badge bg-primary">
                    {{ $reserva->tipoReserva->descripcion ?? 'Tipo no definido' }}
                </span>

                @if($reserva->estado)
                    <span
                        class="badge @if($reserva->estado === 'confirmada') bg-success @elseif($reserva->estado === 'cancelada') bg-danger @else bg-secondary @endif">{{ ucfirst($reserva->estado) }}</span>
                @endif
            </div>

            {{-- Datos en forma de definición --}}
            <dl class="row mb-0">
                <dt class="col-sm-4">Localizador</dt>
                <dd class="col-sm-8">{{ $reserva->localizador ?? '—' }}</dd>

                <dt class="col-sm-4">Creada por</dt>
                <dd class="col-sm-8">
                    @if($reserva->creador)
                        {{ $reserva->creador->name }}
                        <span class="text-muted">({{ $reserva->creador->rol }})</span>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-4 mt-3">Hotel de destino</dt>
                <dd class="col-sm-8 mt-3">
                    {{ $reserva->hotelDestino->nombre ?? $reserva->hotelGestor->nombre ?? '—' }}
                </dd>

                <dt class="col-sm-4">Fecha reserva</dt>
                <dd class="col-sm-8">
                    {{ optional($reserva->fecha_reserva)->format('d/m/Y H:i') ?? '—' }}
                </dd>

                {{-- BLOQUE IDA (si tiene datos de ida) --}}
                @if($reserva->fecha_entrada || $reserva->hora_entrada)
                    <dt class="col-sm-12 mt-3 mb-3">
                        <strong>Tramo de ida (aeropuerto → hotel)</strong>
                    </dt>

                    <dt class="col-sm-4">Fecha llegada</dt>
                    <dd class="col-sm-8">
                        {{ optional($reserva->fecha_entrada)->format('d/m/Y') ?? '—' }}
                        @if($reserva->hora_entrada)
                            a las {{ \Carbon\Carbon::parse($reserva->hora_entrada)->format('H:i') }}
                        @endif
                    </dd>

                    <dt class="col-sm-4">Vuelo llegada</dt>
                    <dd class="col-sm-8">
                        {{ $reserva->numero_vuelo_entrada ?? '—' }}
                        @if($reserva->origen_vuelo_entrada)
                            <span class="text-muted"> (origen: {{ $reserva->origen_vuelo_entrada }})</span>
                        @endif
                    </dd>
                @endif

                {{-- BLOQUE VUELTA (si tiene datos de vuelta) --}}
                @if($reserva->fecha_vuelo_salida || $reserva->hora_vuelo_salida)
                    <dt class="col-sm-12 mt-3 mb-3">
                        <strong>Tramo de vuelta (hotel → aeropuerto)</strong>
                    </dt>

                    <dt class="col-sm-4">Fecha salida</dt>
                    <dd class="col-sm-8">
                        {{ optional($reserva->fecha_vuelo_salida)->format('d/m/Y') ?? '—' }}
                        @if($reserva->hora_vuelo_salida)
                            a las {{ \Carbon\Carbon::parse($reserva->hora_vuelo_salida)->format('H:i') }}
                        @endif
                    </dd>

                    <dt class="col-sm-4">Vuelo salida</dt>
                    <dd class="col-sm-8">
                        {{ $reserva->numero_vuelo_salida ?? '—' }}
                        @if($reserva->destino_vuelo_salida)
                            <span class="text-muted"> (destino: {{ $reserva->destino_vuelo_salida }})</span>
                        @endif
                    </dd>
                @endif

                {{-- BLOQUE COMÚN --}}
                <dt class="col-sm-5 mt-3">Número de viajeros</dt>
                <dd class="col-sm-7 mt-3">{{ $reserva->num_viajeros }}</dd>

                <dt class="col-sm-5">Vehículo</dt>
                <dd class="col-sm-7">
                    @if($reserva->vehiculo)
                        {{ $reserva->vehiculo->descripcion }}
                        <span class="text-muted">
                            ({{ $reserva->vehiculo->plazas }} plazas)
                        </span>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-4 mt-3">Importe Base</dt>
                <dd class="col-sm-8 mt-3">
                    {{ $reserva->importe ? number_format($reserva->importe, 2, ',', '.') . ' €' : '—' }}
                </dd>
                
                <dt class="col-sm-4">Comisión Hotel</dt>
                <dd class="col-sm-8">
                    {{ $reserva->hotelDestino->comision ?? 0 }}%
                </dd>
                
                <dt class="col-sm-4 fw-bold text-primary">Total Reserva</dt>
                <dd class="col-sm-8 fw-bold text-primary">
                    {{ $reserva->total ? number_format($reserva->total, 2, ',', '.') . ' €' : '—' }}
                </dd>
            </dl>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Cerrar
            </button>
            {{-- Aquí podrías añadir un botón "Editar" o "Cancelar reserva" si procede --}}
        </div>
    </x-modal>

    {{-- Modal de confirmación de cancelación (SIN contraseña) --}}
    <x-modal name="modal-cancelar-{{ $reserva->id_reserva }}" size="modal-lg">
        <form method="post" action="{{ route('admin.reservas.destroy', $reserva) }}">
            @csrf
            @method('delete')

            <div class="modal-header">
                <h5 class="modal-title">
                    {{ __('Confirmar cancelación de la reserva') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Cerrar') }}"></button>
            </div>

            <div class="modal-body">
                <p class="mb-3">
                    Vas a cancelar la reserva
                    <strong>{{ $reserva->localizador }}</strong>
                    del hotel
                    <strong>{{ $reserva->hotelDestino->nombre ?? '-' }}</strong>.
                </p>

                <p class="small text-muted mb-0">
                    Esta acción no eliminará la reserva del historial, pero
                    su estado pasará a <strong>“cancelada”</strong> y ya no
                    se prestará el servicio.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{ __('Volver') }}
                </button>

                <x-danger-button>
                    {{ __('Confirmar cancelación') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
@endforeach
</x-app-layout>