{{-- resources/views/admin/reservas/_form.blade.php --}}
@php
    /** @var \App\Models\Reserva|null $reserva */

    $isEdit = $reserva && $reserva->exists;

    $oldTipo = old('id_tipo_reserva', $reserva->id_tipo_reserva ?? 1);
    $oldEstado = old('estado', $reserva->estado ?? 'pendiente');
@endphp

<div class="row g-3">
    {{-- Viajero --}}
    <div class="col-12 col-md-6">
        <label for="id_viajero" class="form-label">{{ __('Viajero') }}</label>
        <div class="d-flex gap-2">
            <select name="id_viajero" id="id_viajero" class="form-select" required>
                <option value="">{{ __('Selecciona un viajero...') }}</option>
                @foreach($viajeros as $viajero)
                    @php
                        $label = trim($viajero->nombre . ' ' . $viajero->apellido1 . ' ' . $viajero->apellido2);
                        $userEmail = optional($viajero->user)->email;
                        if ($userEmail) {
                            $label .= ' (' . $userEmail . ')';
                        }
                    @endphp
                    <option value="{{ $viajero->id_viajero }}"
                        @selected(old('id_viajero', $reserva->id_viajero ?? null) == $viajero->id_viajero)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            {{-- Enlace para crear nuevo viajero en otra pestaña --}}
            <a href="{{ route('admin.users.create', ['rol' => 'viajero']) }}"
               class="btn btn-outline-secondary"
               target="_blank">
                <i class="bi bi-person-plus"></i>
            </a>
        </div>
    </div>

    {{-- Hotel destino --}}
    <div class="col-12 col-md-6">
        <label for="id_hotel" class="form-label">{{ __('Hotel destino') }}</label>
        <select name="id_hotel" id="id_hotel" class="form-select" required>
            <option value="">{{ __('Selecciona un hotel...') }}</option>
            @foreach($hoteles as $hotel)
                <option value="{{ $hotel->id_hotel }}"
                    @selected(old('id_hotel', $reserva->id_hotel_destino ?? null) == $hotel->id_hotel)>
                    {{ $hotel->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Vehículo --}}
    <div class="col-12 col-md-4">
        <label for="id_vehiculo" class="form-label">{{ __('Vehículo') }}</label>
        <select name="id_vehiculo" id="id_vehiculo" class="form-select" required>
            <option value="">{{ __('Selecciona un vehículo...') }}</option>
            @foreach($vehiculos as $vehiculo)
                <option value="{{ $vehiculo->id_vehiculo }}"
                    @selected(old('id_vehiculo', $reserva->id_vehiculo ?? null) == $vehiculo->id_vehiculo)>
                    {{ $vehiculo->descripcion }} ({{ $vehiculo->plazas }} pax)
                </option>
            @endforeach
        </select>
        <div class="form-text">
            {{ __('Capacidad máxima aproximada: :plazas plazas', ['plazas' => $maxPlazas]) }}
        </div>
    </div>

    {{-- Tipo de reserva --}}
    <div class="col-6 col-md-4">
        <label for="id_tipo_reserva" class="form-label">{{ __('Tipo de reserva') }}</label>
        <select name="id_tipo_reserva" id="id_tipo_reserva" class="form-select" required>
            @foreach($tiposReserva as $tipo)
                <option value="{{ $tipo->id_tipo_reserva }}"
                    @selected($oldTipo == $tipo->id_tipo_reserva)>
                    {{ $tipo->descripcion }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Num viajeros --}}
    <div class="col-6 col-md-4">
        <label for="num_viajeros" class="form-label">{{ __('Número de viajeros') }}</label>
        <input type="number"
               name="num_viajeros"
               id="num_viajeros"
               min="1"
               max="{{ $maxPlazas }}"
               class="form-control"
               value="{{ old('num_viajeros', $reserva->num_viajeros ?? 1) }}"
               required>
    </div>

    {{-- Estado --}}
    <div class="col-12 col-md-3">
        <label for="estado" class="form-label">{{ __('Estado') }}</label>
        <select name="estado" id="estado" class="form-select">
            @foreach(['pendiente','confirmada'] as $estado)
                <option value="{{ $estado }}" @selected($oldEstado === $estado)>
                    {{ ucfirst($estado) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<hr class="my-4">

{{-- Sección Ida --}}
<div class="row g-3 mb-4" id="tramo-ida">
    <div class="col-12">
        <h5 class="mb-0">{{ __('Datos de ida') }}</h5>
        <small class="text-muted">{{ __('Rellena estos campos para reservas con ida o ida y vuelta.') }}</small>
    </div>

    <div class="col-12 col-md-3">
        <label for="fecha_entrada" class="form-label">{{ __('Fecha de llegada') }}</label>
        <input type="date"
               name="fecha_entrada"
               id="fecha_entrada"
               class="form-control"
               value="{{ old('fecha_entrada', optional($reserva->fecha_entrada)->format('Y-m-d')) }}">
    </div>

    <div class="col-12 col-md-3">
        <label for="hora_entrada" class="form-label">{{ __('Hora de llegada') }}</label>
        <input type="time"
               name="hora_entrada"
               id="hora_entrada"
               class="form-control"
               value="{{ old('hora_entrada', $reserva->hora_entrada ?? '') }}">
    </div>

    <div class="col-12 col-md-3">
        <label for="numero_vuelo_entrada" class="form-label">{{ __('Nº vuelo llegada') }}</label>
        <input type="text"
               name="numero_vuelo_entrada"
               id="numero_vuelo_entrada"
               class="form-control"
               value="{{ old('numero_vuelo_entrada', $reserva->numero_vuelo_entrada ?? '') }}">
    </div>

    <div class="col-12 col-md-3">
        <label for="origen_vuelo_entrada" class="form-label">{{ __('Origen vuelo llegada') }}</label>
        <input type="text"
               name="origen_vuelo_entrada"
               id="origen_vuelo_entrada"
               class="form-control"
               value="{{ old('origen_vuelo_entrada', $reserva->origen_vuelo_entrada ?? '') }}">
    </div>
</div>

{{-- Sección Vuelta --}}
<div class="row g-3" id="tramo-vuelta">
    <div class="col-12">
        <h5 class="mb-0">{{ __('Datos de vuelta') }}</h5>
        <small class="text-muted">{{ __('Rellena estos campos para reservas con vuelta o ida y vuelta.') }}</small>
    </div>

    <div class="col-12 col-md-3">
        <label for="fecha_vuelo_salida" class="form-label">{{ __('Fecha de salida') }}</label>
        <input type="date"
               name="fecha_vuelo_salida"
               id="fecha_vuelo_salida"
               class="form-control"
               value="{{ old('fecha_vuelo_salida', optional($reserva->fecha_vuelo_salida)->format('Y-m-d')) }}">
    </div>

    <div class="col-12 col-md-3">
        <label for="hora_vuelo_salida" class="form-label">{{ __('Hora de salida') }}</label>
        <input type="time"
               name="hora_vuelo_salida"
               id="hora_vuelo_salida"
               class="form-control"
               value="{{ old('hora_vuelo_salida', $reserva->hora_vuelo_salida ?? '') }}">
    </div>

    <div class="col-12 col-md-3">
        <label for="numero_vuelo_salida" class="form-label">{{ __('Nº vuelo salida') }}</label>
        <input type="text"
               name="numero_vuelo_salida"
               id="numero_vuelo_salida"
               class="form-control"
               value="{{ old('numero_vuelo_salida', $reserva->numero_vuelo_salida ?? '') }}">
    </div>

    <div class="col-12 col-md-3">
        <label for="destino_vuelo_salida" class="form-label">{{ __('Destino vuelo salida') }}</label>
        <input type="text"
               name="destino_vuelo_salida"
               id="destino_vuelo_salida"
               class="form-control"
               value="{{ old('destino_vuelo_salida', $reserva->destino_vuelo_salida ?? '') }}">
    </div>
</div>