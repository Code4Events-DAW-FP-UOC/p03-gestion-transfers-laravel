<?php

namespace App\Http\Requests;

use App\Models\Hotel;
use App\Models\Precio;
use App\Models\TiposReserva;
use App\Models\Vehiculo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $tablaHoteles   = (new Hotel())->getTable();        // p3_transfer_hoteles
        $tablaVehiculos = (new Vehiculo())->getTable();     // p3_transfer_vehiculos
        $tablaTipos     = (new TiposReserva())->getTable(); // p3_transfer_tipos_reservas

        // Misma lógica que en StoreViajeroReservaRequest:
        $tipo = (int) $this->input('id_tipo_reserva');

        // Reglas comunes
        $rules = [
            'id_tipo_reserva' => [
                'required',
                'integer',
                Rule::exists($tablaTipos, 'id_tipo_reserva'),
            ],
            'id_hotel' => [
                'required',
                'integer',
                Rule::exists($tablaHoteles, 'id_hotel'),
            ],
            'num_viajeros' => [
                'required',
                'integer',
                'min:1',
            ],
            'id_vehiculo' => [
                'required',
                'integer',
                Rule::exists($tablaVehiculos, 'id_vehiculo'),
            ],
        ];

        // ----- Campos de IDA (tipo 1 o 3) -----
        if (in_array($tipo, [1, 3], true)) {
            $rules = array_merge($rules, [
                'fecha_entrada'        => ['required', 'date'],
                'hora_entrada'         => ['required', 'date_format:H:i'],
                'numero_vuelo_entrada' => ['required', 'string', 'max:50'],
                'origen_vuelo_entrada' => ['required', 'string', 'max:100'],
            ]);
        } else {
            $rules['fecha_entrada']        = ['nullable', 'date'];
            $rules['hora_entrada']         = ['nullable', 'date_format:H:i'];
            $rules['numero_vuelo_entrada'] = ['nullable', 'string', 'max:50'];
            $rules['origen_vuelo_entrada'] = ['nullable', 'string', 'max:100'];
        }

        // ----- Campos de VUELTA (tipo 2 o 3) -----
        if (in_array($tipo, [2, 3], true)) {
            $rules = array_merge($rules, [
                'fecha_vuelo_salida'   => ['required', 'date'],
                'hora_vuelo_salida'    => ['required', 'date_format:H:i'],
                'numero_vuelo_salida'  => ['required', 'string', 'max:50'],
                'destino_vuelo_salida' => ['required', 'string', 'max:100'],
            ]);
        } else {
            $rules['fecha_vuelo_salida']   = ['nullable', 'date'];
            $rules['hora_vuelo_salida']    = ['nullable', 'date_format:H:i'];
            $rules['numero_vuelo_salida']  = ['nullable', 'string', 'max:50'];
            $rules['destino_vuelo_salida'] = ['nullable', 'string', 'max:100'];
        }

        return $rules;
    }
}
