@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/home-dash.css') }}">
@endsection

<style>
    body{
        display: flex;

        justify-content: center;
        align-items: flex-start;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    .content .cards-home{
        color: var(--branco);
        transition: all 0.3s;
        background-color: var(--fundo2);
    }
    .cards-home:hover{
        background-color: rgb(240, 240, 240);
        color: var(--fundo);
    }


</style>

@section('content')

        <div class="content">
            <h1 class="text-center" style="margin-top: 20px">Painel de Controle</h1>
            <hr>
            <div class="row col-md-12" style="margin-top: 20px">
                <div class=" col-md-4" style="margin-top: 20px;">
                    <div class="cards-home card" style="border-top:3px solid yellow ">
                        <div class="card-body">
                            <h5 class="card-title">Fornecedores</h5>
                            <hr>
                            <p class="card-text" style="font-weight: bold;">Último Registro: <code>{{ $ultimoRegistro }}</code>  </p>
                            <a href="/fornecedor" class="btn btn-primary">Ver todos | <span class="badge badge-light">{{ $count_fornecedores }}</span></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" style="margin-top: 20px">
                    <div class="cards-home card" style="border-top:3px solid orange ">
                        <div class="card-body" >
                            <h5 class="card-title">Recibos</h5>
                            <hr>
                            <p class="card-text" style="font-weight: bold;">Último Registro: <code>{{ $ultimoRegistro }}</code>  </p>
                            <a href="/gridrecibo" class="btn btn-primary">Ver todos | <span class="badge badge-light">{{ $count_recibo }}</span></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" style="margin-top: 20px">
                    <div class="cards-home card"  style="border-top:3px solid red ">
                        <div class="card-body">
                            <h5 class="card-title">Funcionários</h5>
                            <hr>
                            <p class="card-text">Temos um total de <code>{{ $count_funcionarios }}</code> funcionários registrados</p>
                            <a href="/listarfuncionarios" class="btn btn-primary">Ver todos | <code class="badge badge-light"> {{ $count_funcionarios }}</code></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" style="margin-top: 20px">
                    <div class="cards-home card"  style="border-top:3px solid red ">
                        <div class="card-body">
                            <h5 class="card-title">RDV</h5>
                            <hr>
                            <p class="card-text">Temos um total de <code>{{ $count_rdv }}</code> RDV's registrados</p>
                            <a href="/rdvlist" class="btn btn-primary">Ver todos | <code class="badge badge-light"> {{ $count_rdv }}</code></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" style="margin-top: 20px">
                    <div class="cards-home card"  style="border-top:3px solid green">
                        <div class="card-body">
                            <h5 class="card-title">Usuários</h5>
                            <hr>
                            <p class="card-text">Exitem um total de <code>{{ $countusuario }}</code> usuários cadastrados</p>
                            <a href="/usuarios" class="btn btn-primary">Ver todos | <span class="badge badge-light"> {{ $countusuario }}</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
