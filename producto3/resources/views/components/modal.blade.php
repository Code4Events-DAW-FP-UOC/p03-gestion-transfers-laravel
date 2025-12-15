{{-- resources/views/components/modal.blade.php --}}
@props(['name', 'show' => false, 'focusable' => false])

@php

    $modalId = $name ?? 'modal-' . uniqid();
@endphp

<div {{ $attributes->merge(['class' => 'modal fade']) }} id="{{ $modalId }}" tabindex="-1"
    aria-hidden="{{ $show ? 'false' : 'true' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>

@if ($show)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalEl = document.getElementById('{{ $modalId }}');
            if (!modalEl || typeof bootstrap === 'undefined') return;
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        });
    </script>
@endif