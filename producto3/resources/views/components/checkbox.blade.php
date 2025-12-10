{{-- resources/views/components/checkbox.blade.php --}}
@props(['name' => null])

<input type="checkbox" {{ $attributes->merge(['class' => 'form-check-input']) }}>
