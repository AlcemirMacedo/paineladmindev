@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/dash-frame.css') }}">
@endsection

@foreach ($sql as $item)

@endforeach

@section('content')

{{-- @if (Session::has('error'))
    <script>
        swal()
    </script>
@endif --}}

<section class="container">
    <h1 class="text-center" style="margin-top: 20px "><i class="bi bi-pencil"></i> Editar Recibo</h1>
    <hr/>
    <form action="/editarrecibo" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Número</label>
                <input name="numero" class="form-control" type="text" placeholder="{{ $item->num_recibo }}" value="{{ $item->num_recibo }}" readonly>
                <input type="hidden" name="id" value="{{ $item->id_recibo }}">
            </div>
            <div class="form-group col-md-9">
                <label>Nome</label>
                <input name="nome" class="form-control" type="text" placeholder="{{ $item->nome }}" value="{{ $item->nome }}" readonly>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-3">
                <label>CPF / CNPJ</label>
                <input name="cpfcnpj" class="form-control" type="text" placeholder="{{ $item->cpfcnpj }}" value="{{ $item->cpfcnpj }}" readonly>
            </div>
            @php
                $valorFormatado = number_format($item->valor_recibo, 2, ',', '.');
            @endphp
            <div class="form-group col-md-9">
                <label>Valor R$</label>
                <input name="valor" class="form-control" id="valor" oninput="formatarMoeda(this)" type="text" value="{{ $valorFormatado }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Descrição</label>
                <input name="decricao" class="form-control" type="text" value="{{ $item->desc_recibo }}">
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



