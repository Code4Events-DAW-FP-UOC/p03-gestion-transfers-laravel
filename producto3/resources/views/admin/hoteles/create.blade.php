{{-- resources/views/admin/hoteles/create.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Crear nuevo hotel') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.hoteles.store') }}" method="POST">
                    @csrf
                    @include('admin.hoteles._form', ['hotel' => null])
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>