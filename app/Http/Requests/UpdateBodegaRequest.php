<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBodegaRequest extends FormRequest
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
            'nombre' => 'required|string|max:150|unique:bodegas,nombre,' . $this->route('bodega'),
            'ubicacion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'encargado' => 'nullable|string|max:150',
            'inhabilitado' => 'boolean',
        ];
    }
}
