{{-- resources/views/admin/zonas/edit.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Editar zona') }}: {{ $zona->descripcion }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.zonas.update', $zona) }}" method="POST">
                    @method('PUT')
                    @include('admin.zonas._form', ['zona' => $zona])
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>