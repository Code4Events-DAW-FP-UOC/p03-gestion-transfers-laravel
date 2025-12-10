<section class="mb-4">
    <header class="mb-3">
        <h2 class="h5 mb-1">{{ __('Actualizar contraseña') }}</h2>
        <p class="small text-muted mb-0">
            {{ __('Asegúrate de utilizar una contraseña larga y difícil de adivinar.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')
        {{-- Contraseña actual --}}
        <div class="mb-3">
            <x-input-label for="update_password_current_password" :value="__('Contraseña actual')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 w-100"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>
        {{-- Nueva contraseña --}}
        <div class="mb-3">
            <x-input-label for="update_password_password" :value="__('Nueva contraseña')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 w-100"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>
        {{-- Confirmación de contraseña --}}
        <div class="mb-3">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar contraseña')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 w-100" autocomplete="new-password" />
        </div>

        <div class="d-flex justify-content-end align-items-center gap-3">
            <x-primary-button>{{ __('Actualizar contraseña') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="small text-muted mb-0">{{ __('Contraseña actualizada corrrectamente.') }}</p>
            @endif
        </div>
    </form>
</section>
