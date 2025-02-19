@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/dash-frame.css') }}">
@endsection

@section('content')

@if (Session::has('success'))
    <script>
        swal({
                title: "Sucesso",
                text: "{{ Session::get('success') }}",
                icon: "success"
            })
    </script>
@endif

@if (Session::has('error'))
    <script>
        swal({
                title: "error",
                text: "{{ Session::get('error') }}",
                icon: "error"
            })
    </script>
@endif

<div class="content col-md-12 dash-fornecedor">
    <h1>Usuários</h1>
    <hr>
    <div class="pesq-field">
        <form action="#" class="form-inline" method="GET">
            <a href="/cadastro" class="btn btn-success" style="margin-right: 10px">Cadastrar Usuário</a>
            {{-- <input type="text" name="search" class="form-control col-md-4" placeholder="Pesquisar por: Nome ou CPF ou CNPJ"> --}}
            {{-- <button type="submit" class="btn btn-secondary" style="margin-left: 10px">Pesquisar</button> --}}
            <span style="position: absolute; right:20px">Total de <code>{{ $contador }}</code> usuários cadastrados</span>
        </form>
    </div>
    <table class="table table-striped table-dark">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Login</th>
                <th scope="col">E-mail</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sql as $item)
                <form action="#" method="POST">
                    @csrf
                    <tr>
                        <th scope="row">
                            {{ $item->id_usuario }}<input type="hidden" placeholder="{{ $item->id_usuario }}" name="id" value="{{ $item->id_usuario }}" readonly>
                        </th>
                        <td>
                            {{ $item->nome }}
                            <input type="hidden" placeholder="{{ $item->nome }}" value="{{ $item->nome }}" name="numero" readonly>
                        </td>
                        <td>
                            {{ $item->login }}
                            <input type="hidden" placeholder="{{ $item->login }}"  value="{{ $item->login }}" name="cpfcnpj" readonly>
                        </td>
                        <td class="name_forn">{{ $item->email }}<input type="hidden" placeholder="{{ $item->email }}" value="{{ $item->email }}" name="nome" readonly></td>
                        <td>
                            <a href="/formrusuario/{{ $item->id_usuario }}" class="edit-bot" style="color: rgb(22, 141, 225)" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a> |
                            <a class="excluir-bot" title="Excluir" href="/excluirsuario/{{ $item->id_usuario }}">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                </form>
            @endforeach

        </tbody>

    </table>
</div>






@endsection
