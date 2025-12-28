@props([
    'reserva',
    'modalId',
    'formAction', // route(...)
])

<x-modal :id="$modalId">
    <x-slot name="title">
        {{ __('Cancelar reserva :loc', ['loc' => $reserva->localizador]) }}
    </x-slot>

    <x-slot name="body">
        <p>{{ __('¿Seguro que quieres cancelar esta reserva? Esta acción no se puede deshacer.') }}</p>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            {{ __('Cerrar') }}
        </button>

        <form action="{{ $formAction }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                {{ __('Confirmar cancelación') }}
            </button>
        </form>
    </x-slot>
</x-modal>