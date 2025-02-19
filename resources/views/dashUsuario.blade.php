@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset("css/dash-usuario.css") }}">

@endsection
@section('content')

@foreach ($usuario as $item)

@endforeach

<header>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" role="navigation">
        <figure  class="navbar-brand">
            <a href="/"><img class="img-fluid" src="{{ asset('img/logo-w.png') }}" alt=""></a>
            <h4>Painel Administrativo</h4>
        </figure>

        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#mycollapse" aria-controls="mycollapse" aria-expanded="false">
            <i class="bi bi-list"></i>
        </button>
        <div class="collapse navbar-collapse" id="mycollapse">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a href="{{ '/home' }}" target="myframe" class="nav-link active">Home</a>
                </li>
                <li class="nav-item">
                    <a href="{{ '/fornecedor' }}" target="myframe" class="nav-link">Fornecedor</a>
                </li>
                <li class="nav-item">
                    <a href="{{ '/gridrecibo' }}" target="myframe" class="nav-link">Recibos</a>
                </li>
                <li class="nav-item">
                    <a href="{{ '/listarfuncionarios' }}" target="myframe" class="nav-link">Funcionarios</a>
                </li>
                <li class="nav-item">
                    <a href="{{ '/rdvlist' }}" target="myframe" class="nav-link">RDV</a>
                </li>
                <li class="nav-item">
                    <a href="{{ '/usuarios' }}" target="myframe" class="nav-link">Usuários</a>
                </li>
            </ul>
        </div>
        <div class="usuario-info">
            <div class="usuario-nome">
                {{ $item->nome }}
            </div>
            <figure>
                <img src="{{ asset('img/user-icon.svg') }}" alt="{{ $item->nome }}">
            </figure>
            <a href="/logout" class="nav-link  sair">Sair</a>
        </div>

    </nav>
</header>

<iframe id="myFrame" name="myframe" src="/home" frameborder="0"></iframe>


@endsection
