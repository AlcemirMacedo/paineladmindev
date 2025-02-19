<?php

namespace App\Http\Controllers;

use App\Models\fornecedoresModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class fornecedorController extends Controller
{

    public function viewFornecedor(){
        $sql = DB::table('tb_fornecedores')
        ->orderBy('id_fornecedores', 'desc')
        ->paginate(8);

        $count_fornecedor = DB::table('tb_fornecedores')->count();
        return view('fornecedor', compact('sql', 'count_fornecedor'));


    }

    public function getFornecedor($value){

        $sql = DB::select('select * from tb_fornecedores where id_fornecedores = ?', [$value]);


        return view('editarFornecedor')->with('sql', $sql);

    }

    public function searchFornecedor(Request $request){

        $sql = fornecedoresModel::where('nome', 'like', '%'.$request->search.'%')
        ->orWhere('cpfcnpj', 'like', '%'.$request->search.'%')->paginate(10)->withQueryString();



        $count_fornecedor = count($sql);
        if(count($sql) > 0){
            return view('fornecedor', [
                'sql' => $sql,
                'count_fornecedor' => $count_fornecedor
            ]);
        }
        else{
            return back()->with('attention','Registro não encontrado!');
        }

    }
    public function formFornecedor(){
        return view('cadastrofornecedor');
    }
}
