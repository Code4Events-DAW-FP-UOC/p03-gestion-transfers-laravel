@props([
    'reserva',
    'modalId', // p.ej. "modal-reserva-{{$reserva->id_reserva }}"
])

<x-modal :id="$modalId">
    <x-slot name="title">
        {{ __('Detalle de reserva :loc', ['loc' => $reserva->localizador]) }}
    </x-slot>
    <x-slot name="body">
        {{-- Detalle bien maquetado: tipo, hotel, fechas, vuelo, estado, etc. --}}
        @iclude('reserva.partials.detail', ['reserva' => $reserva])
    </x-slot>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dimiss="modal">{{ __('Cerrar') }}</button>
    </x-slot>
</x-modal>