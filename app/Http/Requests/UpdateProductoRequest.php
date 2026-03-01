<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
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
            'codigo' => 'required|string|max:150|unique:productos,codigo,' . $this->route('producto'),
            'descripcion' => 'nullable|string',
            'unidad' => 'nullable|string|max:50',
            'cprodserv' => 'nullable|string|max:100',
        ];
    }
}
