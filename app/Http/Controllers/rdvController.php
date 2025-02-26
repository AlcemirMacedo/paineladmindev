<?php
namespace App\Http\Controllers;

use App\Http\Requests\novordvRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class rdvController extends Controller
{

    //View RDV inicial lista todos os RDVS
    public function rdvView(){
        $rdv = DB::select('select * from rdvs');
        $count_rdv = count($rdv);

        $funcionario = DB::select('select * from tb_funcionarios');
        $count_funcionario = count($funcionario);

        $sql = DB::table('rdvs')
        ->leftJoin('tb_funcionarios', 'id_funcionario', '=' ,'id_funcionario_fk')
        ->orderBy('id', 'desc')
        ->paginate(7);
        return view('index', compact('sql', 'count_rdv', 'count_funcionario'));


    }


    //Criar novo RDV
    public function novoRdv(){
        $sql = DB::select('select * from tb_funcionarios');
        return view('form1')->with('sql', $sql);
    }

    public function salvarResponsavel(novordvRequest $r){
        // dd($r);

        $r->validated();

        $ultRdv = DB::select('select num_rdv from rdvs order by id desc limit 1');

        if(count($ultRdv) > 0){
            $ultimoNumero = $ultRdv[0]->num_rdv;
            $pos = strpos($ultimoNumero, '/');
            if($pos == false){
                $pos = 0;
            }

            $numRecibo = substr($ultimoNumero, 0, $pos);
            $numRdvsoma = $numRecibo + 1;
            $numRdvdb = $numRdvsoma.'/'.date('Y');
        }
        else{
            $numRdvsoma = 1;
            $numRdvdb = $numRdvsoma.'/'.date('Y');
        }

        DB::insert("insert into rdvs values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, null)", [
            $r->responsavel,
            $numRdvdb,
            $r->data,
            $r->hora,
            $r->justificativa,
            $r->equipe,
            $r->ope,
            $r->via,
            $r->obs_rdv,
            date('Y-m-d H:i:s'),
        ]);

        $selectJoin = DB::select('select * from rdvs join tb_funcionarios on id_funcionario_fk = id_funcionario where num_rdv=?', [$numRdvdb]);
        $selectItens = DB::select('select * from itens_rdvs where rdv_id=? order by id desc', [$r->idrdv]);


        return view('form2', compact('selectJoin', 'selectItens'));

    }

    public function addItens(Request $request){

        DB::insert('insert into itens_rdvs values (null, ?, ?, ?, ?, ?, ?)', [
            $request->idrdv,
            $request->descricao,
            $request->valor,
            $request->quantidade,
            $request->total,
            $request->obs
        ]);

        $selectJoin = DB::select('select * from rdvs join tb_funcionarios on id_funcionario_fk = id_funcionario where num_rdv=?', [$request->numrdv]);
        $selectItens = DB::select('select * from itens_rdvs where rdv_id=? order by id desc', [$request->idrdv]);

        return view('form2', compact('selectJoin', 'selectItens'));

    }

    //Editar RDV
    public function editarRdv($value){
        $sqlfun = DB::select('select * from tb_funcionarios order by nome_funcionario');
        $sqlRdv = DB::select('select * from rdvs join tb_funcionarios on id_funcionario = id_funcionario_fk where id = ?', [$value]);

        // dd($sqlRdv);
        return view('editarrdv', compact('sqlRdv', 'sqlfun'));
    }



    public function salvarEdite(Request $request){
        // dd($request);
        $created = date('Y-m-d H:i:s');
        DB::update('update rdvs set id_funcionario_fk=?, num_rdv=?, data_viagem=?, hora=?, justificativa=?, equipe=?, operacao=?, via=?, created_at=?, updated_at=?  where id=?', [
            $request->input('id_responsavel'),
            $request->input('numrdv'),
            $request->input('data'),
            $request->input('hora'),
            $request->input('justificativa'),
            $request->input('equipe'),
            $request->input('ope'),
            $request->input('via'),
            $request->input('created_at'),
            $created,
            $request->input('id')
        ]);

        $selectJoin = DB::select('select * from rdvs join tb_funcionarios on id_funcionario_fk = id_funcionario where num_rdv=?', [$request->numrdv]);
        $selectItens = DB::select('select * from itens_rdvs where rdv_id=? order by id desc', [$request->id]);

        return view('form2', compact('selectJoin', 'selectItens'));

    }

    //Propriedade para excluir RDV
    public function excluirRdv($value){
        DB::delete('delete from rdvs where id=?', [$value]);
        return redirect('/rdvlist');
    }

    //Propriedade para excluir Item do RDV
    public function excluirItem(Request $request){
        DB::delete('delete from itens_rdvs where id=?', [$request->id]);
        $selectJoin = DB::select('select * from rdvs join tb_funcionarios on id_funcionario_fk = id_funcionario where num_rdv=?', [$request->numrdv]);
        $selectItens = DB::select('select * from itens_rdvs where rdv_id=? order by id desc', [$request->idrdvfk]);

        return view('form2', compact('selectJoin', 'selectItens'));
    }


    //Método para gerar o PDF
    public function gerarRdv(Request $request){

        $selectItens = DB::select('select * from itens_rdvs where rdv_id=? order by id desc', [$request->idrdv]);

        $user = DB::select('select * from tbuser where id_usuario=?', [
            Session::get('usuarioId')
        ]);

        // Inicializar a variável de soma
        $soma = 0;

        // Iterar sobre os itens selecionados e somar os valores
        foreach($selectItens as $ite){
            $soma += floatval(str_replace(['.', ','], ['', '.'], $ite->valor_total));
        }


        $total = number_format($soma, 2, ',', '.');


        // dd($request);
        $nomefun = $request->nome;
        $numrdv = str_replace('/', '', $request->numrdv);
        $hora = $request->hora;
        $funcao = $request->funcao;
        $via = $request->via;
        $justificativa = $request->justificativa;
        $equipe = $request->equipe;
        $ope = $request->ope;
        $data_iso = $request->data;
        $created_at = $request->created_at;

        // Substitui hífens por barras
        $data_temp = str_replace('-', '/', $data_iso);

        // Reorganiza a data para o formato DD/MM/YYYY
        $data_br = substr($data_temp, 8, 2) . '/' . substr($data_temp, 5, 2) . '/' . substr($data_temp, 0, 4);

        $html = "
            <style type='text/css'>
                html, body {
                    height: 297mm;
                    width: 210mm;
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    text-align: center;
                    font-family: Verdana, Geneva, Tahoma, sans-serif;
                    font-size: 8pt;
                }
                code{
                    color: #B22222;

                }
                .main-body {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    width: 180mm;
                    height: 280mm;
                    margin-left: 10mm;
                    margin-top: 10mm;
                }
                .container-pdf {
                    width: 100%;
                    height: 120mm;
                    background-color: #fff;
                    border: 1px solid rgb(82, 82, 82);
                    margin-bottom: 5mm;
                    padding: 2mm;
                    align-items: center;
                    background: url('img/watermark.png');
                    background-position: center;
                    background-size: cover;
                    background-repeat: no-repeat;
                }
                .text-head{
                    line-height:50%;
                    font-size:7pt;
                    padding-left:10px;
                }
                .border-td{
                    border-right:1px solid rgb(126, 126, 126);
                    border-bottom:1px solid rgb(126, 126, 126);
                }
                .container-footer{
                    border:1px rgb(82, 82, 82) solid;
                    width: 100%;
                    height: 30mm;
                }
            </style>
            <div class='main-body'>
                <table>
                    <tr>
                        <td><img src='img/logo.png' width='100'></td>
                        <td class='text-head'>
                            <p>GERENCIA DE ADMINISTRAÇÃO E FINANÇAS</p>
                            <p>TECNOLOGIA DA INFORMAÇÃO</p>
                            <p>PROVISIONAMENTO FINANCEIRO PARA VIAGENS</p>
                        </td>
                    </tr>
                </table>
                <div class='container-pdf'>
                    <table>
                        <tr>
                            <td>RDV nº <code>$request->numrdv</code></td>
                        </tr>
                        <tr>
                            <td>Data: <code>$data_br</code> | Hora: <code>$hora</code></td>
                        </tr>
                        <tr>
                            <td>Responsável: <code>$nomefun</code> | Função: <code>$funcao</code></td></tr>
                        <tr>
                            <td>Modalidade: <code>$via</code> | Justificativa: <code>$justificativa</code></td>
                        </tr>
                        <tr>
                            <td>Equipe: <code>$equipe</code> | Operação: <code>$ope </code> | Criado em: <code>$created_at</code></td>
                        </tr>
                    </table>
                    <div style='border-bottom:1px #000000 solid; width:100%; margin-top:10px; margin-bottom:10px;'></div>
                    <table width='100%'>
                        <thead style='background-color: #DCDCDC'>
                            <tr>
                                <th scope='col'>Item</th>
                                <th scope='col'>Descrição</th>
                                <th scope='col'>Quantidade</th>
                                <th scope='col'>Valor R$</th>
                                <th scope='col'>Valor Total R$</th>
                                <th scope='col'>Observação</th>
                            </tr>
                        </thead>
                        <tbody>
                ";
        $contador = 1;
        foreach($selectItens as $item){
            $html .="
                    <tr style='border-bottom:1px solid #000000'>
                        <td class='border-td' align='center'>$contador</td>
                        <td class='border-td'>$item->descricao</td>
                        <td class='border-td' align='center'>$item->quantidade</td>
                        <td class='border-td' align='center'>$item->valor</td>
                        <td class='border-td' align='center'>$item->valor_total</td>
                        <td class='border-td'>$item->observacao</td>
                    </tr>
                ";
                $contador++;
        }

        $html .="
        <tr>
            <td colspan='6' align='right' style='font-size:12pt'>Valor Total R$ <code>$total</code></td>
        </tr>
        </tbody>
                    </table>

            </div>
            <p>Apresentar todos os recibos e cupons ao retornar da operação, ordenando cronologicamente. Qualquer despesa não prevista nesse cronograma deve ser comunicado antecipadamente ao financeiro ante de qualque execução</p>
            <div class='container-footer'>
                <p>Demais observações</p>
                <table width='100'>
                    <tr>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div style='position:absolute; bottom:10mm; right:10mm'>
                Emitido por: <code>{$user[0]->nome}</code>
            </div>
        </div>";

        //Este Return gera o pdf
        return Pdf::loadHTML($html)->setPaper('A4', 'portrait') // Define o tamanho do papel
                                ->set_option('isHtml5ParserEnabled', true) // Garante compatibilidade com HTML5
                                ->set_option('isRemoteEnabled', true) // Permite imagens externas
                                ->set_option('isPhpEnabled', false)
                                ->download("RDV nº $numrdv $nomefun.pdf");
    }

     //Método para gerar o PDF
     public function gerarRdvdir($value){

        $selectRdv = DB::select('select * from rdvs join tb_funcionarios on id_funcionario_fk = id_funcionario where id=?', [$value]);
        foreach($selectRdv as $itemrdv){
            $data_iso = $itemrdv->data_viagem;
            $num_rdv = $itemrdv->num_rdv;
            $data_viagem = $itemrdv->data_viagem;
            $hora = $itemrdv->hora;
            $justificativa = $itemrdv->justificativa;
            $equipe = $itemrdv->equipe;
            $operacao = $itemrdv->operacao;
            $via = $itemrdv->via;
            $created_at = $itemrdv->created_at;
            $id_funcionario = $itemrdv->id_funcionario;
            $nome_funcionario = $itemrdv->nome_funcionario;
            $cargo_funcionario = $itemrdv->cargo_funcionario;
        }

        $selectItens = DB::select('select r.*, i.* from rdvs as r join itens_rdvs as i on r.id = i.rdv_id where r.id=?', [$value]);
        // dd($selectRdv);
        // dd($selectItens);

        $user = DB::select('select * from tbuser where id_usuario=?', [
            Session::get('usuarioId')
        ]);

        // Inicializar a variável de soma
        $soma = 0;

        // Iterar sobre os itens selecionados e somar os valores
        foreach($selectItens as $ite){
            $soma += floatval(str_replace(['.', ','], ['', '.'], $ite->valor_total));
        }


        $total = number_format($soma, 2, ',', '.');


        // dd($request);
        // $nomefun = $request->nome;
        $numrdv = str_replace('/', '', $num_rdv);
        // $hora = $request->hora;
        // $funcao = $request->funcao;
        // $via = $request->via;
        // $justificativa = $request->justificativa;
        // $equipe = $request->equipe;
        // $ope = $request->ope;
        // $created_at = $request->created_at;

        // Substitui hífens por barras
        $data_temp = str_replace('-', '/', $data_iso);

        // Reorganiza a data para o formato DD/MM/YYYY
        $data_br = substr($data_temp, 8, 2) . '/' . substr($data_temp, 5, 2) . '/' . substr($data_temp, 0, 4);

        $html = "
            <style type='text/css'>
                html, body {
                    height: 297mm;
                    width: 210mm;
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    text-align: center;
                    font-family: Verdana, Geneva, Tahoma, sans-serif;
                    font-size: 8pt;
                }
                code{
                    color: #B22222;

                }
                .main-body {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    width: 180mm;
                    height: 280mm;
                    margin-left: 10mm;
                    margin-top: 10mm;
                }
                .container-pdf {
                    width: 100%;
                    height: 120mm;
                    background-color: #fff;
                    border: 1px solid rgb(82, 82, 82);
                    margin-bottom: 5mm;
                    padding: 2mm;
                    align-items: center;
                    background: url('img/watermark.png');
                    background-position: center;
                    background-size: cover;
                    background-repeat: no-repeat;
                }
                .text-head{
                    line-height:50%;
                    font-size:7pt;
                    padding-left:10px;
                }
                .border-td{
                    border-right:1px solid rgb(126, 126, 126);
                    border-bottom:1px solid rgb(126, 126, 126);
                }
                .container-footer{
                    border:1px rgb(82, 82, 82) solid;
                    width: 100%;
                    height: 30mm;
                }
            </style>
            <div class='main-body'>
                <table>
                    <tr>
                        <td><img src='img/logo.png' width='100'></td>
                        <td class='text-head'>
                            <p>GERENCIA DE ADMINISTRAÇÃO E FINANÇAS</p>
                            <p>TECNOLOGIA DA INFORMAÇÃO</p>
                            <p>PROVISIONAMENTO FINANCEIRO PARA VIAGENS</p>
                        </td>
                    </tr>
                </table>
                <div class='container-pdf'>
                    <table>
                        <tr>
                            <td>RDV nº <code>$num_rdv </code></td>
                        </tr>
                        <tr>
                            <td>Data: <code>$data_br</code> | Hora: <code>$hora</code></td>
                        </tr>
                        <tr>
                            <td>Responsável: <code>$nome_funcionario</code> | Função: <code>$cargo_funcionario</code></td></tr>
                        <tr>
                            <td>Modalidade: <code>$via</code> | Justificativa: <code>$justificativa</code></td>
                        </tr>
                        <tr>
                            <td>Equipe: <code>$equipe</code> | Operação: <code>$operacao </code> | Criado em: <code>$created_at</code></td>
                        </tr>
                    </table>
                    <div style='border-bottom:1px #000000 solid; width:100%; margin-top:10px; margin-bottom:10px;'></div>
                    <table width='100%'>
                        <thead style='background-color: #DCDCDC'>
                            <tr>
                                <th scope='col'>Item</th>
                                <th scope='col'>Descrição</th>
                                <th scope='col'>Quantidade</th>
                                <th scope='col'>Valor R$</th>
                                <th scope='col'>Valor Total R$</th>
                                <th scope='col'>Observação</th>
                            </tr>
                        </thead>
                        <tbody>
                ";
        $contador = 1;
        foreach($selectItens as $item){
            $html .="
                    <tr style='border-bottom:1px solid #000000'>
                        <td class='border-td' align='center'>$contador</td>
                        <td class='border-td'>$item->descricao</td>
                        <td class='border-td' align='center'>$item->quantidade</td>
                        <td class='border-td' align='center'>$item->valor</td>
                        <td class='border-td' align='center'>$item->valor_total</td>
                        <td class='border-td'>$item->observacao</td>
                    </tr>
                ";
                $contador++;
        }

        $html .="
        <tr>
            <td colspan='6' align='right' style='font-size:12pt'>Valor Total R$ <code>$total</code></td>
        </tr>
        </tbody>
                    </table>

            </div>
            <p>Apresentar todos os recibos e cupons ao retornar da operação, ordenando cronologicamente. Qualquer despesa não prevista nesse cronograma deve ser comunicado antecipadamente ao financeiro ante de qualque execução</p>
            <div class='container-footer'>
                <p>Demais observações</p>
                <table width='100'>
                    <tr>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div style='position:absolute; bottom:10mm; right:10mm'>
                Emitido por: <code>{$user[0]->nome}</code>
            </div>
        </div>";

        //Este Return gera o pdf
        return Pdf::loadHTML($html)->setPaper('A4', 'portrait') // Define o tamanho do papel
                                ->set_option('isHtml5ParserEnabled', true) // Garante compatibilidade com HTML5
                                ->set_option('isRemoteEnabled', true) // Permite imagens externas
                                ->set_option('isPhpEnabled', false)
                                ->download("RDV nº $numrdv $cargo_funcionario.pdf");
    }
}
