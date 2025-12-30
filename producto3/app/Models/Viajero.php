<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reserva;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Viajero extends Model
{
    use HasFactory;

    protected $table = 'p3_transfer_viajeros';
    protected $primaryKey = 'id_viajero';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido1',
        'apellido2',
        'direccion',
        'codigo_postal',
        'ciudad',
        'pais',
        'email',
        'telefono',
        'activo',
    ];

    /* RELACIONES */

    // Viajero pertenece a un User (login)
    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Un viajero puede tener muchas reservas
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'id_viajero', 'id_viajero');
    }

    /* SCOPES ÚTILES */

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    } 
}
