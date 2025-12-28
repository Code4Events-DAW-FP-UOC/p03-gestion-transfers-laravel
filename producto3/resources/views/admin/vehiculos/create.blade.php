{{-- resources/views/admin/vehiculos/create.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Nuevo vehículo') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.vehiculos.store') }}" method="POST">
                    @include('admin.vehiculos._form', ['vehiculo' => $vehiculo])
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>