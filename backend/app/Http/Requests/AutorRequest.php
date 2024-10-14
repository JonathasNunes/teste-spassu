<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutorRequest extends FormRequest
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
            'Nome' => 'required|string|max:40',
        ];
    }

    /**
     * Mensagens de erro personalizadas para as validações.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'Nome.required' => 'O campo Nome é obrigatório.',
            'Nome.max' => 'O campo Nome não pode exceder 40 caracteres.',
        ];
    }
}
