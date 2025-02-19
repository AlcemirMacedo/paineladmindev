<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class cadastroRequest extends FormRequest
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
            'fullname' => 'required',
            'usuario' => 'required',
            'email' => 'required|email',
            'senha' => 'required|min:6|max:10',
            'senhaconfirm' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'fullname.required' => '* Campo Nome Completo obrigatório!',
            'usuario.required' => '* Campo Usuário obrigatório!',
            'email.required' => '* Campo Email obrigatório!',
            'email.email' => '* Insira um Email válido!',
            'senha.required' => '* Campo Senha obrigatório!',
            'senha.min' => '* A senha deve conter no mínimo 6 caracteres e no máximo 11!',
            'senha.max' => '* A senha deve conter no mínimo 6 caracteres e no máximo 11!',
            'senhaconfirm.required' => '* Confirme sua senha'
        ];
    }
}
