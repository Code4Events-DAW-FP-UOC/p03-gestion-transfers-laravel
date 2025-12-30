{{-- resources/views/viajero/_fields.blade.php --}}

{{-- Datos personales del viajero --}}
<div class="row">
    {{-- Nombre --}}
    <div class="col-md-4 mb-3">
        <x-input-label for="nombre" :value="__('Nombre')" />
        <x-text-input id="nombre" type="text" name="nombre" class="mt-1 w-100" :value="old('nombre', $viajero->nombre ?? '')" required />
        <x-input-error :messages="$errors->get('nombre')" />
    </div>

    {{-- Primer apellido --}}
    <div class="col-md-4 mb-3">
        <x-input-label for="apellido1" :value="__('Primer apellido')" />
        <x-text-input id="apellido1" type="text" name="apellido1" class="mt-1 w-100" :value="old('apellido1', $viajero->apellido1 ?? '')" required />
        <x-input-error :messages="$errors->get('apellido1')" />
    </div>

    {{-- Segundo apellido --}}
    <div class="col-md-4 mb-3">
        <x-input-label for="apellido2" :value="__('Segundo apellido (opcional)')" />
        <x-text-input id="apellido2" type="text" name="apellido2" class="mt-1 w-100" :value="old('apellido2', $viajero->apellido2 ?? '')" />
        <x-input-error :messages="$errors->get('apellido2')" />
    </div>
</div>
{{-- Datos de contacto / dirección --}}
<div class="row">
    {{-- Dirección --}}
    <div class="col-md-8 mb-3">
        <x-input-label for="direccion" :value="__('Direccion')" />
        <x-text-input id="direccion" type="text" name="direccion" class="mt-1 w-100" :value="old('direccion', $viajero->direccion ?? '')" required />
        <x-input-error :messages="$errors->get('direccion')" />
    </div>
    {{-- Teléfono --}}
    <div class="col-md-4 mb-3">
        <x-input-label for="telefono" :value="__('Teléfono')" />
        <x-text-input id="telefono" type="text" name="telefono" class="mt-1 w-100" :value="old('telefono', $viajero->telefono ?? '')" />
        <x-input-error :messages="$errors->get('telefono')" />
    </div>
</div>


<div class="row">
    {{-- Código postal --}}
    <div class="col-md-4 mb-3">
        <x-input-label for="codigo_postal" :value="__('Código postal')" />
        <x-text-input id="codigo_postal" type="text" name="codigo_postal" class="mt-1 w-100" :value="old('codigo_postal', $viajero->codigo_postal ?? '')"
            require />
        <x-input-error :messages="$errors->get('codigo_postal')" />
    </div>
    {{-- Ciudad --}}
    <div class="col-md-4 mb-3">
        <x-input-label for="ciudad" :value="__('Ciudad')" />
        <x-text-input id="ciudad" type="text" name="ciudad" class="mt-1 w-100" :value="old('ciudad', $viajero->ciudad ?? '')" require />
        <x-input-error :messages="$errors->get('ciudad')" />
    </div>
    {{-- País --}}
    <div class="col-md-4 mb-3">
        <x-input-label for="pais" :value="__('País')" />
        <x-text-input id="pais" type="text" name="pais" class="mt-1 w-100" :value="old('pais', $viajero->pais ?? '')" require />
        <x-input-error :messages="$errors->get('pais')" />
    </div>
</div>
