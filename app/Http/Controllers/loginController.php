<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use App\Models\userModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class loginController extends Controller
{
    public function viewLogin(){
        if(Session::has('usuarioId')){
            return redirect('/dashuser');
        }
        return view('loginadm');
    }

    public function loginUsuario(loginRequest $request){
        $senha = $request->senha;
        $request->validated();

        $user = userModel::where('login', $request->usuario)->first();


        if ($user && Hash::check($senha, $user->senha)){
            Session::put('usuarioId', $user->id_usuario);

            return redirect('/dashuser');

        }
        else{
            Log::error('Houve um erro na tentativa de login');
            return back()->with('error', 'Login inválido!');
        }
    }
}
