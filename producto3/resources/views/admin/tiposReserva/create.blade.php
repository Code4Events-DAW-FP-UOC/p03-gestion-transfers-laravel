{{-- resources/views/admin/tiposReserva/create.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Nuevo tipo de reserva') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.tiposReserva.store') }}" method="POST">
                    @include('admin.tiposReserva._form', ['tipoReserva' => $tipoReserva])
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>