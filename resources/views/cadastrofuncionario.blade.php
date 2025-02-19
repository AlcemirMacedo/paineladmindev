@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/dash-frame.css') }}">
    <style>
        body{
            padding-top: 50px;
        }
        input{
            text-transform: none;
        }
    </style>
@endsection

@section('content')

@if (Session::has('error'))
    <script>
        swal({
            title: "Mensagem de erro",
            text: "{{ Session::get('error') }}",
            icon: "error"
        })
    </script>
@endif

@foreach ($sql as $item)

@endforeach

<div class="container">
    <h1 class="text-center">Cadastro de Funcionários</h1>
    <hr>
    <form action="/cadastrofuncionario" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Matrícula</label>
                <input type="hidden" name="id" value="{{ @$item->id_funcionario }}">
                <input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="matricula" value="{{ old('matricula', @$item->matricula_funcionario) }}">
            </div>
            <div class="form-group col-md-5">
                <label>Nome</label>
                <input class="form-control" type="text" name="nome" value="{{ old('nome', @$item->nome_funcionario) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Cargo</label>
                <input class="form-control" type="text" name="cargo" value="{{ old('cargo', @$item->cargo_funcionario)}}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-3">
                <label>CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14" value="{{ old('cpf', @$item->cpf_funcionario) }}">
            </div>
            <div class="form-group col-md-9">
                <label>Endereço</label>
                <input class="form-control" type="text" name="endereco" value="{{ old('endereco', @$item->end_funcionario) }}" >
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Email</label>
                <input class="form-control" type="email" name="email" value="{{ old('email', @$item->email_funcionario) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Contato</label>
                <input class="form-control" type="text" name="contato" value="{{ old('contato', @$item->contato_funcionario) }}" oninput="mascaraTelefone(this)">
            </div>
            <div class="form-group col-md-4">
                <label>Data de Nascimento</label>
                <td><input type="date" class="form-control" value="{{ @$item->data_nasc }}" name="data_nasc"></td>
            </div>
        </div>

        <div class="form-row">
            <button type="submit" class="btn btn-success col-md-2">Salvar</button>
            <a onclick="window.history.back()" class="btn btn-light col-md-2" style="margin-left: 10px">Voltar</a>
        </div>
    </form>
</div>

@if ($errors -> any())
    <script>
        swal({
            title: "Mensagem de erro",
            text: "{{ implode('\n', $errors->all()) }}",
            icon: "error"
        })
    </script>
@endif

{{-- Máscara de CPF --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cpfInput = document.getElementById("cpf");

        cpfInput.addEventListener("input", function () {
            let value = cpfInput.value;
            value = value.replace(/\D/g, ""); // Remove tudo que não for número

            // Aplica a máscara: 000.000.000-00
            value = value.replace(/^(\d{3})(\d)/, "$1.$2");
            value = value.replace(/^(\d{3})\.(\d{3})(\d)/, "$1.$2.$3");
            value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, "$1.$2.$3-$4");

            cpfInput.value = value;
        });
    });
</script>

{{-- Máscara de CEP --}}
<script>

        function mascaraCep(input) {
            // Remove qualquer caractere que não seja um número
            let cep = input.value.replace(/\D/g, '');

            // Aplica a máscara de CEP
            if (cep.length > 5) {
                cep = cep.slice(0, 5) + '-' + cep.slice(5);
            }

            // Atualiza o valor do input com o CEP mascarado
            input.value = cep;
        }


</script>

{{-- Máscara de Telefone --}}
<script>
    function mascaraTelefone(input) {
        // Remove qualquer caractere que não seja um número
        let telefone = input.value.replace(/\D/g, '');

        // Aplica a máscara de telefone
        if (telefone.length > 10) {
            telefone = telefone.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3");
        } else if (telefone.length > 5) {
            telefone = telefone.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3");
        } else if (telefone.length > 2) {
            telefone = telefone.replace(/^(\d{2})(\d{0,5})/, "($1) $2");
        } else if (telefone.length > 0) {
            telefone = telefone.replace(/^(\d{0,2})/, "($1");
        }

        // Atualiza o valor do input com o telefone mascarado
        input.value = telefone;
    }
</script>


@endsection
