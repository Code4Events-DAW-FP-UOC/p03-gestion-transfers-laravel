<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Autorizar esta petición.
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
        $user = $this->user();

        $rules = [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:150', Rule::unique(User::class)->ignore($user->id)],
        ];

        if ($user->isViajero()) {
            $rules = array_merge($rules, [
                'nombre'        => ['required', 'string', 'max:100'],
                'apellido1'     => ['required', 'string', 'max:100'],
                'apellido2'     => ['nullable', 'string', 'max:100'],
                'direccion'     => ['required', 'string', 'max:150'],
                'codigo_postal' => ['required', 'string', 'max:20'],
                'ciudad'        => ['required', 'string', 'max:100'],
                'pais'          => ['required', 'string', 'max:100'],
                'telefono'      => ['nullable', 'string', 'max:50'],
            ]);
        }

        if ($user->isHotel()) {
            $rules = array_merge($rules, [
                'nombre'   => ['required', 'string', 'max:150'],
                'telefono' => ['nullable', 'string', 'max:50'],
                'id_zona'  => ['required', 'integer', 'exists:p3_transfer_zonas,id_zona'],
            ]);
        }

        return $rules;
    }
    // public function rules(): array
    // {
    //     return [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => [
    //             'required',
    //             'string',
    //             'lowercase',
    //             'email',
    //             'max:255',
    //             Rule::unique(User::class)->ignore($this->user()->id),
    //         ],
    //     ];
    // }
}
