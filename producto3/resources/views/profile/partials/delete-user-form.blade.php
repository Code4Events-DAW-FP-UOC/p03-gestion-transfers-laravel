<section class="mb-4">
    <header class="mb-3">
        <h2 class="h5 mb-1 text-danger">{{ __('Eliminar cuenta') }}</h2>
        <p class="small text-muted mb-0">
            {{ __('Una vez elimines tu cuenta, todos tus datos se borrarán de forma permanente. Esta acción no se puede deshacer.') }}
        </p>
    </header>
    {{-- Botón que abre el modal de confiramción --}}
    <div class="d-flex justify-content-end align-items-center gap-3"><x-danger-button type="button" data-bs-toggle="modal"
            data-bs-target="#confirm-user-deletion-modal">{{ __('Eliminiar cuenta definitivamente') }}</x-danger-button>
    </div>

    {{-- Modal de confirmación usando el componente x-modal --}}
    <x-modal name="confirm-user-deletion-modal" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Confirmar eliminación de cuenta') }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal"
                    aria-label="{{ __('Cerrar') }}"></button>
            </div>

            <div class="modal-body">
                <p class="mb-3">
                    {{ __('Una vez eliminas tu cuenta, todos tus datos se borrarán de forma permanente. Introduce tu contraseña para confirmar la operación.') }}
                </p>
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
