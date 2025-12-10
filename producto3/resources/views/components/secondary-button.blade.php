{{-- resources/views/components/secondary-button.blade.php --}}
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-secondary']) }}>
    {{ $slot }}
</button>
