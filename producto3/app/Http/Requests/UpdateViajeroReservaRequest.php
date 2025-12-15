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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_hotel_destino'  => ['required', 'integer', 'exists:p3_tansefer_hoteles,id_hotel'],
            'id_vehiculo'       => ['required', 'integer', 'exists:p3_transfer_vehiculos,id_vehiculo'],
            'id_tipo_reserva'   => ['required', 'integer', 'exists:p3_transfer_tipos_reserva,id_tipo_reserva'],
            'id_precio'         => ['required', 'integer', 'exists:p3_transfer_precios,id_precio'],
            'fecha_entrada'     => ['required', 'date', 'affter:now + 48 hours'],
            'hora_entrada'      => ['nullable', 'date_format:H:i'],
            'num_viajeros'      => ['required', 'integer', 'min:1'],
        ];
    }
}
