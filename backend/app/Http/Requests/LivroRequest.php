<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LivroRequest extends FormRequest
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
            'Titulo' => 'required|string|max:40',
            'Editora' => 'required|string|max:40',
            'Edicao' => 'nullable|integer|min:1',
            'AnoPublicacao' => 'nullable|string|size:4|regex:/^[0-9]{4}$/',
            'Autor_CodAu' => 'nullable',
            'Assunto_codAs' => 'nullable',
            'preco' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'Titulo.required' => 'O campo Título é obrigatório.',
            'Titulo.max' => 'O campo Título não pode exceder 40 caracteres.',
            'Editora.required' => 'O campo Editora é obrigatório.',
            'Editora.max' => 'O campo Editora não pode exceder 40 caracteres.',
            'Edicao.integer' => 'O campo Edição deve ser um número inteiro.',
            'Edicao.min' => 'O campo Edição deve ser maior ou igual a 1.',
            'AnoPublicacao.size' => 'O campo Ano de Publicação deve ter 4 dígitos.',
            'AnoPublicacao.regex' => 'O campo Ano de Publicação deve ser um ano válido.',
            'preco.numeric' => 'O campo Preço deve ser numérico',
        ];
    }
}
