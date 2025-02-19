@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/dash-frame.css') }}">
@endsection

@foreach ($sql as $item)
@endforeach

@section('content')
<div class="dash-fornecedor container">
    <h1 class="text-center">Emitir Recibo para:</h1>
    <form action="/gerarpdf" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group  col-md-12">
                <label for="nome">Nome:</label>
                <input class="form-control" type="text" name="nome" readonly value="{{ $item->nome }}" placeholder="{{ $item->nome }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group  col-md-12">
                <label for="nome">CNPJ / CPF:</label>
                <input class="form-control" type="text" name="cpfcnpj" readonly value="{{ $item->cpfcnpj }}" placeholder="{{ $item->cpfcnpj }}">
            </div>
            <div class="form-group  col-md-12">
                <label for="descricao">Descrição:</label>
                <input class="form-control" type="text" name="descricao" placeholder="Editar Descrição" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="valor">Valor R$:</label>
                <input id="valor" oninput="formatarMoeda(this)" placeholder="0,00" class="form-control" name="valor" type="text">
            </div>
        </div>

        <button class="btn btn-success btn-lg" type="submit">Emitir Recibo</button>
        <a href="/fornecedor" class="btn btn-light btn-lg" style="margin-left: 10px" >Voltar</a>
    </form>

    <script> function formatarMoeda(input) {
        let valor = input.value.replace(/\D/g, '');
        valor = (valor / 100).toFixed(2) + '';
        valor = valor.replace(".", ",");
        valor = valor.replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
        input.value = valor; }
    </script>
</div>
@endsection



