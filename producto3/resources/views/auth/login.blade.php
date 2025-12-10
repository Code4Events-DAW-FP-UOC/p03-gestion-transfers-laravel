<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Iniciar sesión</h1>
                        {{-- Estado de sessión --}}
                        <x-auth-session-status class="mb-3" :status="session('status')" />
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            {{-- Correo electrónico --}}
                            <div class="mb-3">
                                <x-input-label for="email" :value="__('Correo electrónico')" />
                                <x-text-input id="email" class="mt-1 w-100" type="email" name="email"
                                    :value="old('email')" required autofocus autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" />
                            </div>
                            {{-- Contraseña --}}
                            <div class="mb-3">
                                <x-input-label for="password" :value="__('Contraseña')" />
                                <x-text-input id="password" class="mt-1 w-100" type="password" name="password"
                                    :value="old('email')" required autocomplete="current-password" />
                                <x-input-error :messages="$errors->get('password')" />
                            </div>
                            {{-- Recuérdame --}}
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="from-check">
                                    <x-checkbox id="remember_me" name="remember" />
                                    <label for="remember_me" class="from-check-label ms-1"></label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="small text-decoration-none">{{ __('¿Has olvidado la contraseña?') }}</a>
                                @endif
                            </div>
                            <div class="d-grid gap-2">
                                <x-primary-button class="w-100">{{ __('Iniciar sesión') }}</x-primary-button>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="btn btn-outline-primary w-100">{{ __('Crear cuenta de viajero') }}</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
