@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/dash-frame.css') }}">
@endsection

@section('content')
    <div class="dash-fornecedor">
        <h1>Fornecedores</h1>
        <hr>
        <div class="pesq-field">
            <form  action="/searchfornecedor" method="GET" class="form-inline">
                {{-- @csrf --}}
                <a href="/cadastrofornecedor" class="btn btn-success" style="margin-right: 10px">Cadastrar Fornecedor</a>
                <input type="text" class="form-control col-md-4" name="search" placeholder="Pesquisar por: Nome ou CPF ou CNPJ">
                <button type="submit" class="btn btn-secondary" style="margin-left: 10px">Pesquisar</button>
                <span style="position: absolute; right:20px">Total de fornecedores: <code>{{ $count_fornecedor }}</code></span>
            </form>
        </div>
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">CPF / CNPJ</th>
                    <th scope="col">Cidade</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sql as $item)
                    <tr>
                        <th scope="row">{{ $item->id_fornecedores }}</th>
                        <td class="name_forn">{{ $item->nome }}</td>
                        <td>{{ $item->cpfcnpj }}</td>
                        <td>{{ $item->cidade }}</td>
                        <td>{{ $item->telefone }}</td>
                        <td>{{ $item->email }}</td>
                        <td>
                            <a title="Editar Fornecedor" href="/editarfornecedor/{{ $item->id_fornecedores }}"><i class="bi bi-pencil"></i></a>
                             <a title="Emitir Recibo" href="/emitirrecibo/{{ $item->id_fornecedores }}"><i class="bi bi-file-text"></i></a>
                             <a title="Excluir Fornecedor" href="#" onclick="confirmarAcao({{ $item->id_fornecedores }})"><i class="bi bi-trash3"></i></a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        @php
        $paginator = $sql
    @endphp
    @if ($paginator->hasPages())
        <ul class="pagination"> {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true"><span>&laquo; Anterior</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Anterior</a></li>
            @endif {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">Próximo &raquo;</a></li>
            @else
                <li class="disabled" aria-disabled="true"><span>Próximo &raquo;</span></li>
            @endif
        </ul>
    @endif

    </div>

    <script type="text/javascript"> function confirmarAcao(id_fornecedor) {
            var confirmacao = confirm("Você tem certeza que deseja excluir esse fornecedor com ID "+id_fornecedor+ " ?");
            if (confirmacao) {

                window.location.href="/excluirfornecedor/"+id_fornecedor;
            }
        }
    </script>
@endsection
