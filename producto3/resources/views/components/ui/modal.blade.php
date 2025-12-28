{{-- components/ui/modal.blade.php --}}
@props([
    'id',
    'title' => '',
    'size' => 'lg', // sm, md, lg, xl
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }} modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                @if($title)
                    <h5 class="modal-title">{{ $title }}</h5>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('Cerrar') }}"></button>
            </div>

            <div class="modal-body">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>