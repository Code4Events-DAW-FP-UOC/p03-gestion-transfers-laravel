{{-- resources/views/components/dropdown-link.blade.php --}}
@props(['as' => 'a'])

@if ($as === 'button')
    <button {{ $attributes->merge(['class' => 'dropdown-item']) }}>
        {{ $slot }}
    </button>
@else
    <a {{ $attributes->merge(['class' => 'dropdown-item']) }}>
        {{ $slot }}
    </a>
@endif
