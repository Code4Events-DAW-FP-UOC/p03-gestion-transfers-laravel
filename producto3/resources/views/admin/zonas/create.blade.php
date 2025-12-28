{{-- {{-- resources/views/admin/zonas/create.blade.php --}}
 --}}
<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Nueva zona') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.zonas.store') }}" method="POST">
                    @include('admin.zonas._form', ['zona' => $zona])
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>