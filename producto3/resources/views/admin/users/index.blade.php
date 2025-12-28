{{-- resources/views/admin/users/index.blade.php --}}
<x-admin-layout>
    {{-- Título + botón "Nuevo usuario" --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Usuarios') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                {{ __('Nuevo usuario') }}
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Buscador opcional --}}
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3">
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <input type="text"
                               name="q"
                               class="form-control form-control-sm"
                               placeholder="{{ __('Buscar por nombre o email...') }}"
                               value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i> {{ __('Buscar') }}
                        </button>
                    </div>
                    @if(!empty($search))
                        <div class="col-auto">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-link">
                                {{ __('Limpiar') }}
                            </a>
                        </div>
                    @endif
                </form>

                {{-- Tabla de resultados --}}
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Nombre y apellidos') }}</th>
                                <th>{{ __('Correo electrónico') }}</th>
                                <th>{{ __('Rol') }}</th>
                                <th>{{ __('Estado') }}</th>
                                <th>{{ __('Teléfono') }}</th>
                                <th class="text-end">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $userRow)
                                @php
                                    $viajero = $userRow->viajero;

                                    // Nombre completo: si es viajero usamos su ficha, si no el name del usuario
                                    $nombreCompleto = $viajero
                                        ? trim($viajero->nombre . ' ' . $viajero->apellido1 . ' ' . $viajero->apellido2)
                                        : $userRow->name;

                                    // Teléfono: prioridad viajero, luego posible campo en users, si no guion
                                    $telefono = $viajero->telefono
                                        ?? $userRow->telefono
                                        ?? '—';

                                    // Estado (activo / inactivo)
                                    $estado = $userRow->activo ? 'activo' : 'inactivo';

                                    $estadoBadgeClass = $userRow->activo
                                        ? 'bg-success'
                                        : 'bg-secondary';

                                    // Permiso para borrar: aquí SOLO dejamos borrar si está inactivo
                                    $canDelete = $userRow->activo;
                                @endphp

                                <tr>
                                    {{-- Nombre y apellidos --}}
                                    <td>{{ $nombreCompleto }}</td>

                                    {{-- Email --}}
                                    <td>{{ $userRow->email }}</td>

                                    {{-- Rol --}}
                                    <td>
                                        @if($userRow->rol === 'admin')
                                            {{ __('Administrador') }}
                                        @elseif($userRow->rol === 'viajero')
                                            {{ __('Viajero') }}
                                        @else
                                            {{ ucfirst($userRow->rol) }}
                                        @endif
                                    </td>

                                    {{-- Estado --}}
                                    <td>
                                        <span class="badge {{ $estadoBadgeClass }}">
                                            {{ ucfirst($estado) }}
                                        </span>
                                    </td>

                                    {{-- Teléfono --}}
                                    <td>{{ $telefono }}</td>

                                    {{-- Acciones --}}
                                    <td class="text-end">
                                        {{-- Ver / editar --}}
                                        <a href="{{ route('admin.users.edit', $userRow) }}"
                                        class="btn btn-sm btn-outline-secondary me-1"
                                        title="{{ __('Ver / editar') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.users.edit', $userRow) }}"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="{{ __('Editar') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- Eliminar: botón que abre modal --}}
                                        @if ($canDelete)
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteUser-{{ $userRow->id }}"
                                                    title="{{ __('Eliminar') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary"
                                                    title="{{ __('No se puede eliminar mientras esté inactivo') }}"
                                                    disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                    {{-- Modal eliminar usuario --}}
                                    @if ($canDelete)
                                        <x-ui.modal
                                            :id="'deleteUser-' . $userRow->id"
                                            :title="__('Eliminar usuario')"
                                            size="sm"
                                        >
                                            <p class="mb-3">
                                                {{ __('¿Seguro que quieres desactivar el usuario ":user"?', [
                                                    'user' => $nombreCompleto,
                                                ]) }}
                                            </p>
                                            <p class="small text-muted">
                                                {{ __('No se eliminarán sus datos históricos, solo se marcará como inactivo.') }}
                                            </p>

                                            <x-slot name="footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    {{ __('Cancelar') }}
                                                </button>

                                                <form action="{{ route('admin.users.destroy', $userRow) }}"
                                                    method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-trash me-1"></i>
                                                        {{ __('Desactivar usuario') }}
                                                    </button>
                                                </form>
                                            </x-slot>
                                        </x-ui.modal>
                                    @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        {{ __('No hay usuarios para mostrar.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if ($users->hasPages())
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            {{ __('Mostrando :from a :to de :total resultados', [
                                'from'  => $users->firstItem(),
                                'to'    => $users->lastItem(),
                                'total' => $users->total(),
                            ]) }}
                        </div>

                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>