<section class="mb-4">
    <header class="mb-3">
        <h2 class="h5 mb-1 text-danger">{{ __('Desactivar cuenta') }}</h2>
        <p class="small text-muted mb-0">
            {{ __('Una vez descatives tu cuenta, todos tus datos y reservas se conservarán de forma interna, pero no podrás iniciar sesión.') }}
        </p>
    </header>
    {{-- Botón que abre el modal de confiramción --}}
    <div class="d-flex justify-content-end align-items-center gap-3"><x-danger-button type="button"
            data-bs-toggle="modal"
            data-bs-target="#confirm-user-deletion-modal">{{ __('Desactivar cuenta definitivamente') }}</x-danger-button>
    </div>

    {{-- Modal de confirmación usando el componente x-modal --}}
    <x-modal name="confirm-user-deletion-modal" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Confirmar desactivación de cuenta') }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal"
                    aria-label="{{ __('Cerrar') }}"></button>
            </div>

            <div class="modal-body">
                <p class="mb-3">{{ __('Esta acción desactivará tu cuenta.') }}</p>
                <p class="mb-3">
                    {{ __('Tus datos y reservas se conservarán de forma interna, pero no podrás iniciar sesión.') }}
                </p>
                <p class="mb-3">
                    {{ __('Si en el futuro quieres volver a utilizar Isla Transfers, contacta con la administración para reactivar tu cuenta.') }}
                </p>
                <p class="mb-3">{{ __('Introduce tu contraseña para confirmar la operación.') }}</p>
                <div class="mb-3">
                    <x-input-label for="delete_acount_password" :value="__('Contraseña')" />
                    <x-text-input id="delete_acount_password" name="password" type="password" class="mt-1 w-100"
                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->userDeletion->get('password')" />
                </div>
            </div>

            <div class="modal-footer">
                <x-secondary-button type="button" data-bs-dismiss="modal">{{ __('Cancelar') }}</x-secondary-button>
                <x-danger-button>{{ __('Eliminar cuenta') }}</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>