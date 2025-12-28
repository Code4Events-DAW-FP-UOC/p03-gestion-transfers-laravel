{{-- resources/views/admin/users/edit.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Editar usuario') }}
            </h2>

            <div class="d-flex gap-2">
                {{-- Botón restablecer contraseña (solo si no es el propio admin autenticado) --}}
                @if (auth()->id() !== $user->id)
                    <button type="button"
                            class="btn btn-outline-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#resetPasswordUser-{{ $user->id }}">
                        <i class="bi bi-key me-1"></i>
                        {{ __('Restablecer contraseña') }}
                    </button>
                @endif

                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    {{ __('Volver al listado') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    @include('admin.users._form', [
                        'user' => $user,
                        'viajero' => $viajero ?? null,
                    ])

                    <div class="mt-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>
                            {{ __('Guardar cambios') }}
                        </button>
                    </div>
                </form>

                {{-- Modal para restablecer contraseña --}}
                @if (auth()->id() !== $user->id)
                    <x-ui.modal
                        :id="'resetPasswordUser-' . $user->id"
                        :title="__('Restablecer contraseña')"
                        size="sm"
                    >
                        <p class="mb-3">
                            {{ __('¿Seguro que quieres restablecer la contraseña de este usuario?') }}<br>
                            {{ __('La nueva contraseña será "islatransfers".') }}
                        </p>

                        <x-slot name="footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                {{ __('Cancelar') }}
                            </button>

                            <form action="{{ route('admin.users.reset-password', $user) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf

                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-key me-1"></i>
                                    {{ __('Restablecer contraseña') }}
                                </button>
                            </form>
                        </x-slot>
                    </x-ui.modal>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>