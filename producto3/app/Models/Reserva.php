<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property Carbon|null $fecha_reserva
 * @property Carbon|null $fecha_entrada
 * @property Carbon|null $fecha_vuelo_salida
 */
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

    protected $casts = [
        'fecha_reserva'=> 'datetime',
        'fecha_modificacion' => 'datetime',
        'fecha_entrada'=> 'date',
        'fecha_vuelo_salida'=> 'date',
    ];

    /**
     * Genera un localizador único para la reserva.
     */
    public static function generarLocalizador(): string
    {
        do{
            $code = strtoupper(substr(md5(uniqid('', true)), 0, 8));
        } while (self::where('localizador', $code)->exists());

        return $code;
    }

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

    public function getCreadorLabelAttribute(): string
    {
        if (! $this->creador) {
            return '-';
        }

        return match ($this->creador->rol) {
            'viajero' => 'Viajero',
            'hotel'   => 'Hotel',
            'admin'   => 'Administrador',
            default   => ucfirst($this->creador->rol),
        };
    }

    /**
     * Importe total del traslado según tipo de reserva.
     *
     * - Solo ida  → 1 × tarifa
     * - Solo vuelta → 1 × tarifa
     * - Ida y vuelta → 2 × tarifa
     */
    public function getImporteAttribute(): ?float
    {
        if (! $this->precio) {
            return null;
        }

        $factor = 1;

        if (in_array($this->id_tipo_reserva, [3], true)) {
            $factor = 2;
        }

        return (float) $this->precio->precio * $factor;
    }

    public function getGananciaHotelAttribute()
    {
        $comision = $this->hotelDestino->comision ?? 0;
        return $this->importe * ($comision / 100);
    }

    public function getTotalAttribute(): ?float
    {
        $importe = $this->importe; // Llamamos al accesor de arriba

        if ($importe === null) {
            return null;
        }

        // Obtenemos la comisión del hotel de destino (si no existe, 0)
        $comisionPorcentaje = $this->hotelDestino->comision ?? 0;

        // Cálculo: Importe + (Importe * % / 100)
        return (float) $importe * (1 + ($comisionPorcentaje / 100));
    }

}
