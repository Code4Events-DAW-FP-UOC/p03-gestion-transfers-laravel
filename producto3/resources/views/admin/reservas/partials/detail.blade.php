{{-- resources/views/admin/reservas/partials/detail.blade.php --}}

@php
    $viajero      = $reserva->viajero;
    $viajeroUser  = $viajero?->user;
    $hotelDestino = $reserva->hotelDestino;
    $vehiculo     = $reserva->vehiculo;
    $tipoReserva  = $reserva->tipoReserva;
    $creador      = $reserva->creador;

    $fechaReserva = $reserva->fecha_reserva
        ? $reserva->fecha_reserva->format('d/m/Y H:i')
        : '—';

    $fechaModificacion = $reserva->fecha_modificacion
        ? $reserva->fecha_modificacion->format('d/m/Y H:i')
        : '—';

    $fechaIda = $reserva->fecha_entrada
        ? $reserva->fecha_entrada->format('d/m/Y')
        : null;

    $horaIda = $reserva->hora_entrada;

    $fechaVuelta = $reserva->fecha_vuelo_salida
        ? $reserva->fecha_vuelo_salida->format('d/m/Y')
        : null;

    $horaVuelta = $reserva->hora_vuelo_salida;

    $estado = $reserva->estado;

    $badgeClass = match ($estado) {
        'pendiente'  => 'bg-warning text-dark',
        'confirmada' => 'bg-primary',
        'realizada'  => 'bg-success',
        'cancelada'  => 'bg-secondary',
        default      => 'bg-light text-dark',
    };

    $creadorRolLabel = match(optional($creador)->rol) {
        'viajero' => __('Viajero'),
        'hotel'   => __('Hotel'),
        'admin'   => __('Administrador'),
        default   => __('Desconocido'),
    };
@endphp

<div class="container-fluid">
    {{-- Estado y fechas --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <h6 class="text-muted mb-1">{{ __('Estado') }}</h6>
            <span class="badge {{ $badgeClass }}">
                {{ ucfirst($estado) }}
            </span>
        </div>
        <div class="col-md-4">
            <h6 class="text-muted mb-1">{{ __('Fecha de reserva') }}</h6>
            <p class="mb-0">{{ $fechaReserva }}</p>
        </div>
        <div class="col-md-4">
            <h6 class="text-muted mb-1">{{ __('Última modificación') }}</h6>
            <p class="mb-0">{{ $fechaModificacion }}</p>
        </div>
    </div>

    <hr>

    {{-- Viajero y creador --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <h6 class="text-muted mb-1">{{ __('Viajero') }}</h6>
            @if($viajero)
                <p class="mb-0 fw-semibold">
                    {{ $viajero->nombre }}
                    {{ $viajero->apellido1 }}
                    {{ $viajero->apellido2 }}
                </p>
                <p class="mb-0 small text-muted">
                    {{ $viajero->email }} · {{ $viajero->telefono ?? '—' }}
                </p>
                <p class="mb-0 small text-muted">
                    {{ $viajero->direccion }},
                    {{ $viajero->codigo_postal }} {{ $viajero->ciudad }},
                    {{ $viajero->pais }}
                </p>
            @else
                <p class="mb-0">—</p>
            @endif
        </div>
        <div class="col-md-6">
            <h6 class="text-muted mb-1">{{ __('Creada por') }}</h6>
            @if($creador)
                <p class="mb-0 fw-semibold">
                    {{ $creador->name }}
                </p>
                <p class="mb-0 small text-muted">
                    {{ $creador->email }} · {{ $creadorRolLabel }}
                </p>
            @else
                <p class="mb-0">—</p>
            @endif
        </div>
    </div>

    <hr>

    {{-- Hotel y vehículo --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <h6 class="text-muted mb-1">{{ __('Hotel destino') }}</h6>
            @if($hotelDestino)
                <p class="mb-0 fw-semibold">{{ $hotelDestino->nombre }}</p>
                <p class="mb-0 small text-muted">
                    {{ $hotelDestino->email }} · {{ $hotelDestino->telefono ?? '—' }}
                </p>
                @if($hotelDestino->zona)
                    <p class="mb-0 small text-muted">
                        {{ __('Zona:') }}
                        {{ $hotelDestino->zona->descripcion }}
                        ({{ $hotelDestino->zona->codigo }})
                    </p>
                @endif
            @else
                <p class="mb-0">—</p>
            @endif
        </div>

        <div class="col-md-6">
            <h6 class="text-muted mb-1">{{ __('Vehículo') }}</h6>
            @if($vehiculo)
                <p class="mb-0 fw-semibold">
                    {{ $vehiculo->descripcion }}
                </p>
                <p class="mb-0 small text-muted">
                    {{ __('Plazas:') }} {{ $vehiculo->plazas }}
                </p>
            @else
                <p class="mb-0">—</p>
            @endif

            <h6 class="text-muted mb-1 mt-3">{{ __('Tipo de reserva') }}</h6>
            <p class="mb-0">
                {{ $tipoReserva->descripcion ?? '—' }}
            </p>
            <p class="mb-0 small text-muted">
                {{ __('Número de viajeros:') }} {{ $reserva->num_viajeros }}
            </p>
        </div>
    </div>

    <hr>

    {{-- Tramo ida y vuelta --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <h6 class="text-muted mb-1">{{ __('Tramo de ida') }}</h6>
            @if($fechaIda || $horaIda || $reserva->numero_vuelo_entrada || $reserva->origen_vuelo_entrada)
                <ul class="list-unstyled mb-0 small">
                    <li><strong>{{ __('Fecha:') }}</strong> {{ $fechaIda ?? '—' }}</li>
                    <li><strong>{{ __('Hora:') }}</strong> {{ $horaIda ?? '—' }}</li>
                    <li><strong>{{ __('Nº vuelo:') }}</strong> {{ $reserva->numero_vuelo_entrada ?? '—' }}</li>
                    <li><strong>{{ __('Origen:') }}</strong> {{ $reserva->origen_vuelo_entrada ?? '—' }}</li>
                </ul>
            @else
                <p class="mb-0 small text-muted">—</p>
            @endif
        </div>

        <div class="col-md-6">
            <h6 class="text-muted mb-1">{{ __('Tramo de vuelta') }}</h6>
            @if($fechaVuelta || $horaVuelta || $reserva->numero_vuelo_salida || $reserva->destino_vuelo_salida)
                <ul class="list-unstyled mb-0 small">
                    <li><strong>{{ __('Fecha:') }}</strong> {{ $fechaVuelta ?? '—' }}</li>
                    <li><strong>{{ __('Hora:') }}</strong> {{ $horaVuelta ?? '—' }}</li>
                    <li><strong>{{ __('Nº vuelo:') }}</strong> {{ $reserva->numero_vuelo_salida ?? '—' }}</li>
                    <li><strong>{{ __('Destino:') }}</strong> {{ $reserva->destino_vuelo_salida ?? '—' }}</li>
                </ul>
            @else
                <p class="mb-0 small text-muted">—</p>
            @endif
        </div>
    </div>

    {{-- Comisión y observaciones (si las estás usando) --}}
    @if($reserva->comision_porcentaje != 0 || $reserva->comision_importe != 0 || $reserva->observaciones)
        <hr>
        <div class="row mb-2">
            <div class="col-md-6">
                <h6 class="text-muted mb-1">{{ __('Comisión del hotel') }}</h6>
                <p class="mb-0 small">
                    <strong>{{ __('Porcentaje:') }}</strong>
                    {{ number_format($reserva->comision_porcentaje, 2) }} %
                </p>
                <p class="mb-0 small">
                    <strong>{{ __('Importe:') }}</strong>
                    {{ number_format($reserva->comision_importe, 2) }} €
                </p>
            </div>
            @if($reserva->observaciones)
                <div class="col-md-6">
                    <h6 class="text-muted mb-1">{{ __('Observaciones') }}</h6>
                    <p class="mb-0 small">
                        {{ $reserva->observaciones }}
                    </p>
                </div>
            @endif
        </div>
    @endif
</div>