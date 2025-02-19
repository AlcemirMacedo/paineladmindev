@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/dash-frame.css') }}">
@endsection

@section('content')
    @if(Session::has('success'))
        <script>
            swal({
            title: "Tudo ceto!",
            text: "{{ Session::get('success') }}",
            icon: "success"
            })
        </script>
    @endif

    <div class="dash-fornecedor">
        <h1>Funcionários</h1>
        <hr>
        <div class="pesq-field">
            <form action="/searchrecibo" class="form-inline" method="GET">
                {{-- eu pus a porrra do zero para fazer cadastrar ou editar --}}
                <a href="/cadastrofuncionario/{{ 0 }}" class="btn btn-success" style="margin-right: 10px">Cadastrar Funcionário</a>
                {{-- <input type="text" name="search" class="form-control col-md-4" placeholder="Pesquisar por: Nome ou CPF ou CNPJ"> --}}
                {{-- <button type="submit" class="btn btn-secondary" style="margin-left: 10px">Pesquisar</button> --}}
                <span style="position: absolute; right:20px">Total de <code>{{ $count_funcionarios }}</code> funcionários cadastrados</span>
            </form>
        </div>
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Matrícula</th>
                    <th scope="col">CPF</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Cargo</th>
                    <th scope="col">Contato</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sql as $item)
                    <form action="#" method="POST" onsubmit="showLoader()">
                        @csrf
                        <tr>
                            <th scope="row">{{ $item->id_funcionario }}<input type="hidden" placeholder="{{ $item->id_funcionario }}" name="id" value="{{ $item->id_funcionario }}" readonly></th>
                            <td class="desc_forn">{{ $item->matricula_funcionario }}<input title="{{ $item->matricula_funcionario }}" name="matricula" type="hidden" placeholder="{{ $item->matricula_funcionario }}" value="{{ $item->matricula_funcionario}}" name="matricula" readonly></td>
                            <td >{{ $item->cpf_funcionario }}<input type="hidden" placeholder="{{ $item->cpf_funcionario }}" value="{{ $item->cpf_funcionario }}" name="cpf" readonly></td>
                            <td>{{ $item->nome_funcionario}}<input type="hidden" placeholder="{{ $item->nome_funcionario }}" value="{{ $item->nome_funcionario }}" name="nome" readonly></td>
                            <td>{{ $item->cargo_funcionario }}<input type="hidden" placeholder="{{ $item->cargo_funcionario }}"  value="{{ $item->cargo_funcionario }}" name="cargo" readonly></td>
                            <td>{{ $item->contato_funcionario }}<input type="hidden" placeholder="{{ $item->contato_funcionario }}" value="{{ $item->contato_funcionario }}" name="contato" readonly></td>
                            <td><input type="hidden" placeholder="{{ $item->email_funcionario }}" value="{{ $item->email_funcionario }}" name="email" readonly></td>
                            <td><input type="hidden" placeholder="{{ $item->end_funcionario }}" value="{{ $item->end_funcionario }}" name="endereco" readonly></td>
                            <td><input type="hidden" placeholder="{{ $item->contato_funcionario }}" value="{{ $item->contato_funcionario }}" name="contato" readonly></td>
                            <td><input type="hidden" placeholder="{{ $item->data_nasc }}" value="{{ $item->data_nasc }}" name="data_nasc" readonly></td>

                            {{-- <td>{{ $item->data_admissao }}<input type="hidden" placeholder="{{ $item->data_admissao }}" value="{{ $item->data_admissao }}" name="valor" readonly></td>
                            <td>{{ $item->data_demissao}}<input type="hidden" placeholder="{{ $item->data_demissao}}" value="{{ $item->data_demissao}}" name="data" readonly></td>
                            <td>{{ $item->data_nasc}}<input type="hidden" placeholder="{{ $item->data_nasc}}" value="{{ $item->data_nasc}}" name="data" readonly></td> --}}
                            <td>
                                <a href="/cadastrofuncionario/{{ $item->id_funcionario }}" class="edit-bot" style="color: rgb(22, 141, 225)" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a> |
                                <a class="excluir-bot" title="Excluir" href="#" onclick="confirmarAcao({{ $item->id_funcionario }})">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </form>
                @endforeach
            </tbody>
        </table>
        @php
            if($count_funcionarios ==0 ){
                echo "<div class='text-center'>
                        <i style='font-size:60pt' class='bi bi-emoji-frown'></i>
                        <br>
                        <h3 style='margin:5px 0 20px 0'>Você ainda não tem nenhum<br> funcionário cadastrado</h3>
                        <a href='/cadastrofuncionario/{{ 0 }}' class='btn btn-success' style='margin-right: 10px'>Cadastrar Funcionário</a>
                    </div>";
            }
        @endphp
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

            window.location.href="/exluirfucionario/"+id_fornecedor;
        }
    }
    </script>
@endsection




