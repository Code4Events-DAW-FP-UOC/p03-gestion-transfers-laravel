<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Panel de Reservas - {{ Auth::user()->hotel->nombre }}</h2>
    </x-slot>

    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ route('hotel.reservas.create') }}" class="btn btn-primary">Crear nueva reserva</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Localizador</th>
                        <th>Tipo</th>
                        <th>Viajero</th>
                        <th>Fecha entrada</th>
                        <th>Fecha salida</th>
                        <th>Comisión</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservas as $reserva)
                        @php
                            $fechaEntrada = $reserva->fecha_entrada ? \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') : '-';
                            $fechaSalida = $reserva->fecha_vuelo_salida ? \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d/m/Y') : '-';
                            $estaBloqueada = in_array($reserva->estado, ['cancelada', 'realizada']);
                        @endphp
                        <tr>
                            <td><strong>{{ $reserva->localizador }}</strong></td>
                            <td>{{ $reserva->tipoReserva->descripcion ?? '-' }}</td>
                            {{-- Mostramos el mail del viajero como solicitaste --}}
                            <td>{{ $reserva->viajero->user->email ?? 'N/A' }}</td>
                            <td>{{ $fechaEntrada }}</td>
                            <td>{{ $fechaSalida }}</td>
                            {{-- Mostramos la ganancia por comisión --}}
                            <td class="text-success fw-bold">
                                {{ number_format($reserva->ganancia_hotel, 2, ',', '.') }} €
                            </td>
                            <td>           
                                {{ ucfirst($reserva->estado) }}               
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    data-bs-toggle="modal" data-bs-target="#modal-detalle-{{ $reserva->id_reserva }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                @if($estaBloqueada)
                                    <button class="btn btn-sm btn-outline-secondary ms-1" disabled 
                                        title="No se puede editar una reserva {{ $reserva->estado }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                @else
                                    <a href="{{ route('hotel.reservas.edit', $reserva) }}" 
                                        class="btn btn-sm btn-outline-primary ms-1" title="Editar reserva">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @endif
                                @if($estaBloqueada)
                                    <button class="btn btn-sm btn-outline-secondary ms-1" disabled 
                                        title="Reserva ya {{ $reserva->estado }}">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                @else
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
                            <td colspan="8" class="text-center py-4">No hay reservas registradas para este hotel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($reservas instanceof \Illuminate\Pagination\AbstractPaginator)
                <div class="card-footer">{{ $reservas->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

{{-- Modales de Detalle (Adaptado para el Hotel) --}}
@foreach ($reservas as $reserva)
    <x-modal name="modal-detalle-{{ $reserva->id_reserva }}">
        <div class="modal-header">
            <h5 class="modal-title">Detalles Reserva: {{ $reserva->localizador }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <dl class="row">
                <dt class="col-sm-5">Viajero:</dt>
                <dd class="col-sm-7">{{ $reserva->viajero->user->name }} ({{ $reserva->viajero->user->email }})</dd>

                <dt class="col-sm-5">Vehículo:</dt>
                <dd class="col-sm-7">{{ $reserva->vehiculo->descripcion ?? '—' }}</dd>

                <hr>

                <dt class="col-sm-5">Importe Cliente:</dt>
                <dd class="col-sm-7">{{ number_format($reserva->total, 2, ',', '.') }} €</dd>

                <dt class="col-sm-5 text-success">Tu Comisión ({{ $reserva->hotelDestino->comision ?? 0 }}%):</dt>
                <dd class="col-sm-7 text-success fw-bold">
                    {{ number_format($reserva->ganancia_hotel, 2, ',', '.') }} €
                </dd>

                <dt class="col-sm-5 text-muted">Neto para Transfer:</dt>
                <dd class="col-sm-7 text-muted">{{ number_format($reserva->importe, 2, ',', '.') }} €</dd>
            </dl>
        </div>
    </x-modal>

    {{-- Modal Cancelar (Igual al anterior) --}}
    <x-modal name="modal-cancelar-{{ $reserva->id_reserva }}">
        <form method="post" action="{{ route('hotel.reservas.destroy', $reserva) }}">
            @csrf
            @method('delete')
            <div class="modal-body">
                <p>¿Estás seguro de que deseas cancelar la reserva de <strong>{{ $reserva->viajero->user->name }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
            </div>
        </form>
    </x-modal>
@endforeach