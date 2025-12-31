<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;
    
    protected $table = 'p3_transfer_hoteles';
    protected $primaryKey = 'id_hotel';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'id_zona',
        'nombre',
        'email',
        'comision',
        'telefono',
        'activo',
    ];

    /* RELACIONES */

    // Hotel pertenece a un User (login)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Hotel pertenece a una Zona
    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class, 'id_zona', 'id_zona');
    }

    // Un hotel puede tener muchas reservas
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'id_hotel', 'id_hotel');
    }

    public function precios(): HasMany
    {
        return $this->hasMany(Precio::class, 'id_hotel', 'id_hotel');
    }

    /* SCOPES ÚTILES */

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }       
    
}
