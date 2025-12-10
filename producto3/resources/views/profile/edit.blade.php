<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="row g-4">
            {{-- Datos de usuario --}}
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>
            {{-- Cambiar contraseña --}}
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
            {{-- Eliminar cuenta --}}
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
