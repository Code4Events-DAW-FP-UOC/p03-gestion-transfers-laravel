{{-- resources/views/admin/users/create.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Nuevo usuario') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                {{ __('Volver al listado') }}
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                @include('layouts.partials.flash-messages')

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    @include('admin.users._form', [
                        'user' => null,
                        'viajero' => null,
                    ])

                    <div class="mt-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>
                            {{ __('Guardar usuario') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>