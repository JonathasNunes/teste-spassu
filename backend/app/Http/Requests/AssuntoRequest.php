<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssuntoRequest extends FormRequest
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
            'Descricao' => 'required|string|max:40',
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
            'Descricao.required' => 'O campo Descrição é obrigatório.',
            'Descricao.max' => 'O campo Descrição não pode exceder 40 caracteres.',
        ];
    }
}
