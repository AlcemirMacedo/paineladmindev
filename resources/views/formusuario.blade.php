@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/dash-frame.css') }}">
    <style>
        
        input{
            text-transform: none;
        }
    </style>
@endsection

@foreach ($sql as $item)

@endforeach

@section('content')

<div class="container">
    <h1 class="text-center">Editar Usuário</h1>
    <hr>
    <form id="passwordForm" action="/cadastrar" method="POST" autocomplete="off">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="fullname">Nome Completo</label>
                <input class="form-control" type="text" name="fullname" value="{{ $item->nome }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="usuario">Usuário</label>
                <input class="form-control" type="text" name="usuario" value="{{ old('usuario') }}">
            </div>
            <div class="form-group col-md-8">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" type="email" name="email" >
            </div>
        </div>
        <div class="form-row">
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="senha">Senha</label>
                <input id="password" class="form-control" type="password" name="senha">
            </div>
            <div class="form-group col-md-4">
                <label for="senha">Confirmar senha</label>
                <input id="confirmPassword" class="form-control" type="password" name="senhaconfirm">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <button class="btn btn-success col-md-4" type="submit">Cadastrar</button>
                <a class="btn btn-light col-md-4" style="margin-left: 10px" href="/usuarios">Voltar</a><br>
            </div>
        </div>
    </form>

    @if ($errors -> any())
        <script>
            swal({
                title: "Mensagem de erro",
                text: "{{ implode('\n', $errors->all()) }}",
                icon: "error"
            })
        </script>
    @endif

</div>

<script>
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Impede o envio padrão do formulário
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirmPassword').value;
        if (password !== confirmPassword) {
            alert('As senhas não correspondem. Por favor, tente novamente.');
        } else { // Aqui você pode adicionar a lógica para enviar o formulário, por exemplo:
        this.submit(); } });
</script>

@endsection
