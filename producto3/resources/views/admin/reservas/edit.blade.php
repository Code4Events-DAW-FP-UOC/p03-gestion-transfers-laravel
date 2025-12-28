{{-- resources/views/admin/reservas/edit.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Editar reserva :loc', ['loc' => $reserva->localizador]) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                @include('layouts.partials.flash-messages')

                <form action="{{ route('admin.reservas.update', $reserva) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('admin.reservas._form', [
                        'reserva'      => $reserva,
                        'viajeros'     => $viajeros,
                        'hoteles'      => $hoteles,
                        'vehiculos'    => $vehiculos,
                        'tiposReserva' => $tiposReserva,
                        'maxPlazas'    => $maxPlazas,
                    ])

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('admin.reservas.index') }}" class="btn btn-outline-secondary">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            {{ __('Guardar cambios') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleSegments() {
                const tipo = parseInt(document.getElementById('id_tipo_reserva').value, 10);

                const tramoIda    = document.getElementById('tramo-ida');
                const tramoVuelta = document.getElementById('tramo-vuelta');

                if (tipo === 1) {
                    tramoIda.style.display = '';
                    tramoVuelta.style.display = 'none';
                } else if (tipo === 2) {
                    tramoIda.style.display = 'none';
                    tramoVuelta.style.display = '';
                } else {
                    tramoIda.style.display = '';
                    tramoVuelta.style.display = '';
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const selectTipo = document.getElementById('id_tipo_reserva');
                if (selectTipo) {
                    selectTipo.addEventListener('change', toggleSegments);
                    toggleSegments();
                }
            });
        </script>
    @endpush
</x-admin-layout>