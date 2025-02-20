@extends('layouts.main_layout')

@section('links')
    <script src="https://cdn.jsdelivr.net/npm/cleave.js/dist/cleave.min.js"></script>
    <style>
        /* Estilizando os inputs */
        input:focus {
            outline: none; /* Remove a borda azul */
            box-shadow: none; /* Remove qualquer sombra padrão */
        }
    </style>

@endsection

@section('content')

@if ($errors -> any())
        <script>
            swal({
                title: "Mensagem de erro",
                text: "{{ implode('\n', $errors->all()) }}",
                icon: "error"
            })
        </script>
@endif

<div class="container" >

    <form action="/add" method="post">
         @csrf
         <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Número RDV</th>
                    <th scope="col">Responsável</th>
                    <th scope="col">Justificativa</th>
                    <th scope="col">Equipe</th>
                    <th scope="col">via</th>
                    <th scope="col">Data da Viagem</th>
                    <th scope="col">Emissão</th>
                    <th scope="col">Eções</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($selectJoin as $item)
                @php
                    $data = new DateTime($item->data_viagem);
                    $databr = $data->format('d/m/Y');
                @endphp
                <tr>
                    <td><input class="num-rdv" type="text" style="border: none; background:transparent; color:#ffffff;" readonly value="{{ @$item->num_rdv }} {{ old('numrdv') }}"></td>
                    <td>{{ $item->nome_funcionario }}</td>
                    <td>{{ $item->justificativa }}</td>
                    <td>{{ $item->equipe }}</td>
                    <td>{{ $item->via }}</td>
                    <td>{{ $databr }}</td>
                    <td>{{ $item->created_at}}</td>
                    <td>
                        <a href="/editarrdv/{{ $item->id }}" class="btn btn-warning text-white" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @endforeach

            </tbody>
         </table>

        <input type="hidden" name="numrdv" value="{{ @$item->num_rdv }}">
        <input type="hidden" name="idrdv" value="{{ @$item->id }}">
        <input type="hidden" name="nome" value="{{ @$item->nome_funcionario }}">
        <input type="hidden" name="via" value="{{ @$item->via }}">

        <hr style="border: red 1px solid">


        <h2>Adicionar itens a este RDV</h2>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Descrição</label>
                <input class="form-control" name="descricao" type="text" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-1">
                <label>Valor R$</label>
                <input class="form-control" name="valor" type="text" id="valor" oninput="formatarValor(event)" required>
            </div>
            <div class="form-group col-md-1">
                <label>Quantidade</label>
                <input class="form-control " name="quantidade" type="number" placeholder="0" id="quantidade" oninput="calcularResultado()" required>
            </div>
            <div class="form-group col-md-2">
                <label>Total R$ <i class="bi bi-arrow-down"></i></label>
                <input class="form-control" name="total" type="text" style="background-color: transparent; border:none; box-shadow:none; cursor:default" id="resultado" readonly>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Observação</label>
                <input class="form-control" name="obs" type="text">
            </div>
        </div>

        <button type="submit" class="btn btn-success" title="Adicionar">Adicionar <i class="bi bi-chevron-double-down"></i></i></button>
        <a href="/rdvlist" class="btn btn-danger" title="Sair">Sair</a>

    </form>

    <hr style="border: rgb(177, 177, 177) 1px solid">
    <form action="/gerarrdv" method="POST">
        @csrf
        @foreach ($selectJoin as $item)
            <input type="hidden" name="numrdv" value="{{ @$item->num_rdv }}">
            <input type="hidden" name="idrdv" value="{{ @$item->id }}">
            <input type="hidden" name="nome" value="{{ @$item->nome_funcionario }}">
            <input type="hidden" name="via" value="{{ @$item->via }}">
            <input type="hidden" name="data" value="{{ @$item->data_viagem }}">
            <input type="hidden" name="hora" value="{{ @$item->hora }}">
            <input type="hidden" name="funcao" value="{{ @$item->cargo_funcionario }}">
            <input type="hidden" name="justificativa" value="{{ @$item->justificativa }}">
            <input type="hidden" name="equipe" value="{{ @$item->equipe }}">
            <input type="hidden" name="ope" value="{{ @$item->operacao }}">
            <input type="hidden" name="created_at" value="{{ @$item->created_at }}">
        @endforeach

        @foreach ($selectItens as $itemrdv)
            <input type="hidden" name="descricao" value="{{ @$itemrdv->descricao }}">
            <input type="hidden" name="idrdv" value="{{ @$itemrdv->rdv_id }}">
            <input type="hidden" name="id" value="{{ @$itemrdv->id }}">
        @endforeach

        <button type="submit" class="btn btn-warning" title="Gerar PDF">Gerar PDF</button>
     </form>
    <h3>Itens do RDV</h3>
    <form action="excluiritem" method="post">
        @csrf
        @foreach ($selectJoin as $item)
            <input type="hidden" name="numrdv" value="{{ @$item->num_rdv }}">
            <input type="hidden" name="idrdv" value="{{ @$item->id }}">
            <input type="hidden" name="nome" value="{{ @$item->nome_funcionario }}">
            <input type="hidden" name="via" value="{{ @$item->via }}">
            <input type="hidden" name="data" value="{{ @$item->data_viagem }}">
            <input type="hidden" name="hora" value="{{ @$item->hora }}">
            <input type="hidden" name="funcao" value="{{ @$item->funcao }}">
            <input type="hidden" name="justificativa" value="{{ @$item->justificativa }}">
            <input type="hidden" name="equipe" value="{{ @$item->equipe }}">
            <input type="hidden" name="ope" value="{{ @$item->operacao }}">
            <input type="hidden" name="created_at" value="{{ @$item->created_at }}">
        @endforeach

        @foreach ($selectItens as $itemrdv)
            <input type="hidden" name="descricao" value="{{ @$itemrdv->descricao }}">
            <input type="hidden" name="idrdvfk" value="{{ @$itemrdv->rdv_id }}">
            <input type="hidden" name="id" value="{{ @$itemrdv->id }}">
        @endforeach
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Valor R$</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Total</th>
                    <th scope="col">Observações</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $contador = 1;
                @endphp
                @foreach ($selectItens as $itemrdv)
                    <tr>
                        <td>{{ $contador }}</td>
                        <td>{{ @$itemrdv->descricao }}</td>
                        <td>{{ @$itemrdv->valor }}</td>
                        <td>{{ @$itemrdv->quantidade }}</td>
                        <td>{{ @$itemrdv->valor_total }}</td>
                        <td>{{ @$itemrdv->observacao}}</td>
                        <td>
                            <button title="Excluir" class="btn btn-danger" type="submit" onclick="confirmarAcao({{ $item->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @php
                        $contador++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </form>



</div>


<script>
    function formatarValor(event) {
        var elemento = event.target;
        var valor = elemento.value;

        // Remove todos os caracteres que não são números
        valor = valor.replace(/\D/g, "");
        valor = valor.replace(/^0+/, ''); // Remove zeros à esquerda

        // Formata o valor
        if (valor.length > 2) {
            valor = valor.slice(0, valor.length - 2) + ',' + valor.slice(-2);
        }

        valor = valor.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

        elemento.value = valor;

        // Atualiza o cálculo do resultado
        calcularResultado();
    }

    function calcularResultado() {
        var valor = document.getElementById('valor').value.replace(/\./g, '').replace(',', '.');
        var quantidade = document.getElementById('quantidade').value;
        var resultado = parseFloat(valor) * parseInt(quantidade);

        // Verifica se o resultado é um número
        if (!isNaN(resultado)) {
            // Formata o resultado para BRL
            var resultadoFormatado = resultado.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
            document.getElementById('resultado').value = resultadoFormatado;
        } else {
            document.getElementById('resultado').value = '';
        }
    }
</script>
</body>
</html>
@endsection
