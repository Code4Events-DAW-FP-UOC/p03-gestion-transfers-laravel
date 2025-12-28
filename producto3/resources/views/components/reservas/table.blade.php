@props([
    'reservas',
    'context' => 'admin',
    'showActions' => true,
])

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>{{ __('Localizador') }}</th>
                @if ($context !== 'viajero')
                    <th>{{ __('Viajero') }}</th>
                @endif
                <th>{{ __('Hotel destino') }}</th>
                <th>{{ __('Tipo') }}</th>
                <th>{{ __('Fecha servicio') }}</th>
                <th>{{ __('Estado') }}</th>
                @if($showActions)
                    <th class="text-end">{{ __('Acciones') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($reservas as $reserva)
                @include('reservas.partials.row', [
                    'reserva' => $reserva,
                    'context' => $context,
                ])
                @include('reservas.partials.modal', [
                    'reserva' => $reserva,
                    'context' => $context,
                ])
                
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">{{ __('No ha reservas para mostrar.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>