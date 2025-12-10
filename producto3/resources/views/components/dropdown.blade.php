{{-- resources/views/components/dropdown.blade.php --}}
@props([
    'align' => 'end', // 'start' o 'end' para Bootstrap
])

@php
    // Alineación Bootstrap: dropdown-menu-start / dropdown-menu-end
    $alignmentClass = $align === 'start' ? 'dropdown-menu-start' : 'dropdown-menu-end';
@endphp

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    {{-- Trigger: botón que abre el dropdown --}}
    <button class="btn btn-link nav-link dropdown-toggle p-0 border-0" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        {{ $trigger ?? '' }}
    </button>

    {{-- Contenido del dropdown --}}
    <ul class="dropdown-menu {{ $alignmentClass }}">
        {{ $content ?? '' }}
    </ul>
</div>
