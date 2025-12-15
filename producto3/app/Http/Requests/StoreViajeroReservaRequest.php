<?php

namespace App\Http\Requests;

use App\Models\Vehiculo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreViajeroReservaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //return auth()->check();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
            // Tipo seleccionado en el formulario (1=ida, 2=vuelta, 3=ida+vuelta)
        $tipo = (int) $this->input('id_tipo_reserva');

        // Reglas comunes a cualquier reserva
        $rules = [
            'id_tipo_reserva' => [
                'required',
                'integer',
                'exists:p3_transfer_tipos_reservas,id_tipo_reserva',
            ],
            'id_hotel' => [
                'required',
                'integer',
                'exists:p3_transfer_hoteles,id_hotel',
            ],
            'num_viajeros' => [
                'required',
                'integer',
                'min:1',
            ],
            'id_vehiculo' => [
                'required',
                'integer',
                'exists:p3_transfer_vehiculos,id_vehiculo',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Campos de IDA (tipo 1 o 3)
        |--------------------------------------------------------------------------
        */
        if (in_array($tipo, [1, 3], true)) {
            $rules = array_merge($rules, [
                'fecha_entrada'        => ['required', 'date'],
                'hora_entrada'         => ['required', 'date_format:H:i'],
                'numero_vuelo_entrada' => ['required', 'string', 'max:50'],
                'origen_vuelo_entrada' => ['required', 'string', 'max:100'],
            ]);
        } else {
            // Si llega algo igualmente, que tenga formato correcto
            $rules['fecha_entrada']        = ['nullable', 'date'];
            $rules['hora_entrada']         = ['nullable', 'date_format:H:i'];
            $rules['numero_vuelo_entrada'] = ['nullable', 'string', 'max:50'];
            $rules['origen_vuelo_entrada'] = ['nullable', 'string', 'max:100'];
        }

        /*
        |--------------------------------------------------------------------------
        | Campos de VUELTA (tipo 2 o 3)
        |--------------------------------------------------------------------------
        */
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
 
    public function messages(): array
    {
        return [
            'id_tipo_reserva.required' => 'Debes seleccionar un tipo de reserva.',
            'id_tipo_reserva.exists'   => 'El tipo de reserva seleccionado no es válido.',

            'id_hotel.required'        => 'Debes seleccionar un hotel.',
            'id_hotel.exists'          => 'El hotel seleccionado no es válido.',

            'num_viajeros.required'    => 'Debes indicar el número de viajeros.',
            'num_viajeros.integer'     => 'El número de viajeros debe ser un número entero.',
            'num_viajeros.min'         => 'El número de viajeros debe ser al menos 1.',

            'id_vehiculo.required'     => 'Debes seleccionar un vehículo.',
            'id_vehiculo.exists'       => 'El vehículo seleccionado no es válido.',

            // Ida
            'fecha_entrada.required'        => 'Debes indicar la fecha de llegada.',
            'fecha_entrada.date'            => 'La fecha de llegada no es válida.',
            'hora_entrada.required'         => 'Debes indicar la hora de llegada.',
            'hora_entrada.date_format'      => 'La hora de llegada debe tener el formato HH:MM.',
            'numero_vuelo_entrada.required' => 'Debes indicar el número de vuelo de llegada.',
            'origen_vuelo_entrada.required' => 'Debes indicar el origen del vuelo de llegada.',

            // Vuelta
            'fecha_vuelo_salida.required'   => 'Debes indicar la fecha de salida.',
            'fecha_vuelo_salida.date'       => 'La fecha de salida no es válida.',
            'hora_vuelo_salida.required'    => 'Debes indicar la hora de salida.',
            'hora_vuelo_salida.date_format' => 'La hora de salida debe tener el formato HH:MM.',
            'numero_vuelo_salida.required'  => 'Debes indicar el número de vuelo de salida.',
            'destino_vuelo_salida.required' => 'Debes indicar el destino del vuelo de salida.',
        ];
    }
}
