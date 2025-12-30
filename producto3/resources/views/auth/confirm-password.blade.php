<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-3">{{ __('Confirmar contraseña') }}</h1>
                        <p class="small text-muted mb-4">
                            {{ __('Por seguridad, antes de continuar necesitamos que confirmes tu contraseña.') }}</p>
                        <x-auth-session-status class="mb-3" :status="session('status')" />
                        <form action="{{ route('password.confirm') }}" method="POST">
                            @csrf
                            {{-- Contraseña --}}
                            <div class="mb-3">
                                <x-input-label for="password" :value="__('Contraseña')" />
                                <x-text-input id="password" class="mt-1 w-100" type="password" name="password" required
                                    autocomplete="current-password" />
                                <x-input-error :messages="$errors->get('password')" />
                            </div>
                            <div class="d-grid">
                                <x-primary-button class="w-100">{{ __('Confirmar') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
