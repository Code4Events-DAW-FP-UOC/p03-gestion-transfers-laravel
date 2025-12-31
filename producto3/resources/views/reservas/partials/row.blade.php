<tr>
    <td>{{ $reserva->localizador }}</td>
    @if ($context != 'viajero')
        <td>{{ optional($reserva->viajero)->nombre }}{{ optional($reserva->viajero)->apellido1 }}</td>
    @endif
    <td>{{ optional($reserva->hotelDestino)->nombre ?? '-' }}</td>
    <td>{{ $reserva->tipoReserva->descripcion ?? '-' }}</td>
    <td>{{ optional($reserva->fecha_entrada ?? $reserva->fecha_vuelo_salida)?->format('d/m/Y') ?? '-' }}</td>
    <td><x-reservas.estado-badge :estado="$reserva->estado" /></td>
    @if($showActions)
        <td class="text-end">
            @include('reservas.partials.actions', ['reserva' => $reserva])
        </td>
    @endif
</tr>