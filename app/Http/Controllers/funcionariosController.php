<?php

namespace App\Http\Controllers;

use App\Http\Requests\funcionarioRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class funcionariosController extends Controller
{
    public function listarFuncionarios(){
        $sql = DB::table('tb_funcionarios')
        ->orderBy('id_funcionario', 'desc')
        ->paginate(8);

        $count_funcionarios = DB::table('tb_funcionarios')->count();
        return view('funcionarios', compact('sql', 'count_funcionarios'));
    }

    public function formFuncionario($value){

        $sql = DB::select('select * from tb_funcionarios where id_funcionario = ?', [$value]);

        if($sql){
            return view('cadastrofuncionario', compact('sql'));
        }
        else{
            return view('cadastrofuncionario', compact('sql'));
        }
    }

    public function salvarFuncionario(funcionarioRequest $request){

        $id = $request->id;
        $request->validated();

        if($id!=null){
            $id = $request->input('id');
            $nome = $request->input('nome');
            $cargo = $request->input('cargo');
            $cpf = $request->input('cpf');
            $matricula = $request->input('matricula');
            $email = $request->input('email');
            $endereco = $request->input('endereco');
            $contato = $request->input('contato');
            $data_nasc = $request->input('data_nasc');

            DB::update('update tb_funcionarios set nome_funcionario=?, cargo_funcionario=?, cpf_funcionario=?, matricula_funcionario=?, email_funcionario=?, end_funcionario=?, contato_funcionario=?, data_nasc=? where id_funcionario=?', [
                $nome,
                $cargo,
                $cpf,
                $matricula,
                $email,
                $endereco,
                $contato,
                $data_nasc,
                $id
            ]);

            return redirect('/listarfuncionarios');

        }else{
            try {
                DB::insert('insert into tb_funcionarios values (null,?,?,?,?,?,?,?,?)', [

                    $nome = $request->input('nome'),
                    $cargo = $request->input('cargo'),
                    $cpf = $request->input('cpf'),
                    $matricula = $request->input('matricula'),
                    $email = $request->input('email'),
                    $endereco = $request->input('endereco'),
                    $contato = $request->input('contato'),
                    $data_nasc = $request->input('data_nasc')
                ]);

                return redirect('/listarfuncionarios')->with('success', 'Cadastrado com sucesso');

            } catch (Exception $ex) {
                return back()->with('error', 'Erro ao cadastrar funcionário');
            }



        }
    }

    public function exluirFucionario($value){
        DB::delete('delete from tb_funcionarios where id_funcionario=?', [$value]);

        return back();
    }

}
