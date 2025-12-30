<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TiposReserva extends Model
{
    use HasFactory;

    protected $table = 'p3_transfer_tipos_reservas';
    protected $primaryKey = 'id_tipo_reserva';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'descripcion',
        'codigo',
    ];

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'id_tipo_reserva', 'id_tipo_reserva');
    }
}
