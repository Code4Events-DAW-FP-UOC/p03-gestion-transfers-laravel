{{-- resources/view/profile/partials/update-profile-information-form.blade.php --}}
<section>
    <header>
        <h2 class="h5 mb-3">{{ __('Información de perfil') }}</h2>
        <p class="text-muted small mb-4">{{ __('Actualiza los datos asociados a tu cuenta de Isla Transfers.') }}
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
        {{-- Datos cuenta --}}
        @include('users._account_fields', ['user' => $user])
        <hr class="my-4">
        {{-- Datos viajero u hotel --}}
        @if ($user->isHotel())
            @include('hotel._fields', ['hotel' => $hotel, 'user' => $user])
        @else
            @include('viajero._fields', ['viajero' => $viajero, 'user' => $user])
        @endif
        {{-- Botón guardar + mensaje "Guardado" --}}
        <div class="d-flex justify-content-end align-items-center gap-3">
            <x-primary-button>{{ __('Guardar cambios') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="samll text-muted mb-00">{{ __('Cambios guardados.') }}</p>
            @endif
        </div>
    </form>
</section>
