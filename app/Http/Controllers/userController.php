<?php

namespace App\Http\Controllers;

use App\Http\Requests\cadastroRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class userController extends Controller
{
    public function cadastro(){
        return view('cadastro');
    }

    public function addUser(cadastroRequest $request){

        $senha = Hash::make($request->senha, ['rounds'=>10]);

        $request -> validated();

        try {
            $sql = DB::insert('insert into tbuser values(null, ?,?,?,?)', [
                $request->fullname,
                $request->usuario,
                $senha,
                $request->email
            ]);
            return redirect('/usuarios')->with('success', 'Usuário cadastrado com sucesso!');
        } catch (Exception $e) {
            Log::error("Erro Alcemir". $e->getMessage());
            return back()->with('error', 'Não foi possível cadastrar usuário')->withInput();
        }
    }

    public function mostrarUsuarios(){

        $sql = DB::select('select * from tbuser');
        $quant = count($sql);
        return view('mostrarusuarios', ['sql'=>$sql, 'contador'=>$quant]);
    }

    public function excluirUsuario($value){
        try {
            $sql = DB::delete('delete from tbuser where id_usuario = ?', [$value]);
            return redirect('/usuarios');
        } catch (Exception $ex) {
            return back()->with('error', 'Não foi possível excluir este usuário');
        }
    }

    public function formUsuario($value){
        $sql = DB::select('select * from tbuser where id_usuario = ?', [$value]);
        return view('formusuario', ['sql'=>$sql]);
    }

    public function editarUsuario(){

    }

}
