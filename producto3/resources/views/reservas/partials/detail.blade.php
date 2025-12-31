{{-- resources/views/reservas/partials/detail.blade.php --}}
<div class="row mb-3">
    <div class="col-sm-4 fw-semibold">Localizador</div>
    <div class="col-sm-8">{{ $reserva->localizador }}</div>
</div>

<div class="row mb-3">
    <div class="col-sm-4 fw-semibold">Viajero</div>
    <div class="col-sm-8">
        {{ optional($reserva->viajero)->nombre }}
        {{ optional($reserva->viajero)->apellido1 }}
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-4 fw-semibold">Hotel destino</div>
    <div class="col-sm-8">
        {{ optional($reserva->hotelDestino)->nombre ?? '—' }}
    </div>
</div>

{{-- … resto de campos comunes: tipo, fechas, vuelo, estado, importe, etc. --}}

{{-- Ejemplo: mostrar quién creó la reserva solo para admin --}}
@isset($context)
    @if($context === 'admin')
        <div class="row mb-3">
            <div class="col-sm-4 fw-semibold">Creada por</div>
            <div class="col-sm-8">
                {{ $reserva->creador?->name }}
                <small class="text-muted">({{ $reserva->creador_label }})</small>
            </div>
        </div>
    @endif
@endisset