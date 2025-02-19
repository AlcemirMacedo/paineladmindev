<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class funcionarioRequest extends FormRequest
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
            'matricula' => 'required',
            'nome' => 'required',
            'cargo' => 'required',
            'cpf' => 'required|min:14',
            'endereco' => 'required',
            'email' => 'required',
            'contato' => 'required',
            'data_nasc' => 'required'
        ];
    }

    public function messages(){
        return [
            'matricula.required' => 'Campo Nome obrigatório',
            'nome.required' => 'Campo Nome obrigatório',
            'cargo.required' => 'Campo Cargo obrigatório',
            'cpf.required' => 'Campo CPF ou CNPJ obrigatório',
            'cpf.min' => 'CPF deve conter 14 caracteres',
            'endereco.required' => 'Campo Endereço obrigatório',
            'email.required' => 'Campo E-mail obrigatório',
            'contato.required' => 'Campo Contato obrigatório',
            'data_nasc.required' => 'Campo Data de Nascimento obrigatório'
        ];
    }

}
