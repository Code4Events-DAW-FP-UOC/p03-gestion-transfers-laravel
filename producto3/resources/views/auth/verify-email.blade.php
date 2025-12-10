<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shado-sm">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-3">{{ __('Verifica tu correo electónico') }}</h1>
                        <p class="small text-muted">
                            {{ _('Te hemos enviado un enlace de verificación a tu correo electrónico. Antes de continuar, por favor revisa tu bandeja de entrada (y la carpeta de spam, por si acaso).') }}
                        </p>
                        <p class="small text-muted">
                            {{ __('Si no has recibido el correo, puedes solicitar otro haciendo clic en el botón siguiente:') }}
                        </p>
                        {{-- Mensaje de "link enviado" --}}
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-succes py-2">
                                {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo.') }}
                            </div>
                        @endif
                        <div class="d-flex justify.contennt-between align-items-center mt-3">
                            {{-- Reenviar enlace de verificación --}}
                            <form action="{{ route('verification.send') }}" method="POST">
                                @csrf
                                <x-primary-button>{{ __('Reenviar correo de verificación') }}</x-primary-button>
                            </form>
                            {{-- Cerrar sesión --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="btn btn-outline-primary">{{ __('Cerrrar sesión') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
