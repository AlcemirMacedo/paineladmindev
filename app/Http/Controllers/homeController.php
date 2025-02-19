<?php

namespace App\Http\Controllers;

use App\Models\fornecedoresModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class homeController extends Controller
{
    public function viewHome(){
        //Fornecedores
        $ultimo_For = DB::select('select data_inclusao from tb_fornecedores order by id_fornecedores');
        foreach($ultimo_For as $item){
            $dataFormatada = $item->data_inclusao;
        }

        $databd = new DateTime($dataFormatada);
        $dataFormatada = $databd->format('d/m/Y H:i:s');

        $fornecedores = DB::select('select id_fornecedores from tb_fornecedores');
        $count_fornecedores = count($fornecedores);

        //Recibos
        $ultimo_recibo = DB::select('select data_recibo from tbrecibo order by id_recibo');
        if($ultimo_recibo){
            foreach($ultimo_recibo as $item){

                $ultimorecibo = $item->data_recibo;
            }

        }else{
            $ultimorecibo=0;
        }

        $recibos = DB::select('select * from tbrecibo');
        $count_recibo = count($recibos);

        // Usuários
        $usuarios = DB::select('select * from tbuser');
        $count_usuario = count($usuarios);

        // RDV
        $rdv = DB::select('select * from rdvs');
        $count_rdv = count($rdv);

        //Funcionários
        $funcionarios = DB::select('select * from tb_funcionarios');
        $count_funcionarios = count($funcionarios);

        return view('home',[
            'ultimoRegistro'=>$dataFormatada,
            'count_fornecedores'=>$count_fornecedores,
            'ultimoRecibo'=>$ultimorecibo,
            'count_recibo'=>$count_recibo,
            'countusuario'=>$count_usuario,
            'count_rdv'=>$count_rdv,
            'count_funcionarios'=>$count_funcionarios
        ]);
    }
}
