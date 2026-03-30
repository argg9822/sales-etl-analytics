<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ImportStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'csv_file' => 'required|file|mimes:csv'
        ];
    }

    public function messages(): array
    {
        return [
            'csv_file.required' => 'Por favor, selecciona un archivo CSV para importar.',
            'csv_file.file' => 'El archivo debe ser un archivo válido.',
            'csv_file.mimes' => 'El archivo no cumple con las características requeridas. Asegúrate de que el archivo sea un CSV.'
        ];
    }
}
