<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Precio extends Model
{
    use HasFactory;

    protected $table = 'p3_transfer_precios';
    protected $primaryKey = 'id_precio';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_vehiculo',
        'id_hotel',
        'precio',
    ];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id_hotel');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_precio', 'id_precio');
    }
}
