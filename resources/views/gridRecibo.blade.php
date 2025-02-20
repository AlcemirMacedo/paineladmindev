@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/dash-frame.css') }}">
@endsection

@section('content')
    <div class="dash-fornecedor">
        <h1>Recibos</h1>
        <hr>
        <div class="pesq-field">
            <form action="/searchrecibo" class="form-inline" method="GET">
                <a href="/formrecibo" class="btn btn-success" style="margin-right: 10px">Recibo Avulso</a>
                <input type="text" name="search" class="form-control col-md-4" placeholder="Pesquisar por: Nome ou CPF ou CNPJ">
                <button type="submit" class="btn btn-secondary" style="margin-left: 10px">Pesquisar</button>
                <span style="position: absolute; right:20px">Total de recibos emitidos: <code>{{ $total_recibo }}</code></span>
                <div class="form-group col-md-6">
                    <div id="cloader"></div>
                    <h5 class="text-success text-center" id="txt">Gerando Recibo</h5>
                </div>

                @if (Session::has('attention'))
                    <script>
                        swal({
                            title: "Erro de Pesquisa",
                            text: "{{ Session::get('attention') }}",
                            icon: "warning"
                        })
                    </script>
                @endif

            </form>
        </div>
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nº</th>
                    <th scope="col">CPF / CNPJ</th>
                    <th scope="col">NOME / EMPRESA</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Valor R$</th>
                    <th scope="col">Data</th>
                    <th scope="col" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sql as $item)
                    <form action="/baixarpdf" method="POST" onsubmit="showLoader()">
                        @csrf
                        @php
                            $valorFormatado = number_format($item->valor_recibo, 2, ',', '.');
                        @endphp
                        <tr>
                            <th scope="row">{{ $item->id_recibo }}<input type="hidden" placeholder="{{ $item->id_recibo }}" name="id" value="{{ $item->id_recibo }}" readonly></th>
                            <td>{{ $item->num_recibo }}<input type="hidden" placeholder="{{ $item->num_recibo }}" value="{{ $item->num_recibo }}" name="numero" readonly></td>
                            <td>{{ $item->cpfcnpj_recibo }}<input type="hidden" placeholder="{{ $item->cpfcnpj_recibo }}"  value="{{ $item->cpfcnpj_recibo }}" name="cpfcnpj" readonly></td>
                            <td class="name_forn">{{ $item->nome }}<input type="hidden" placeholder="{{ $item->nome }}" value="{{ $item->nome }}" name="nome" readonly></td>
                            <td class="desc_forn">{{ $item->desc_recibo }}<input title="{{ $item->desc_recibo }}" type="hidden" placeholder="{{ $item->desc_recibo }}" value="{{ $item->desc_recibo }}" name="descricao" readonly></td>
                            <td>{{ $valorFormatado }}<input type="hidden" placeholder="{{ $valorFormatado }}" value="{{ $valorFormatado }}" name="valor" readonly></td>
                            <td>{{ $item->data_recibo }}<input type="hidden" placeholder="{{ $item->data_recibo }}" value="{{ $item->data_recibo }}" name="data" readonly></td>
                            <input type="hidden" placeholder="{{ $item->vlr_extenso }}" value="{{ $item->vlr_extenso }}" name="vlr_extenso">
                            <td class="text-center">
                                @php
                                    if($item->status_recibo == 1 || $item->status_recibo == NULL){
                                        echo "<button title='Baixar' class='btn btn-success' type='submit'>
                                            <i class='bi bi-box-arrow-down'></i>
                                        </button>
                                        <a href='/formrecibo/$item->id_recibo'  class='btn btn-warning' title='Editar'>
                                            <i class='bi bi-pencil'></i>
                                        </a>";
                                    }
                                @endphp
                                {{-- <button title="Baixar" class="baixar-bot" type="submit" style="color: rgb(22, 186, 85)">
                                  <i class="bi bi-box-arrow-down"></i>
                                  </button>|
                                   <a href="/formrecibo/{{ $item->id_recibo }}" class="edit-bot" style="color: rgb(22, 141, 225)" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                    </a> | --}}
                                <a class="btn btn-danger" title="Excluir" href="#" onclick="confirmarAcao({{ $item->id_recibo }})">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </form>
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

    {{-- MODAL PARA SELECIONAR O CLIENTE DO RECIBO --}}
    <div class="modal fade" id="modal-select" tabindex="-1" aria-labelledby="modal-select" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-tittle">Selecione fornecedor</h4>
                    <button type="button" class="close" data-dismiss="modal"><i class="bi bi-x-circle-fill"></i></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post">

                    </form>
                    <table>
                        <tr>
                            <td>id</td>
                            <td>cpf/cnpj</td>
                            <td>Nome</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"> function confirmarAcao(id_recibo) {
        var confirmacao = confirm("Você tem certeza que deseja excluir o recibo com ID "+id_recibo+ " ?");
        if (confirmacao) {

            window.location.href="/excluirrecibo/"+id_recibo;
        }
    }
    </script>
@endsection




