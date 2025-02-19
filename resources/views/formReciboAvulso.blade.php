@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/dash-frame.css') }}">
@endsection



@section('content')

<section class="container">
    <h1 class="text-center" style="margin-top: 20px ">Emitir Recibo Avulso</h1>
    <hr/>
    <form action="/emitirpdfavulso" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Número</label>
                <input name="numero" class="form-control" value="{{ $numRecibo }}" type="text">
            </div>
            <div class="form-group col-md-9">
                <label>Nome</label>
                <input name="nome" class="form-control" type="text">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-3">
                <label>CPF / CNPJ</label>
                <input maxlength="18" name="cpfcnpj" oninput="mascararDocumento(this)" class="form-control" type="text">
            </div>

            <div class="form-group col-md-9">
                <label>Valor R$</label>
                <input name="valor" class="form-control" id="valor" oninput="formatarMoeda(this)" type="text">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Descrição</label>
                <input name="descricao" class="form-control" type="text" >
            </div>
        </div>
        <button type="submit" class="btn btn-success" style="margin-right:10px">Emitir Recibo</button>
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

<script> function formatarMoeda(input) {
    let valor = input.value.replace(/\D/g, '');
    valor = (valor / 100).toFixed(2) + '';
    valor = valor.replace(".", ",");
    valor = valor.replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
    input.value = valor; }
</script>


@endsection



