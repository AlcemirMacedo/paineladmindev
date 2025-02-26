@extends('layouts.main_layout')

@section('content')



@if ($errors -> any())
    <script>
        swal({
            title:"Mensagem de erro",
            text: "{{ implode('\n', $errors->all()) }}",
            icon: "error"
        })
    </script>
@endif
{{-- @if ($errors -> any())
        <script>
            swal({
                title: "Mensagem de erro",
                text: "{{ implode('\n', $errors->all()) }}",
                icon: "error"
            })
        </script>
@endif --}}

<div class="container">
    <h1 class="text-center" style="margin-top: 20px "></i>Criar Novo RDV</h1>
    <hr/>
    <form action="salvarresponsavel" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputState">Responsável</label>
                <select id="inputState" name="responsavel" class="form-control">
                    <option value="" selected>Selecione</option>
                    @foreach ($sql as $item)
                        <option  value="{{ $item->id_funcionario }}">{{ $item->nome_funcionario }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Via:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="via" id="inlineRadio1" value="Terrestre" required>
                    <label class="form-check-label" for="inlineRadio1">Terrestre</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="via" id="inlineRadio2" value="Fluvial" required>
                    <label class="form-check-label" for="inlineRadio2">Fluvial</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="via" id="inlineRadio3" value="Fluvial/Terrestre" required>
                    <label class="form-check-label" for="inlineRadio3">Terrestre/Fluvial</label>
                </div>
            </div>
        </div>


        <div class="form-row">
            <div class="form-group">
                <label>Data:</label>
                <input class="form-control" type="date" name="data" required>
            </div>
            <div style="margin-left: 10px" class="form-group">
                <label>Hora:</label>
                <input class="form-control" type="time" name="hora" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Justificativa</label>
                <input name="justificativa" class="form-control" type="text">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Equipe</label>
                <input name="equipe" class="form-control" type="text">
            </div>
            <div class="form-group col-md-12">
                <label>Operação</label>
                <input name="ope" class="form-control" type="text">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Observações</label>
                <input name="obs_rdv" class="form-control" type="text">
            </div>
        </div>

        <button type="submit" class="btn btn-success">Próximo <i class="bi bi-chevron-double-right"></i></button>
        <button type="button" class="btn" onclick="window.history.back()">Voltar</button>
    </form>
</div>
</body>
</html>
@endsection
