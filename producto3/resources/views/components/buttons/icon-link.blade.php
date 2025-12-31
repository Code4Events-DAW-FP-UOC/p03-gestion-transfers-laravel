@props([
    'href' => '#',
    'title' => '',
    'icon' => 'eye',                 // nombre del icono de Bootstrap
    'variant' => 'outline-primary',  // outline-primary, outline-secondary, outline-danger...
    'size' => 'sm',
    'disabled' => false,
])
@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
<a href="{{ $disabled ? '#' : $href }}" @if($disabled) aria-disabled="true" tabindex="-1" @endif {{ $attributes->merge(['class' => 'btn btn-' . $size . ' btn-' . $variant . ($disabled ? ' disabled' : '')]) }} @if(!$disabled && $title) title="{{ $title }}" @endif>
    <i class="bi bi-{{ $icon }}"></i>
</a>