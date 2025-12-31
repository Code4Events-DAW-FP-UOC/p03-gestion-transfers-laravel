<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'p3_transfer_vehiculos';
    protected $primaryKey = 'id_vehiculo';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'descripcion',
        'email',
        'matricula',
        'plazas',
        'activo',
    ];

    public function precios(): HasMany
    {
        return $this->hasMany(Precio::class, 'id_vehiculo', 'id_vehiculo');
    }
}
