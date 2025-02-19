@extends('layouts.main_layout')

@section('content')



<div class="container">

    <h1>Selecione o funcionário</h1>
    <form action="/salvaredicao" method="post">
        @foreach ($sqlRdv as $itemrdv)

        @endforeach

        @foreach ($sqlfun as $itemfun)

        @endforeach
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputState"><strong>Responsável: {{ $itemrdv->nome_funcionario }}</strong></label>
                <select id="inputState" name="id_responsavel" class="form-control">
                    <option selected value="{{ $itemrdv->id_funcionario }}">{{ $itemrdv->nome_funcionario }}</option>
                        @foreach ($sqlfun as $funcionario)
                        <option value="{{ $funcionario->id_funcionario }}">{{ $funcionario->nome_funcionario }}</option>
                        @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label><strong>Via: {{ $itemrdv->via }}</strong></label><br>
                <div class="form-check form-check-inline">
                    @php
                        if($itemrdv->via == 'Terrestre'){
                    @endphp
                            <script>
                                // Seu código JavaScript aqui
                                document.addEventListener("DOMContentLoaded", function() {
                                    let radioButton = document.getElementById("inlineRadio1");
                                    radioButton.checked = true;
                                });
                            </script>
                    @php
                        }
                    @endphp

                    @php
                        if($itemrdv->via == 'Fluvial'){
                    @endphp
                            <script>
                                // Seu código JavaScript aqui
                                document.addEventListener("DOMContentLoaded", function() {
                                    let radioButton = document.getElementById("inlineRadio2");
                                    radioButton.checked = true;
                                });
                            </script>
                    @php
                        }
                    @endphp

                    @php
                    if($itemrdv->via == 'Fluvial/Terrestre'){
                    @endphp
                        <script>
                            // Seu código JavaScript aqui
                            document.addEventListener("DOMContentLoaded", function() {
                                let radioButton = document.getElementById("inlineRadio3");
                                radioButton.checked = true;
                            });
                        </script>
                    @php
                    }
                    @endphp

                    @php
                    $data = new DateTime($itemrdv->data_viagem);
                    $databr = $data->format('d/m/Y');
                    @endphp
                    <input class="form-check-input" type="radio" name="via" id="inlineRadio1" value="Terrestre">
                    <label class="form-check-label" for="inlineRadio1">Terrestre</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="via" id="inlineRadio2" value="Fluvial">
                    <label class="form-check-label" for="inlineRadio2">Fluvial</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="via" id="inlineRadio3" value="Fluvial/Terrestre">
                    <label class="form-check-label" for="inlineRadio2">Terrestre/Fluvial</label>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label><strong>Data da viagem: {{ $databr }}</strong></label>
                <input class="form-control" type="date" name="data" value="{{ $itemrdv->data_viagem }}">
            </div>
            <div style="margin-left: 10px" class="form-group">
                <label><strong>Hora: {{ $itemrdv->hora }}</strong></label>
                <input class="form-control" type="time" name="hora" value="{{ $itemrdv->hora }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Justificativa</label>
                <input name="justificativa" class="form-control" value="{{ $itemrdv->justificativa }}" type="text">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Equipe</label>
                <input name="equipe" class="form-control" value="{{ $itemrdv->equipe }}" type="text">
            </div>
            <div class="form-group col-md-12">
                <label>Operação</label>
                <input name="ope" class="form-control" value="{{ $itemrdv->operacao }}" type="text">
            </div>
        </div>

        {{-- inputs hidden para coletar os dados --}}
        <input type="hidden" name="id" value="{{ $itemrdv->id }}">
        <input type="hidden" name="numrdv" value="{{ $itemrdv->num_rdv }}">
        <input type="hidden" name="created_at" value="{{ $itemrdv->created_at }}">

        {{-- fim dos inputs hidden --}}

        <button type="submit" class="btn btn-success"><i class="bi bi-floppy-fill"> Salvar</i></button>
        <button type="button" class="btn" onclick="window.history.back()">Cancelar</button>
    </form>
</div>
</body>
</html>

@endsection
