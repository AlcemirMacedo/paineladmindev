<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class novordvRequest extends FormRequest
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
            'responsavel' => 'required|exists:tb_funcionarios,id_funcionario',
            'via' => 'required',
            'data' => 'required',
        ];
    }

    public function messages()
    {
        return[
            'responsavel.required' => 'ESelecione um responsável',
            'via.required' => 'Escolha uma das opções de Via',
            'data.required' => 'Preencha a data da viagem',
        ];
    }
}
