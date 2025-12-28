@props(['estado'])

@php
    $estado = strtolower($estado ?? '');

    $map = [
        'pendiente' => ['text' => 'Pendiente', 'class' => 'badge bg-warning text-dark'],
        'confirmada' => ['text' => 'Confirmada', 'class' => 'badge bg-info text-dark'],
        'realizada' => ['text' => 'Realizada', 'class' => 'badge bg-success'],
        'cancelada' => ['text' => 'Cancelada', 'class' => 'badge bg-secondary'],
    ];

    $config = $map[$estado] ?? ['text' => ucfirst($estado ?: '-'), 'class' => 'badge bg-light text-muted'];
@endphp

<span class="{{ $config['class'] }}">
    {{ $config['text'] }}
</span>