{{-- resources/views/admin/hoteles/edit.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Editar hotel') }}: {{ $hotel->nombre }}
            </h2>
            {{-- Botón para resetear contraseña --}}
            @if ($hotel->user)
                {{-- Botón que abre el modal --}}
                <button type="button"
                        class="btn btn-sm btn-outline-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#resetPasswordModal-{{ $hotel->id_hotel }}">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    {{ __('Restablecer contraseña') }}
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.hoteles.update', $hotel) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.hoteles._form', ['hotel' => $hotel])
                </form>
            </div>
        </div>
    </div>
    {{-- Modal para restablecer la contraseña del hotel --}}
    @if ($hotel->user)
        <x-ui.modal :id="'resetPasswordModal-'.$hotel->id_hotel"
                    :title="__('Restablecer contraseña')"
                    size="md">

            <p class="mb-3">
                {{ __('Vas a restablecer la contraseña del usuario del hotel a la contraseña por defecto.') }}
            </p>
            <p class="mb-0">
                <strong>{{ __('Contraseña por defecto:') }}</strong> <code>islatransfers</code><br>
                <span class="text-muted small">
                    {{ __('El hotel deberá cambiarla desde su perfil tras acceder de nuevo.') }}
                </span>
            </p>

            <x-slot name="footer">
                <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    {{ __('Cancelar') }}
                </button>

                <form action="{{ route('admin.hoteles.reset-password', $hotel) }}"
                      method="POST"
                      class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        {{ __('Restablecer contraseña') }}
                    </button>
                </form>
            </x-slot>
        </x-ui.modal>
    @endif
</x-admin-layout>