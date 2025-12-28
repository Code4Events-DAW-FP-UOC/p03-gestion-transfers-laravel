@props(['reserva'])

@if ($reserva->creador)
    <span class="badge bg-light text-muted border">{{ $reserva->creador_label }}</span>
@else
    <span class="text-muted">-</span>
@endif