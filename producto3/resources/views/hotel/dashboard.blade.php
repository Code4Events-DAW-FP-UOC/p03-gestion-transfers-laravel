{{-- resources/views/hotel/dashboard.blade.php --}}
@php
    use Carbon\Carbon;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Panel de hotel</h1>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">

            {{-- Fila 1: métricas generales de reservas --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6 col-xl-2">
                    <x-ui.metric-card
                        :title="__('Reservas totales')"
                        :value="$totalReservas"
                        :subtitle="__('Todas las reservas asociadas a tu hotel')"
                        color="dark"
                    />
                </div>

                <div class="col-12 col-md-6 col-xl-2">
                    <x-ui.metric-card
                        :title="__('Realizadas')"
                        :value="$realizadas"
                        :subtitle="__('Servicios completados')"
                        color="success"
                    />
                </div>

                <div class="col-12 col-md-6 col-xl-2">
                    <x-ui.metric-card
                        :title="__('Confirmadas')"
                        :value="$confirmadas"
                        :subtitle="__('Reservas confirmadas')"
                        color="primary"
                    />
                </div>

                <div class="col-12 col-md-6 col-xl-2">
                    <x-ui.metric-card
                        :title="__('Pendientes')"
                        :value="$pendientes"
                        :subtitle="__('Reservas por gestionar')"
                        color="warning"
                    />
                </div>

                <div class="col-12 col-md-6 col-xl-2">
                    <x-ui.metric-card
                        :title="__('Canceladas')"
                        :value="$canceladas"
                        :subtitle="__('No generan comisión')"
                        color="secondary"
                    />
                </div>
            </div>

            {{-- Fila 2: resumen mensual de comisiones --}}
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="h5 mb-3">
                                {{ __('Comisiones por mes') }}
                            </h2>
                            <p class="text-muted small">
                                {{ __('Se muestran las comisiones de reservas realizadas, confirmadas y pendientes. Las canceladas no se contabilizan.') }}
                            </p>

                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Mes') }}</th>
                                            <th class="text-end">{{ __('Comisión realizadas') }}</th>
                                            <th class="text-end">{{ __('Comisión confirmadas') }}</th>
                                            <th class="text-end">{{ __('Comisión pendientes') }}</th>
                                            <th class="text-end">{{ __('Comisión total') }}</th>
                                            <th class="text-end">{{ __('Nº reservas') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($comisionesMensuales as $fila)
                                            @php
                                                $fechaMes = Carbon::createFromDate($fila->year, $fila->month, 1);
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{ $fechaMes->translatedFormat('F Y') }}
                                                </td>
                                                <td class="text-end text-success">
                                                    {{ number_format($fila->comision_realizada ?? 0, 2, ',', '.') }} €
                                                </td>
                                                <td class="text-end text-primary">
                                                    {{ number_format($fila->comision_confirmada ?? 0, 2, ',', '.') }} €
                                                </td>
                                                <td class="text-end text-warning">
                                                    {{ number_format($fila->comision_pendiente ?? 0, 2, ',', '.') }} €
                                                </td>
                                                <td class="text-end fw-semibold">
                                                    {{ number_format($fila->comision_total ?? 0, 2, ',', '.') }} €
                                                </td>
                                                <td class="text-end">
                                                    {{ $fila->total_reservas }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    {{ __('Todavía no hay reservas con comisión para mostrar.') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Fila 3: bloques informativos --}}
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="h5 mb-2">Reservas de mis clientes</h2>
                            <p class="mb-3">
                                Crea y consulta reservas de transfer asociadas a tu hotel.
                            </p>
                            <a href="{{ route('hotel.reservas.index') }}" class="btn btn-primary btn-sm">
                                {{ __('Ver reservas') }}
                            </a>
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
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                {{ __('Ver datos') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Próximas reservas --}}
            @if(isset($proximasReservas) && $proximasReservas->count())
                <div class="row g-3 mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h2 class="h5 mb-3">{{ __('Próximas reservas') }}</h2>

                                <div class="table-responsive">
                                    <table class="table table-sm align-middle">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Fecha servicio') }}</th>
                                                <th>{{ __('Viajero') }}</th>
                                                <th>{{ __('Tipo') }}</th>
                                                <th>{{ __('Vehículo') }}</th>
                                                <th>{{ __('Estado') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($proximasReservas as $reserva)
                                                @php
                                                    $fechaServicio = $reserva->fecha_entrada
                                                        ?? $reserva->fecha_vuelo_salida;

                                                    $estado = $reserva->estado;

                                                    $badgeClass = match ($estado) {
                                                        'pendiente'  => 'bg-warning text-dark',
                                                        'confirmada' => 'bg-primary',
                                                        'realizada'  => 'bg-success',
                                                        'cancelada'  => 'bg-secondary',
                                                        default      => 'bg-light text-dark',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td>{{ optional($fechaServicio)->format('d/m/Y') }}</td>
                                                    <td>
                                                        {{ optional($reserva->viajero)->nombre }}
                                                        {{ optional($reserva->viajero)->apellido1 }}
                                                    </td>
                                                    <td>{{ optional($reserva->tipoReserva)->descripcion }}</td>
                                                    <td>{{ optional($reserva->vehiculo)->descripcion }}</td>
                                                    <td>
                                                        <span class="badge {{ $badgeClass }}">
                                                            {{ ucfirst($estado) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <a href="{{ route('hotel.reservas.index') }}" class="btn btn-sm btn-outline-primary">
                                    {{ __('Ver todas las reservas') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>