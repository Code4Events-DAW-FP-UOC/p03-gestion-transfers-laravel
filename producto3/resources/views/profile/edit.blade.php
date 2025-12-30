{{-- resources/view/profile/edit.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Perfil') }}
        </h2>
    </x-slot>
    <div class="py-4">
        {{-- Datos de usuario --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form', [
                            'user' => $user,
                            'viajero' => $viajero,
                            'hotel' => $hotel,
                        ])
                    </div>
                </div>
            </div>
        </div>
        {{-- Cambiar contraseña --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>
        {{-- Eliminar cuenta --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
