<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 text-center-mb-3">{{ __('Restablecer contraseña') }}</h1>
                        <p class="small text-muted mb-4">Introduce tu neva contraseña para la cuenta asociada a este
                            correo electrónico.</p>
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            {{-- Token oculto que viene en el enlace del correo --}}
                            <input type="hidden" name="token" value="{{ request()->route('token') }}">
                            {{-- Correo electrónico --}}
                            <div class="mb-3">
                                <x-input-label for="email" :value="__('Correo electrónico')" />
                                <x-text-input id="email" class="mt-1 w-100" type="email" name="email"
                                    :value="old('email', $request->email)" required autofocus />
                                <x-input-error :messages="$errors->get('email')" />
                            </div>
                            {{-- Nueva contraseña --}}
                            <div class="mb-3">
                                <x-input-label for="password" :value="__('Nueva contraseña')" />
                                <x-text-input id="password" class="mt-1 w-100" type="password" name="password" required
                                    autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" />
                            </div>
                            {{-- Confirmación --}}
                            <div class="mb-4">
                                <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                                <x-text-input id="password_confirmation" class="mt-1 w-100" type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
                            </div>
                            <div class="d-grid">
                                <x-primary-button class="w-100">{{ __('Guardar nueva contraseña') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
