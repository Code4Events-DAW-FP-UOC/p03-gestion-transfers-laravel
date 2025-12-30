{{-- resources/views/components/primary-button.blade.php --}}
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary']) }}>
    {{ $slot }}
</button>
