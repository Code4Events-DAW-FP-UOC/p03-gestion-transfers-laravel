{{-- <x-buttons.icon-link :href="route('viajero.reservas.show', $reserva)" title="Ver reserva" icon="eye" /> --}}
{{-- Ver detalles en modal --}}
<x-buttons.icon-link href="#" icon="eye" :title="__('Ver detalles')" variant="outline-primary" size="sm"
    data-bs-toggle="modal" :data-bs-target="'#reservaModal-'.$reserva->id_reserva" />

{{-- <x-buttons.icon-link :href="route('viajero.reservas.edit', $reserva)" title="Editar" icon="pencil-square"
    :disabled="in_array($reserva->estado, ['cancelada', 'realizada'])" /> --}}
{{-- Editar --}}
{{-- <x-buttons.icon-link :href="route($context . '.reservas.edit', $reserva)" icon="pencil-square" :title="__('Editar reserva')" variant="outline-secondary" size="sm" :disabled="in_array($reserva->estado, ['cancelada', 'realizada'])" /> --}}


{{-- botón que abre modal de cancelar --}}
{{-- <button type="button"
    class="btn btn-sm btn-outline-danger {{ in_array($reserva->estado, ['cancelada', 'realizada']) ? 'disabled' : '' }}"
    data-bs-toggle="modal" data-bs-target="#modal-cancel-{{ $reserva->id_reserva }}" @if(in_array($reserva->estado,
    ['cancelada', 'realizada'])) aria-disabled="true" @endif>
    <i class="bi bi-trash"></i>
</button> --}}

{{-- Cancelar (modal propio o form) --}}
{{-- @if(!in_array($reserva->estado, ['cancelada', 'realizada']))
    <form method="POST" action="{{ route($context . '.reservas.destroy', $reserva) }}" class="d-inline">
        @csrf
        @method('DELETE')
        <x-buttons.icon-link href="#" icon="trash" :title="__('Cancelar reserva')" variant="outline-danger" size="sm"
            data-bs-toggle="modal" :data-bs-target="'#cancelReservaModal-'.$reserva->id_reserva" />
    </form>
@endif --}}