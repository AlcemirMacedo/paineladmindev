<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class cadastrarFornecedor extends FormRequest
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
            'nome' => 'required',
            'razaosocial' => 'required',
            'cpfcnpj' => 'required',
            'cidade' => 'required',
            'uf' => 'required|max:2',
            'email' => 'required',
            'telefone' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => '* Campo Nome obrigatório',
            'razaosocial.required' => '* Campo Razão Social obrigatório',
            'cpfcnpj.required' => '* Campo CPF ou CNPJ obrigatório',
            'cidade.required' => '* Campo Cidade obrigatório',
            'uf.required' => '* Campo UF obrigatório',
            'email.required' => '* Campo E-mail obrigatório',
            'telefone' => '* Campo Telefone obrigatório'
        ];
    }
}
