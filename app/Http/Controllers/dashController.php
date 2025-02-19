<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class dashController extends Controller
{
    public function viewDash(){
        if(Session::get('usuarioId')){

            $user = DB::select('select * from tbuser where id_usuario=?', [
                Session::get('usuarioId')
            ]);
            return view('dashUsuario',['usuario' => $user]);
        }
        else{
            return redirect('/');
        }
    }
}
