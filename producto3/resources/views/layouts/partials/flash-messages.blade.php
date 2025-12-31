{{-- views/layouts/partials/flash-messages.blade.php --}}
@if (session('status'))
    <div class="alert alert-success mb-3">
        {{ session('status') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger mb-3">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif