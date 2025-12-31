{{-- resources/views/admin/precios/create.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Nuevo precio') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.precios.store') }}" method="POST">
                    @include('admin.precios._form', ['precio' => $precio])
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>