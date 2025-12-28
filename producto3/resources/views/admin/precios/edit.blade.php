{{-- resources/views/admin/precios/edit.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Editar precio') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.precios.update', $precio) }}" method="POST">
                    @method('PUT')
                    @include('admin.precios._form', ['precio' => $precio])
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>