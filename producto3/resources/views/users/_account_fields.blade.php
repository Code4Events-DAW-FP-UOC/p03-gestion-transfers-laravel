{{-- resources/views/users/_account_fields.blade.php --}}

{{-- Correo electrónico --}}
<div class="row mb-3">
    <div class="col-12 col-lg-8">
        <div class="mb-3">
            <x-input-label for="user_email" :value="__('Correo electrónico')" />
            <x-text-input id="user_email" name="email" type="email" class="mt-1 w-100" :value="old('email', $user->email)" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <x-input-label for="user_rol" :value="__('Rol')" />
        <input id="user_rol" type="text" class="form-control mt-1"
            value="@switch($user->rol)
                        @case('admin') Administrador @break
                        @case('hotel') Hotel @break
                        @case('viajero') Viajero @break
                        @default {{ ucfirst($user->rol) }}
                   @endswitch"
            readonly>
        <small class="text-muted">
            {{ __('El rol determina el tipo de acceso al sistema.') }}
        </small>
    </div>
</div>
{{-- Verificación de email, solo si aplica --}}
@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
    <div class="alert alert-warning mt-2">
        <p class="small mb-2">
            {{ __('Tu dirección de correo electrónico no está verificada.') }}
        </p>
        <button form="send-verification" type="submit" class="btn btn-link btn-sm p-0 align-baseline">
            {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
        </button>

        @if (session('status') === 'verification-link-sent')
            <p class="small text-success mb-0 mt-2">
                {{ __('Se ha enviado un nuevo enlace de verificación a tu correo electrónico.') }}
            </p>
        @endif
    </div>
@endif
