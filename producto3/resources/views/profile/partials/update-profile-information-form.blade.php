<section>
    <header>
        <h2 class="h5 mb-3">{{ __('Información de perfil') }}</h2>
        <p class="text-muted small mb-4">{{ __('Actualiza el nombre y el correo electrónico asociados a tu cuenta.') }}
        </p>
    </header>
    {{-- Formulario "oculto" para reenviar verificación de email --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
    {{-- Formulario pricipal de actualización de perfil --}}
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')
        {{-- Nombre --}}
        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 w-100" :value="old('name', $user->name)" required
                autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        {{--  Correo electrónico --}}
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 w-100" :value="old('email', $user->email)" required
                autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="small mb-2">
                        {{ __('Tu dirección de correo electrónico no está verificada.') }}
                        <button form="send-verification" type="submit" class="btn btn-link btn-sm p-0 align-baseline">
                            {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="alert alert-succes py-2 mb-0">
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu correo electrónico.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        {{-- Botón guardar + mensaje "Guardado" --}}
        <div class="d-flex items-center gap-3">
            <x-primary-button>{{ __('Guardar cambios') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="samll text-muted mb-00">{{ __('Cambios guardados.') }}</p>
            @endif
        </div>
    </form>
</section>
