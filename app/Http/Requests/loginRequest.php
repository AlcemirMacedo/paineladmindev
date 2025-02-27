<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class loginRequest extends FormRequest
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
            'usuario' => 'required',
            'senha' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'usuario.required' => 'Campo de Usuário obrigatório',
            'senha.required' => 'Campo Senha obrigatório'
        ];
    }
}
