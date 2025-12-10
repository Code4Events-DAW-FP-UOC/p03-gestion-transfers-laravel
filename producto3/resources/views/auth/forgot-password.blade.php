<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-3">{{ __('¿Has olvidado la contraseña?') }}</h1>
                        <p class="small text-muted mb-3">
                            {{ __('Indica tu correo electrónico y te enviaremos un enlace para que puedas restablecer tu contraseña.') }}
                        </p>
                        <x-auth-session-status class="mb-3" :status="session('status')" />
                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf
                            {{-- Correo electrónico --}}
                            <div class="mb-3">
                                <x-input-label for="email" :value="__('Correo electrónico')" />
                                <x-text-input id="email" class="mt-1 w-100" type="email" name="email"
                                    :value="old('email')" required autofocus />
                                <x-input-error :messages="$errors->get('email')" />
                            </div>
                            <div class="d-grid gap-2">
                                <x-primary-button
                                    class="w-100">{{ __('Enviar enlace de recuperación') }}</x-primary-button>
                                <a href="{{ route('login') }}"
                                    class="btn btn-outline-primary w-100">{{ __('Volver al inicio de sesión') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
