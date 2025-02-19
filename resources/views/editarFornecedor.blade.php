@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/dash-frame.css') }}">
@endsection

@section('content')

@foreach ($sql as $item)

@endforeach

<section class="container">
    <h1 class="text-center" style="margin-top: 20px "><i class="bi bi-pencil"></i> Editar Fornecedor</h1>
    <hr/>
    <form action="/editarfornecedor" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>CPF / CNPJ</label>
                <input maxlength="18" oninput="mascararDocumento(this)" class="form-control" type="text" name="cpfcnpj" value="{{ $item->cpfcnpj }}">
                <input type="hidden" name="id" value="{{ $item->id_fornecedores }}">
            </div>
            <div class="form-group col-md-8">
                <label>Nome</label>
                <input class="form-control" type="text" name="nome" value="{{ $item->nome }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Raão Social</label>
                <input class="form-control" type="text" name="razaosocial" value="{{ $item->razaosocial }}">
            </div>
            <div class="form-group col-md-6">
                <label>Endereço</label>
                <input class="form-control" type="text" name="endereco" value="{{ $item->endereco }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Bairro</label>
                <input class="form-control" type="text" name="bairro" value="{{ $item->bairro }}">
            </div>
            <div class="form-group col-md-4">
                <label>Cidade</label>
                <input id="cidade" class="form-control" type="text" name="cidade" value="{{ $item->cidade }}">
            </div>
            <div class="form-group col-md-2">
                <label>UF</label>
                <input id="uf" class="form-control" type="text" name="uf" value="{{ $item->uf }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Cep</label>
                <input id="cep" class="form-control" type="text" name="cep" value="{{ $item->cep }}">
            </div>
            <div class="form-group col-md-4">
                <label>E-mail</label>
                <input class="form-control" type="text" name="email" value="{{ $item->email }}" style="text-transform:lowercase">
            </div>
            <div class="form-group col-md-4">
                <label>telefone</label>
                <input class="form-control" type="text" name="telefone" value="{{ $item->telefone }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Tipo</label>
                <input class="form-control" type="text" name="tipo_pessoa" value="{{ $item->tipo_pessoa }}">
            </div>
        </div>
        <button type="submit" class="btn btn-success" style="margin-right: 10px">Salvar Alterações</button>
        <a class="btn btn-light" onclick="window.history.back()">Voltar</a>
    </form>
</section>


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




@endsection




