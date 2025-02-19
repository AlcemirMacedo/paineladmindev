@extends('layouts.main_layout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/loginadmin.css') }}">
    <style>
        html, body{
            height: 100vh;
            }
            body{
                display: flex;
                justify-content: center;
                align-items: center;
            }

    </style>
@endsection
@section('content')
    <section class="card col-md-6">
        <div class="row">
            <div id="col1" class="col-md-6">
                <figure>
                    <img src="{{ asset('img/logo-w.png') }}" alt="">
                </figure>
                <h1>Painel Administrativo</h1>
                <hr>
                <h3>Todos os direitos reservados &copy</h3>
            </div>
            <div id="col2" class="col-md-6">
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
                            title: "Painel Administrativo Erro",
                            text: "{{ Session::get('error') }}",
                            icon: "error"
                        })
                    </script>

                @endif
                <form action="/login" method="POST" class="col-md-12">
                    @csrf
                    <h1 class="text-center">Login</h1>
                    <hr>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="usuario">Usuário:</label>
                            <input type="text" name="usuario" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="senha">Senha</label>
                            <input type="password" name="senha" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <button type="submit" class="btn btn-success btn-block">Login</button>
                        <a class="btn btn-light btn-block" style="margin-bottom: 10px" href="/cadastro">Criar uma conta</a>
                    </div>
                </form>
                @if ($errors->any())
                    <script>
                        swal({
                            title: "Painel Administrativo Erro",
                            text: "{{ implode('\n', $errors->all()) }}",
                            icon: "error"
                        })
                    </script>
                @endif
            </div>
        </div>
    </section>
    <script src="{{ asset('js/jquery-3.4.0.min.js') }}"></script>
    <script src="{{ asset('bootstrap-4.1.3-dist/js/bootstrap.min.js') }}"></script>

@endsection



