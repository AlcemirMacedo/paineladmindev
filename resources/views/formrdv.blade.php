@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/dash-frame.css') }}">
@endsection

@section('content')

<section class="container">
    <h1 class="text-center" style="margin-top: 20px ">Formulário RDV</h1>
    <hr/>
    <form action="/editarrecibo" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Número</label>
                <input name="numero" class="form-control" type="text" >

            </div>
            <div class="form-group col-md-9">
                <label>Nome</label>
                <input name="nome" class="form-control" type="text">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-3">
                <label>CPF / CNPJ</label>
                <input name="cpfcnpj" class="form-control" type="text">
            </div>

            <div class="form-group col-md-9">
                <label>Valor R$</label>
                <input name="valor" class="form-control" id="valor" oninput="formatarMoeda(this)" type="text">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Descrição</label>
                <input name="decricao" class="form-control" type="text" >
            </div>
        </div>
        <button type="submit" class="btn btn-success" style="margin-right:10px">Salvar Alterações</button>
        <a class="btn btn-light" onclick="window.history.back()">Cancelar</a>
    </form>
</section>
<script> function formatarMoeda(input) {
    let valor = input.value.replace(/\D/g, '');
    valor = (valor / 100).toFixed(2) + '';
    valor = valor.replace(".", ",");
    valor = valor.replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
    input.value = valor; }
</script>
@endsection



