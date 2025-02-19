<?php

use App\Http\Controllers\crudFornecedorController;
use App\Http\Controllers\crudReciboController;
use App\Http\Controllers\dashController;
use App\Http\Controllers\emitirReciboController;
use App\Http\Controllers\fornecedorController;
use App\Http\Controllers\funcionariosController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\rdvController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

Route::get('/', [loginController::class, 'viewLogin']);
Route::post('/login',[loginController::class, 'loginUsuario']);
Route::get('/cadastro', [userController::class, 'cadastro']);
Route::post('/cadastrar', [userController::class, 'addUser']);

Route::middleware(['session'])->group(function(){
    Route::get('/dashuser', [dashController::class, 'viewDash']);
    Route::get('/logout', [logoutController::class, 'logoutUser']);
    Route::get('/home', [homeController::class, 'viewHome']);
    Route::get('/fornecedor', [fornecedorController::class, 'viewFornecedor']);
    Route::get('/editarfornecedor/{value}', [fornecedorController::class, 'getFornecedor']);
    Route::get('/emitirrecibo/{value}', [emitirReciboController::class, 'emitirRecibo']);
    Route::post('/gerarpdf', [emitirReciboController::class, 'gerarPdf']);
    Route::get('/gridrecibo', [crudReciboController::class, 'viewGrid']);
    Route::get('/formrecibo/{value}', [crudReciboController::class, 'viewFormRecibo']);
    Route::get('/excluirrecibo/{value}', [crudReciboController::class, 'exluirRecibo']);
    Route::post('/baixarpdf', [emitirReciboController::class, 'baixarPDF']);
    Route::post('/editarrecibo', [crudReciboController::class, 'editarRecibo']);
    Route::post('/editarfornecedor', [crudFornecedorController::class, 'editarFornecedor']);
    Route::get('/excluirfornecedor/{value}', [crudFornecedorController::class, 'excluirFornecedor']);
    Route::get('/usuarios', [userController::class, 'mostrarUsuarios']);
    Route::get('/excluirsuario/{value}', [userController::class, 'excluirUsuario']);
    Route::get('/formrusuario/{value}', [userController::class, 'formUsuario']);
    Route::get('/searchfornecedor', [fornecedorController::class, 'searchFornecedor']);
    Route::get('/searchrecibo', [crudReciboController::class, 'searchRecibo']);
    Route::get('/cadastrofornecedor', [fornecedorController::class, 'formFornecedor']);
    Route::post('/cadastrarfornecedor', [crudFornecedorController::class, 'cadastrarFornecedor']);
    Route::get('/listarfuncionarios', [funcionariosController::class, 'listarFuncionarios']);
    Route::get('/cadastrofuncionario/{value}', [funcionariosController::class, 'formFuncionario']);
    Route::post('/cadastrofuncionario/', [funcionariosController::class, 'salvarFuncionario']);
    Route::get('/exluirfucionario/{value}', [funcionariosController::class, 'exluirFucionario']);
    Route::get('/gridrdv', [rdvController::class, 'listarRdv']);
    Route::get('/formrdv', [rdvController::class, 'formRdv']);
    Route::get('/formrecibo', [emitirReciboController::class, 'formReciboAvulso']);
    Route::post('/emitirpdfavulso', [emitirReciboController::class, 'emitirPdfAvulso']);

    Route::get('/rdvlist', [rdvController::class, 'rdvView']);
    Route::get('/novordv', [rdvController::class, 'novoRdv']);
    Route::post('/salvarresponsavel', [rdvController::class, 'salvarResponsavel']);
    Route::get('/form2Rdv/{value}', [rdvController::class, 'form2Rdv']);
    Route::post('/add', [rdvController::class, 'addItens']);

    // Route::get('/additems', [rdvController::class, 'addItemsRDV']);


    // Rota para gerar o pdf
    Route::post('/gerarpdf', [rdvController::class, 'gerarPdf']);

    //Rotas de edições
    Route::get('/editarrdv/{value}', [rdvController::class, 'editarRdv']);
    Route::post('/salvaredicao', [rdvController::class, 'salvarEdite']);

    //Rotas de exclusão
    Route::get('/excluirrdv/{value}', [rdvController::class, 'excluirRdv']);
    Route::post('/excluiritem', [rdvController::class, 'excluirItem']);

});
