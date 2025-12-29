{{-- resources/views/admin/comisiones/index.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Comisiones por hotel y mes') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                {{-- Filtros --}}
                <form method="GET" action="{{ route('admin.comisiones.index') }}" class="row g-3 align-items-end">
                    {{-- Año --}}
                    <div class="col-12 col-sm-3 col-md-2">
                        <label class="form-label form-label-sm">{{ __('Año') }}</label>
                        <select name="year" class="form-select form-select-sm">
                            @foreach($yearsDisponibles as $y)
                                <option value="{{ $y }}" @selected($y == $year)>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Mes --}}
                    <div class="col-12 col-sm-3 col-md-2">
                        <label class="form-label form-label-sm">{{ __('Mes') }}</label>
                        <select name="month" class="form-select form-select-sm">
                            <option value="">{{ __('Todos') }}</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" @selected($month == $m)>
                                    {{ \Carbon\Carbon::createFromDate($year, $m, 1)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Hotel --}}
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label form-label-sm">{{ __('Hotel') }}</label>
                        <select name="hotel_id" class="form-select form-select-sm">
                            <option value="">{{ __('Todos los hoteles') }}</option>
                            @foreach($hotelesFiltro as $hotelItem)
                                <option value="{{ $hotelItem->id_hotel }}" @selected($hotelId == $hotelItem->id_hotel)>
                                    {{ $hotelItem->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-search"></i> {{ __('Filtrar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla resumen --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Hotel') }}</th>
                                <th>{{ __('Año') }}</th>
                                <th>{{ __('Mes') }}</th>
                                <th class="text-end">{{ __('Reservas realizadas') }}</th>
                                <th class="text-end">{{ __('Comisión total (€)') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($resumen as $fila)
                                @php
                                    $hotelNombre = $hoteles[$fila->hotel_id]->nombre ?? __('Desconocido');
                                    $mesNombre = \Carbon\Carbon::createFromDate($fila->year, $fila->month, 1)->translatedFormat('F');
                                @endphp
                                <tr>
                                    <td>{{ $hotelNombre }}</td>
                                    <td>{{ $fila->year }}</td>
                                    <td>{{ ucfirst($mesNombre) }}</td>
                                    <td class="text-end">{{ $fila->total_reservas }}</td>
                                    <td class="text-end">
                                        {{ number_format($fila->total_comision, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        {{ __('No hay datos de comisiones para los filtros seleccionados.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($resumen->isNotEmpty())
                    <div class="mt-3 text-end">
                        <span class="small text-muted">
                            {{ __('Totales filtrados: :reservas reservas · :importe € en comisiones', [
                                'reservas' => $resumen->sum('total_reservas'),
                                'importe'  => number_format($resumen->sum('total_comision'), 2, ',', '.'),
                            ]) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>