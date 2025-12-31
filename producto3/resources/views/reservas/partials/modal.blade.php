{{-- resources/views/reservas/partials/modal.blade.php --}}
<div class="modal fade" id="reservaModal-{{ $reserva->id_reserva }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{ __('Reserva :loc', ['loc' => $reserva->localizador]) }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="{{ __('Cerrar') }}"></button>
            </div>
            <div class="modal-body">
                {{-- detalle de la reserva --}}
                @include('reservas.partials.detail', ['reserva' => $reserva])
            </div>
        </div>
    </div>
</div>