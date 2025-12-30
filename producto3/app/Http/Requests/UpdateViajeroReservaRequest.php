<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateViajeroReservaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_hotel'          => 'required|exists:p3_transfer_hoteles,id_hotel', 
            'id_tipo_reserva'   => 'required|integer',
            'id_vehiculo'       => 'required|exists:p3_transfer_vehiculos,id_vehiculo',
            'num_viajeros'      => 'required|integer|min:1',
            'fecha_entrada'        => 'nullable|date',
            'hora_entrada'         => 'nullable',
            'numero_vuelo_entrada' => 'nullable',
            'origen_vuelo_entrada' => 'nullable',
        
            'fecha_vuelo_salida'   => 'nullable|date',
            'hora_vuelo_salida'    => 'nullable',
            'numero_vuelo_salida'  => 'nullable',
            'destino_vuelo_salida' => 'nullable',
        ];
    }
}
