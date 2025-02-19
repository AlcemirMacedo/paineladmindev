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

@if (Session::has('success'))
    <script>
        swal({
            title: "Tudo certo!",
            text: "{{ Session::get('success') }}",
            icon: "success"
        })
    </script>
@endif

@if (Session::has('error'))
    <script>
        swal({
            title: "Mensagem de erro",
            text: "{{ Session::get('error') }}",
            icon: "error"
        })
    </script>
@endif
<div class="container">
    <h1 class="text-center">Cadastro de Fornecedores</h1>
    <hr>
    <form action="/cadastrarfornecedor" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Nome</label>
                <input class="form-control" type="text" name="nome" value="{{ old('nome') }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Razão Social</label>
                <input class="form-control" type="text" name="razaosocial" value="{{ old('razaosocial') }}" >
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>CPF ou CNPJ</label>
                <input class="form-control" maxlength="18" oninput="mascararDocumento(this)" type="text" name="cpfcnpj" value="{{ old('cpfcnpj') }}" >
            </div>
            <div class="form-group col-md-8">
                <label>Endereço</label>
                <input class="form-control" type="text" name="endereco" value="{{ old('endereco') }}" >
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Bairro</label>
                <input class="form-control" type="text" name="bairro" value="{{ old('bairro') }}">
            </div>
            <div class="form-group col-md-4">
                <label>Cidade</label>
                <input class="form-control" type="text" name="cidade" value="{{ old('cidade') }}">
            </div>
            <div class="form-group col-md-1">
                <label>UF</label>
                <input class="form-control" type="text" name="uf" maxlength="2" value="{{ old('uf') }}">
            </div>
            <div class="form-group col-md-3">
                <label>CEP</label>
                <input class="form-control" type="texto" maxlength="9" oninput="mascaraCep(this)" name="cep" value="{{ old('cep') }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Email</label>
                <input class="form-control" type="email" name="email" value="{{ old('email') }}">
            </div>
            <div class="form-group col-md-4">
                <label>Tipo Pessoa</label>
                <select name="tipo" class="form-control">
                    <option value="cpf">CPF</option>
                    <option value="cnpj">CNPJ</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Contato</label>
                <input class="form-control" type="text" name="telefone" value="{{ old('value') }}" oninput="mascaraTelefone(this)">
            </div>
        </div>

        <div class="form-row">
            <button type="submit" class="btn btn-success col-md-2">Cadastrar</button>
            <a href="/fornecedor" class="btn btn-light col-md-2" style="margin-left: 10px">Cancelar</a>
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

    
{{-- Máscara de CPF e CNPJ --}}
<script> function mascararDocumento(input)
    {
        let value = input.value.replace(/\D/g, '');
        if (value.length <= 11)
            {
                input.value = value.replace(/(\d{3})(\d)/, '$1.$2') .replace(/(\d{3})(\d)/, '$1.$2') .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
        else
            {
                input.value = value.replace(/^(\d{2})(\d)/, '$1.$2') .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3') .replace(/\.(\d{3})(\d)/, '.$1/$2') .replace(/(\d{4})(\d{1,2})$/, '$1-$2');
            }
    }
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
