<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-4">{{ __('Crear cuenta de viajero') }}</h1>
                        <form action="{{ route('register') }}" method="post">
                            @csrf
                            {{-- Datos personales del viajero --}}
                            <div class="row">
                                {{-- Nombre --}}
                                <div class="col-md-4 mb-3">
                                    <x-input-label for="nombre" :value="__('Nombre')" />
                                    <x-text-input id="nombre" type="text" name="nombre" class="mt-1 w-100"
                                        :value="old('nombre')" require autofocus />
                                    <x-input-error :messages="$errors->get('nombre')" />
                                </div>

                                {{-- Primer apellido --}}
                                <div class="col-md-4 mb-3">
                                    <x-input-label for="apellido1" :value="__('Primer apellido')" />
                                    <x-text-input id="apellido1" type="text" name="apellido1" class="mt-1 w-100"
                                        :value="old('apellido1')" require />
                                    <x-input-error :messages="$errors->get('apellido1')" />
                                </div>

                                {{-- Segundo apellido --}}
                                <div class="col-md-4 mb-3">
                                    <x-input-label for="apellido2" :value="__('Segund apellido (opcional)')" />
                                    <x-text-input id="apellido2" type="text" name="apellido2" class="mt-1 w-100"
                                        :value="old('apellido2')" />
                                    <x-input-error :messages="$errors->get('apellido2')" />
                                </div>
                            </div>


                            {{-- Datos de contacto / dirección --}}
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <x-input-label for="direccion" :value="__('Direccion')" />
                                    <x-text-input id="direccion" type="text" name="direccion" class="mt-1 w-100"
                                        :value="old('direccion')" require />
                                    <x-input-error :messages="$errors->get('direccion')" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <x-input-label for="telefono" :value="__('Teléfono')" />
                                    <x-text-input id="telefono" type="text" name="telefono" class="mt-1 w-100"
                                        :value="old('telefono')" />
                                    <x-input-error :messages="$errors->get('telefono')" />
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <x-input-label for="codigo_postal" :value="__('Código postal')" />
                                    <x-text-input id="codigo_postal" type="text" name="codigo_postal"
                                        class="mt-1 w-100" :value="old('codigo_postal')" require />
                                    <x-input-error :messages="$errors->get('codigo_postal')" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <x-input-label for="ciudad" :value="__('Ciudad')" />
                                    <x-text-input id="ciudad" type="text" name="ciudad" class="mt-1 w-100"
                                        :value="old('ciudad')" require />
                                    <x-input-error :messages="$errors->get('ciudad')" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <x-input-label for="pais" :value="__('País')" />
                                    <x-text-input id="pais" type="text" name="pais" class="mt-1 w-100"
                                        :value="old('pais')" require />
                                    <x-input-error :messages="$errors->get('pais')" />
                                </div>
                            </div>
                            <hr>
                            {{-- Correo electrónico --}}
                            <div class="mb-3">
                                <x-input-label for="email" :value="__('Correo electrónico')" />
                                <x-text-input id="email" type="email" name="email" class="mt-1 w-100"
                                    :value="old('email')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" />
                            </div>
                            <div class="row">
                                {{-- Contraseña --}}
                                <div class="col-md-6 mb-3">
                                    <x-input-label for="password" :value="__('Contraseña')" />
                                    <x-text-input id="password" type="password" name="password" class="mt-1 w-100"
                                        required autocomplete="new-password" />
                                    <small
                                        class="form-text text-muted">{{ __('La contraseña debe tener al menos 8 caracteres.') }}</small>
                                    <x-input-error :messages="$errors->get('password')" />
                                </div>

                                {{-- Confirmación --}}
                                <div class="col-md-6 mb-3">
                                    <x-input-label for="password_confirmation" :value="__('Repetir contraseña')" />
                                    <x-text-input id="password_confirmation" type="password"
                                        name="password_confirmation" class="mt-1 w-100" required
                                        autocomplete="new-password" />
                                </div>
                            </div>


                            <x-primary-button class="w-100">{{ __('Registrarse') }}</x-primary-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
