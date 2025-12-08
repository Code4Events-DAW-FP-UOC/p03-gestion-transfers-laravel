<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'p3_transfer_reservas';
    protected $primaryKey = 'id_reserva';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'localizador',
        'id_hotel',
        'id_viajero',
        'id_creador',
        'id_modificador',
        'id_tipo_reserva',
        'id_precio',
        'fecha_reserva',
        'fecha_modificacion',
        'id_hotel_destino',
        'num_viajeros',
        'id_vehiculo',
        'fecha_entrada',
        'hora_entrada',
        'numero_vuelo_entrada',
        'origen_vuelo_entrada',
        'fecha_vuelo_salida',
        'hora_vuelo_salida',
        'numero_vuelo_salida',
        'destino_vuelo_salida',
        'estado',
        'observaciones',
    ];

    /* RELACIONES */
    
    public function viajero(): BelongsTo
    {
        return $this->belongsTo(Viajero::class, 'id_viajero', 'id_viajero');
    }

    public function hotelGestor(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id_hotel');
    }

    public function hotelDestino(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'id_hotel_destino', 'id_hotel');
    }

    public function tipoReserva(): BelongsTo
    {
        return $this->belongsTo(TiposReserva::class, 'id_tipo_reserva', 'id_tipo_reserva');
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function precio(): BelongsTo
    {
        return $this->belongsTo(Precio::class, 'id_precio', 'id_precio');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_creador');
    }

    public function modificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_modificador');
    }
}
