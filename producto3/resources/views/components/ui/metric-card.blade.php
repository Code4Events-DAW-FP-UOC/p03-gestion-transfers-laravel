@props([
    'title',
    'value',
    'subtitle' => null,
    'variant' => 'light', // primary, success, warning, danger, info...
])

@php
    $bgClass = match ($variant) {
        'primary'  => 'bg-primary text-white',
        'success'  => 'bg-success text-white',
        'warning'  => 'bg-warning',
        'danger'   => 'bg-danger text-white',
        'info'     => 'bg-info',
        'secondary'=> 'bg-secondary text-white',
        default    => 'bg-white',
    };
@endphp

<div {{ $attributes->merge(['class' => 'card shadow-sm h-100 ' . $bgClass]) }}>
    <div class="card-body">
        <h3 class="h6 text-uppercase mb-1">{{ $title }}</h3>
        <div class="display-6 fw-semibold">{{ $value }}</div>
        @if($subtitle)
            <p class="mb-0 small opacity-75">{{ $subtitle }}</p>
        @endif
    </div>
</div>