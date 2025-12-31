{{-- resources/views/admin/dashboard.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Panel de administración') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">

            {{-- Primera fila: reservas por estado --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-lg-3 col-xl-2">
                    <x-ui.metric-card
                        :title="__('Reservas totales')"
                        :value="$totalReservas"
                        :subtitle="__('Todas las reservas del sistema')"
                        variant="primary"
                    />
                </div>
                <div class="col-12 col-sm-6 col-lg-3 col-xl-2">
                    <x-ui.metric-card
                        :title="__('Realizadas')"
                        :value="$realizadas"
                        :subtitle="__('Servicios completados')"
                        variant="success"
                    />
                </div>
                <div class="col-12 col-sm-6 col-lg-3 col-xl-2">
                    <x-ui.metric-card
                        :title="__('Confirmadas')"
                        :value="$confirmadas"
                        :subtitle="__('Reservas confirmadas')"
                        variant="info"
                    />
                </div>
                <div class="col-12 col-sm-6 col-lg-3 col-xl-2">
                    <x-ui.metric-card
                        :title="__('Pendientes')"
                        :value="$pendientes"
                        :subtitle="__('Reservas sin gestionar')"
                        variant="warning"
                    />
                </div>
                <div class="col-12 col-sm-6 col-lg-3 col-xl-2">
                    <x-ui.metric-card
                        :title="__('Canceladas')"
                        :value="$canceladas"
                        :subtitle="__('Reservas anuladas')"
                        variant="secondary"
                    />
                </div>
            </div>

            {{-- Segunda fila: activos / totales --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <x-ui.metric-card
                        :title="__('Hoteles activos')"
                        :value="$hotelesActivos"
                        :subtitle="__('de :total hoteles', ['total' => $hotelesTotales])"
                        variant="primary"
                    />
                </div>
                <div class="col-12 col-md-4">
                    <x-ui.metric-card
                        :title="__('Vehículos activos')"
                        :value="$vehiculosActivos"
                        :subtitle="__('de :total vehículos', ['total' => $vehiculosTotales])"
                        variant="info"
                    />
                </div>
                <div class="col-12 col-md-4">
                    <x-ui.metric-card
                        :title="__('Viajeros activos')"
                        :value="$viajerosActivos"
                        :subtitle="__('de :total viajeros', ['total' => $viajerosTotales])"
                        variant="success"
                    />
                </div>
            </div>

            {{-- Tercera fila: calendario --}}
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="h5 mb-0">{{ __('Calendario de reservas') }}</h3>
                            </div>
                            <div id="reservas-calendar"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cuarta fila: próximas reservas (sin columna de acciones) --}}
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="h5 mb-0">{{ __('Próximas reservas') }}</h3>
                                <a href="{{ route('admin.reservas.index') }}"
                                   class="btn btn-sm btn-outline-primary">
                                    {{ __('Ver todas las reservas') }}
                                </a>
                            </div>
                            <x-reservas.table :reservas="$proximasReservas"
                                              context="admin-dashboard"
                                              :show-actions="false" />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        {{-- FullCalendar (CDN) --}}
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const calendarEl = document.getElementById('reservas-calendar');
                if (!calendarEl) return;

                const events = @json($calendarEvents);

                const estadoColors = {
                    pendiente:  '#ffc107',
                    confirmada: '#0d6efd',
                    realizada:  '#198754',
                    cancelada:  '#6c757d',
                };

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    locale: 'es',
                    firstDay: 1,
                    height: 'auto',
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día',
                        list: 'Lista'
                    },
                    events: events.map(ev => {
                        const estado = ev.extendedProps?.estado ?? '';
                        const color  = estadoColors[estado] ?? '#0d6efd';

                        return {
                            ...ev,
                            backgroundColor: color,
                            borderColor: color,
                        };
                    }),
                    eventDidMount(info) {
                        const estado = info.event.extendedProps.estado ?? '';
                        const hotel  = info.event.extendedProps.hotel  ?? '';
                        const tipo   = info.event.extendedProps.tipo   ?? '';

                        let tooltip = info.event.title;
                        if (estado) {
                            tooltip += '\n' + estado.charAt(0).toUpperCase() + estado.slice(1);
                        }
                        if (hotel) {
                            tooltip += '\n' + hotel;
                        }
                        if (tipo) {
                            tooltip += '\n' + tipo;
                        }
                        info.el.setAttribute('title', tooltip);
                    },
                });

                calendar.render();
            });
        </script>
    @endpush
</x-admin-layout>