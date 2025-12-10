<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-4">{{ __('Crear cuenta de viajero') }}</h1>
                        <form action="{{ route('register') }}" method="post">
                            @csrf
                            {{-- Nombre --}}
                            <div class="mb-3">
                                <x-input-label for="name" :value="__('Nombre')" />
                                <x-text-input id="name" type="text" name="name" class="mt-1 w-100"
                                    :value="old('name')" require autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" />
                            </div>

                            {{-- Correo electrónico --}}
                            <div class="mb-3">
                                <x-input-label for="email" :value="__('Correo electrónico')" />
                                <x-text-input id="email" type="email" name="email" class="mt-1 w-100"
                                    :value="old('email')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" />
                            </div>

                            {{-- Contraseña --}}
                            <div class="mb-3">
                                <x-input-label for="password" :value="__('Contraseña')" />
                                <x-text-input id="password" type="password" name="password" class="mt-1 w-100" required
                                    autocomplete="new-password" />
                                <small
                                    class="form-text text-muted">{{ __('La contraseña debe tener al menos 8 caracteres.') }}</small>
                                <x-input-error :messages="$errors->get('password')" />
                            </div>

                            {{-- Confirmación --}}
                            <div class="mb-4">
                                <x-input-label for="password_confirmation" :value="__('Repetir contraseña')" />
                                <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                                    class="mt-1 w-100" required autocomplete="new-password" />
                            </div>

                            <x-primary-button class="w-100">{{ __('Registrarse') }}</x-primary-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
