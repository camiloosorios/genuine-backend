<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Asegúrate de que esto sea true
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:categories,name',
            'description' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.unique' => 'El nombre ya está en uso.',
            'description.required' => 'El campo descripción es obligatorio.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
        ];
    }
}