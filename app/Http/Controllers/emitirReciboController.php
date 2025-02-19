<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class emitirReciboController extends Controller{

    public function emitirRecibo($value){
        $sql = DB::select('select * from tb_fornecedores where id_fornecedores = ?', [$value]);

        return view('emitirrecibo')->with('sql', $sql);
    }

    private function numberToWords($number) {
        // Remover pontos e substituir a vírgula por ponto
        $number = str_replace('.', '', $number);
        $number = str_replace(',', '.', $number);

        // Converter para float
        $number = floatval($number);

        // Função para converter o número em extenso (exemplo simplificado)
        $formatter = new \NumberFormatter('pt_BR', \NumberFormatter::SPELLOUT);

        // Obter a parte inteira e a parte decimal corretamente
        $integerPart = floor($number);
        $decimalPart = round(($number - $integerPart) * 100);

        $integerPartInWord = $formatter->format($integerPart);
        $decimalPartInWord = $formatter->format($decimalPart);

        // Substituir "catorze" por "quatorze"
        $integerPartInWord = str_replace('catorze', 'quatorze', $integerPartInWord);
        $decimalPartInWord = str_replace('catorze', 'quatorze', $decimalPartInWord);

        if ($decimalPart == 0) {
            $centavos = '';
        } else {
            $centavos = ' e ' . $decimalPartInWord . ' centavos';
        }

        return ucfirst($integerPartInWord) . ' reais' . $centavos;
    }

     //Método responsável por carregar a página com o formulário de recibo com número na sequência do banco de dados
     public function formReciboAvulso(){

        $ultRecibo = DB::select('select * from tbrecibo order by id_recibo desc limit 1');

            if(count($ultRecibo) > 0){
                $ultimoNumero = $ultRecibo;
                $num = $ultimoNumero[0]->num_recibo;
                $pos = strpos($num, '/');
                if($pos == false){
                    $pos = 0;
                }
                $numRecibo = substr($num, 0, $pos);
                $numRecibo = $numRecibo + 1;
            }
            else{
                $numRecibo = 2975;
            }
        return view('formReciboAvulso')->with('numRecibo', $numRecibo.date('/Y'));
     }

     // Método que faz o registro do recibo avulso no banco de dados
     public function emitirPdfAvulso(Request $request){
        $nome = $request->input('nome');
        $descricao = $request->input('descricao');
        $valorrecibo = $request->input('valor');
        $numero = $request->input('numero');
        $cpfcnpj = $request->input('cpfcnpj');
        $data = date('Y-m-d H:i:s');

        //Remove os pontos
        $valorrecibosemponto = str_replace('.', '', $valorrecibo);

        // Substituir a vírgula por ponto
        $stringComPonto = str_replace(',', '.', $valorrecibosemponto);

        // Converter a string para float
        $valorFloat = floatval($stringComPonto);

        $numberInWords = $this->numberToWords($valorrecibo);

        DB::insert('insert into tbrecibo values (null,?,?,?,?,?,?,2)', [
            $numero,
            $cpfcnpj,
            $descricao,
            $valorFloat,
            $data,
            $numberInWords
        ]);



        $html = "
                <style type='text/css'>
                    html, body{
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
                    }
                    .line{
                        border-bottom: 1px dashed gray;
                        margin-bottom: 20px;
                        width: 180mm;
                    }
                    .main-body{
                        display:flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        width: 180mm;
                        height:280mm;
                        margin-left:10mm;
                        margin-top:10mm;
                    }
                    .container-pdf{
                        width: 100%;
                        height: 120mm;
                        background-color: #fff;
                        border: 1px solid rgb(0, 0, 0);
                        border-radius: 10px;
                        margin-bottom:5mm;
                        padding: 5mm;
                        align-items: center;
                        background: url('img/watermark.png');
                        background-position: center;
                        background-size: cover;
                        background-repeat: no-repeat;
                    }
                    .table-rec{
                        width:100%;
                    }
                    .table-rec td{
                        padding:1.3mm;
                    }
                    .table-rec .title-rec{
                        font-size:30pt;
                        font-weight: bold;
                    }
                    .table-rec .t-style{
                        border: 1px solid black;
                        border-radius:2mm;
                        width:50mm;
                        padding-left: 2mm;
                    }
                    .table-rec .valor span{
                        font-size:18pt;
                        font-weight: bold;
                    }
                    .table-rec .desc-ref{
                        border: 1px black solid;
                        border-radius:10px;
                        line-height:5mm;
                        min-height:45mm;
                        max-height: 45mm;
                        overflow: hidden;
                    }

                    table, th, td {
                        border:1px solid transparent;
                    }

                    .table-rec  .footer-rec{
                        border-top: 1px black dashed;
                        text-align: center;
                        font-size:9pt;
                        padding: 2px;
                        line-height:1mm;
                    }
                    .text-assign{
                        text-align:center;
                        border-top: 1px solid black;
                        font-size: 8pt;
                        position: absolute;
                        margin-top: -30px;
                        width:50mm;
                    }
                    .desc-text{
                        text-transform:uppercase;
                    }
                </style>
                <div class='main-body'>
                    <div class='container-pdf'>
                        <table class='table-rec' border='0'>
                            <tr>
                                <td class='title-rec'>Recibo</td>
                                <td align='center' class='t-style serie'>Nº $numero</td>
                                <td></td>
                                <td align='center' class='t-style valor'>R$ <span> $valorrecibo</span></td>
                            </tr>
                            <tr>
                                <td colspan='4'></td>
                            </tr>
                            <tr height='50'>
                                <td colspan='4'>
                                    <div class='desc-ref'>

                                        <table style='width:100%; height:45mm'>
                                            <tr>
                                                <td>
                                                    Recebi(emos) de(a): <span>$nome</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    a importância de: <span class='desc-text' style='font-size: 9pt'>$numberInWords</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    Referente a: <span class='desc-text' style='font-size: 9pt'>$descricao</span>
                                                </td>
                                            </tr>
                                            <tr >
                                                <td align='right'>
                                                    Manaus, <span class='timedate' style='font-size: 12pt; font-weight:bold;'>___/___/_____. </span><span style='font-size: 9pt'></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td colspan='2'>
                                        <table align='left'>
                                            <tr>
                                                <td>
                                                    <img src='img/assinatura.png' width='180'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class='text-assign'>
                                                        Júlio Neto<br>
                                                        Infortread Telecom<br>
                                                        Gerente Geral
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                </td>
                                <td colspan='2' align='right'>
                                    <img src='img/carimbo-transp.png' width='180'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='4' class='footer-rec'>
                                    <p>Endereço Comercial: RUA DJALMA DUTRA - 44 - NOSSA SENHORA DAS GRACAS | MANAUS-AM - CEP 63.053-400 |</p>
                                    <p>Contato: (92)9271-7118 | Email: infortread.am@gmail.com | Site: www.infortread.com.br</p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class='line'></div>

                    <div class='container-pdf'>
                        <table class='table-rec' border='0'>
                            <tr>
                                <td class='title-rec'>Recibo</td>
                                <td align='center' class='t-style serie'>Nº $numero</td>
                                <td></td>
                                <td align='center' class='t-style valor'>R$ <span> $valorrecibo</span></td>
                            </tr>
                            <tr>
                                <td colspan='4'></td>
                            </tr>
                            <tr height='50'>
                                <td colspan='4'>
                                    <div class='desc-ref'>

                                        <table style='width:100%; height:45mm'>
                                            <tr>
                                                <td>
                                                    Recebi(emos) de(a): <span>$nome</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    a importância de: <span class='desc-text' style='font-size: 9pt'>$numberInWords</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    Referente a: <span class='desc-text' style='font-size: 9pt'>$descricao</span>
                                                </td>
                                            </tr>
                                            <tr >
                                                <td align='right'>
                                                    Manaus, <span class='timedate' style='font-size: 12pt; font-weight:bold;'>___/___/_____. </span><span style='font-size: 9pt'></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td colspan='2'>
                                        <table align='left'>
                                            <tr>
                                                <td>
                                                    <img src='img/assinatura.png' width='180'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class='text-assign'>
                                                        Júlio Neto<br>
                                                        Infortread Telecom<br>
                                                        Gerente Geral
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                </td>
                                <td colspan='2' align='right'>
                                    <img src='img/carimbo-transp.png' width='180'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='4' class='footer-rec'>
                                    <p>Endereço Comercial: RUA DJALMA DUTRA - 44 - NOSSA SENHORA DAS GRACAS | MANAUS-AM - CEP 63.053-400 |</p>
                                    <p>Contato: (92)9271-7118 | Email: infortread.am@gmail.com | Site: www.infortread.com.br</p>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>

            ";

    return Pdf::loadHTML($html)->setPaper('A4', 'portrait') // Define o tamanho do papel
                        ->set_option('isHtml5ParserEnabled', true) // Garante compatibilidade com HTML5
                        ->set_option('isRemoteEnabled', true) // Permite imagens externas
                        ->set_option('isPhpEnabled', false)
                        ->download("Recibo de $nome.pdf");
    }

    //método para emitir um pdf já cadastrado
    public function baixarPDF(Request $request){
        $nome = $request->input('nome');
        $descricao = $request->input('descricao');
        $valorrecibo = $request->input('valor');
        $numero = $request->input('numero');
        $vlrextenso = $request->input(('vlr_extenso'));
        $html = "
                <style type='text/css'>
                    html, body{
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
                    }
                    .line{
                        border-bottom: 1px dashed gray;
                        margin-bottom: 20px;
                        width: 180mm;
                    }
                    .main-body{
                        display:flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        width: 180mm;
                        height:280mm;
                        margin-left:10mm;
                        margin-top:10mm;
                    }
                    .container-pdf{
                        width: 100%;
                        height: 120mm;
                        background-color: #fff;
                        border: 1px solid rgb(0, 0, 0);
                        border-radius: 10px;
                        margin-bottom:5mm;
                        padding: 5mm;
                        align-items: center;
                        background: url('img/watermark.png');
                        background-position: center;
                        background-size: cover;
                        background-repeat: no-repeat;
                    }
                    .table-rec{
                        width:100%;
                    }
                    .table-rec td{
                        padding:1.3mm;
                    }
                    .table-rec .title-rec{
                        font-size:30pt;
                        font-weight: bold;
                    }
                    .table-rec .t-style{
                        border: 1px solid black;
                        border-radius:2mm;
                        width:50mm;
                        padding-left: 2mm;
                    }
                    .table-rec .valor span{
                        font-size:18pt;
                        font-weight: bold;
                    }
                    .table-rec .desc-ref{
                        border: 1px black solid;
                        border-radius:10px;
                        line-height:5mm;
                        min-height:45mm;
                        max-height: 45mm;
                        overflow: hidden;
                    }

                    table, th, td {
                        border:1px solid transparent;
                    }

                    .table-rec  .footer-rec{
                        border-top: 1px black dashed;
                        text-align: center;
                        font-size:9pt;
                        padding: 2px;
                        line-height:1mm;
                    }
                    .text-assign{
                        text-align:center;
                        border-top: 1px solid black;
                        font-size: 8pt;
                        position: absolute;
                        margin-top: -30px;
                        width:50mm;
                    }
                    .desc-text{
                        text-transform:uppercase;
                    }
                </style>
                <div class='main-body'>
                    <div class='container-pdf'>
                        <table class='table-rec' border='0'>
                            <tr>
                                <td class='title-rec'>Recibo</td>
                                <td align='center' class='t-style serie'>Nº $numero</td>
                                <td></td>
                                <td align='center' class='t-style valor'>R$ <span> $valorrecibo</span></td>
                            </tr>
                            <tr>
                                <td colspan='4'></td>
                            </tr>
                            <tr height='50'>
                                <td colspan='4'>
                                    <div class='desc-ref'>

                                        <table style='width:100%; height:45mm'>
                                            <tr>
                                                <td>
                                                    Recebi(emos) de(a): <span>$nome</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    a importância de: <span class='desc-text' style='font-size: 9pt'>$vlrextenso</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    Referente a: <span class='desc-text' style='font-size: 9pt'>$descricao</span>
                                                </td>
                                            </tr>
                                            <tr >
                                                <td align='right'>
                                                    Manaus, <span class='timedate' style='font-size: 12pt; font-weight:bold;'>___/___/_____. </span><span style='font-size: 9pt'></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td colspan='2'>
                                        <table align='left'>
                                            <tr>
                                                <td>
                                                    <img src='img/assinatura.png' width='180'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class='text-assign'>
                                                        Júlio Neto<br>
                                                        Infortread Telecom<br>
                                                        Gerente Geral
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                </td>
                                <td colspan='2' align='right'>
                                    <img src='img/carimbo-transp.png' width='180'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='4' class='footer-rec'>
                                    <p>Endereço Comercial: RUA DJALMA DUTRA - 44 - NOSSA SENHORA DAS GRACAS | MANAUS-AM - CEP 63.053-400 |</p>
                                    <p>Contato: (92)9271-7118 | Email: infortread.am@gmail.com | Site: www.infortread.com.br</p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class='line'></div>

                    <div class='container-pdf'>
                        <table class='table-rec' border='0'>
                            <tr>
                                <td class='title-rec'>Recibo</td>
                                <td align='center' class='t-style serie'>Nº $numero</td>
                                <td></td>
                                <td align='center' class='t-style valor'>R$ <span> $valorrecibo</span></td>
                            </tr>
                            <tr>
                                <td colspan='4'></td>
                            </tr>
                            <tr height='50'>
                                <td colspan='4'>
                                    <div class='desc-ref'>

                                        <table style='width:100%; height:45mm'>
                                            <tr>
                                                <td>
                                                    Recebi(emos) de(a): <span>$nome</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    a importância de: <span class='desc-text' style='font-size: 9pt'>$vlrextenso</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    Referente a: <span class='desc-text' style='font-size: 9pt'>$descricao</span>
                                                </td>
                                            </tr>
                                            <tr >
                                                <td align='right'>
                                                    Manaus, <span class='timedate' style='font-size: 12pt; font-weight:bold;'>___/___/_____. </span><span style='font-size: 9pt'></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td colspan='2'>
                                        <table align='left'>
                                            <tr>
                                                <td>
                                                    <img src='img/assinatura.png' width='180'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class='text-assign'>
                                                        Júlio Neto<br>
                                                        Infortread Telecom<br>
                                                        Gerente Geral
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                </td>
                                <td colspan='2' align='right'>
                                    <img src='img/carimbo-transp.png' width='180'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='4' class='footer-rec'>
                                    <p>Endereço Comercial: RUA DJALMA DUTRA - 44 - NOSSA SENHORA DAS GRACAS | MANAUS-AM - CEP 63.053-400 |</p>
                                    <p>Contato: (92)9271-7118 | Email: infortread.am@gmail.com | Site: www.infortread.com.br</p>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>

            ";

    return Pdf::loadHTML($html)->setPaper('A4', 'portrait') // Define o tamanho do papel
                        ->set_option('isHtml5ParserEnabled', true) // Garante compatibilidade com HTML5
                        ->set_option('isRemoteEnabled', true) // Permite imagens externas
                        ->set_option('isPhpEnabled', false)
                        ->download("Recibo de $nome.pdf");
    }

    //método para gerar um PDF para fornecedor
    public function gerarPdf(Request $request){

            $nome = $request->input('nome');
            $cpfcnpj = $request->input('cpfcnpj');
            $descricao = $request->input('descricao');
            $valorrecibo = $request->input('valor');

            //Remove os pontos
            $valorrecibosemponto = str_replace('.', '', $valorrecibo);

            // Substituir a vírgula por ponto
            $stringComPonto = str_replace(',', '.', $valorrecibosemponto);

            // Converter a string para float
            $valorFloat = floatval($stringComPonto);

            $numberInWords = $this->numberToWords($valorrecibo);

            $ano = Date('Y');
            $data = Date('d/m/Y');
            $hora = Date('H:i');

            $ultRecibo = DB::select('select * from tbrecibo order by id_recibo desc limit 1');

            if(count($ultRecibo) > 0){
                $ultimoNumero = $ultRecibo;
                $num = $ultimoNumero[0]->num_recibo;
                $pos = strpos($num, '/');
                if($pos == false){
                    $pos = 0;
                }

                $numRecibo = substr($num, 0, $pos);
                $numRecibo = $numRecibo + 1;

            }
            else{
                $numRecibo = 2975;
            }

            DB::insert('insert into tbrecibo values(null, ?,?,?,?,?,?,1)', [
                $numRecibo.'/'.date('Y'),
                $cpfcnpj,
                $descricao,
                $valorFloat,
                Date(now()),
                $numberInWords
            ]);


            $html = "
                <style type='text/css'>
                    html, body{
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
                    }


                    .line{
                        border-bottom: 1px dashed gray;
                        margin-bottom: 20px;
                        width: 180mm;
                    }
                    .main-body{
                        display:flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        width: 180mm;
                        height:280mm;
                        margin-left:10mm;
                        margin-top:10mm;
                    }
                    .container-pdf{
                        width: 100%;
                        height: 120mm;
                        background-color: #fff;
                        border: 1px solid rgb(0, 0, 0);
                        border-radius: 10px;
                        margin-bottom:5mm;
                        padding: 5mm;
                        align-items: center;
                        background: url('img/watermark.png');
                        background-position: center;
                        background-size: cover;
                        background-repeat: no-repeat;
                    }
                    .table-rec{
                        width:100%;
                    }
                    .table-rec td{
                        padding:1.3mm;
                    }
                    .table-rec .title-rec{
                        font-size:30pt;
                        font-weight: bold;
                    }
                    .table-rec .t-style{
                        border: 1px solid black;
                        border-radius:2mm;
                        width:50mm;
                        padding-left: 2mm;
                    }
                    .table-rec .valor span{
                        font-size:18pt;
                        font-weight: bold;
                    }
                    .table-rec .desc-ref{
                        border: 1px black solid;
                        border-radius:10px;
                        line-height:5mm;
                        min-height:45mm;
                        max-height: 45mm;
                        overflow: hidden;
                    }

                    table, th, td {
                        border:1px solid transparent;
                    }

                    .table-rec  .footer-rec{
                        border-top: 1px black dashed;
                        text-align: center;
                        font-size:9pt;
                        padding: 2px;
                        line-height:1mm;
                    }
                    .text-assign{
                        text-align:center;
                        border-top: 1px solid black;
                        font-size: 8pt;
                        position: absolute;
                        margin-top: -30px;
                        width:50mm;
                    }
                    .desc-text{
                        text-transform:uppercase;
                    }
                </style>
                <div class='main-body'>
                    <div class='container-pdf'>
                        <table class='table-rec' border='0'>
                            <tr>
                                <td class='title-rec'>Recibo</td>
                                <td align='center' class='t-style serie'>Nº $numRecibo/$ano</td>
                                <td></td>
                                <td align='center' class='t-style valor'>R$ <span> $valorrecibo</span></td>
                            </tr>
                            <tr>
                                <td colspan='4'></td>
                            </tr>
                            <tr height='50'>
                                <td colspan='4'>
                                    <div class='desc-ref'>

                                        <table style='width:100%; height:45mm'>
                                            <tr>
                                                <td>
                                                    Recebi(emos) de(a): <span>$nome</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    a importância de: <span class='desc-text' style='font-size: 9pt'>$numberInWords</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    Referente a: <span class='desc-text' style='font-size: 9pt'>$descricao</span>
                                                </td>
                                            </tr>
                                            <tr >
                                                <td align='right'>
                                                    Manaus, <span class='timedate' style='font-size: 12pt; font-weight:bold;'>___/___/_____. </span><span style='font-size: 9pt'></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td colspan='2'>
                                        <table align='left'>
                                            <tr>
                                                <td>
                                                    <img src='img/assinatura.png' width='180'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class='text-assign'>
                                                        Júlio Neto<br>
                                                        Infortread Telecom<br>
                                                        Gerente Geral
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                </td>
                                <td colspan='2' align='right'>
                                    <img src='img/carimbo-transp.png' width='180'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='4' class='footer-rec'>
                                    <p>Endereço Comercial: RUA DJALMA DUTRA - 44 - NOSSA SENHORA DAS GRACAS | MANAUS-AM - CEP 63.053-400 |</p>
                                    <p>Contato: (92)9271-7118 | Email: infortread.am@gmail.com | Site: www.infortread.com.br</p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class='line'></div>

                    <div class='container-pdf'>
                        <table class='table-rec' border='0'>
                            <tr>
                                <td class='title-rec'>Recibo</td>
                                <td align='center' class='t-style serie'>Nº $numRecibo/$ano</td>
                                <td></td>
                                <td align='center' class='t-style valor'>R$ <span> $valorrecibo</span></td>
                            </tr>
                            <tr>
                                <td colspan='4'></td>
                            </tr>
                            <tr height='50'>
                                <td colspan='4'>
                                    <div class='desc-ref'>

                                        <table style='width:100%; height:45mm'>
                                            <tr>
                                                <td>
                                                    Recebi(emos) de(a): <span>$nome</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    a importância de: <span class='desc-text' style='font-size: 9pt'>$numberInWords</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    Referente a: <span class='desc-text' style='font-size: 9pt'>$descricao</span>
                                                </td>
                                            </tr>
                                            <tr >
                                                <td align='right'>
                                                    Manaus, <span class='timedate' style='font-size: 12pt; font-weight:bold;'>___/___/_____. </span><span style='font-size: 9pt'></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td colspan='2'>
                                        <table align='left'>
                                            <tr>
                                                <td>
                                                    <img src='img/assinatura.png' width='180'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class='text-assign'>
                                                        Júlio Neto<br>
                                                        Infortread Telecom<br>
                                                        Gerente Geral
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                </td>
                                <td colspan='2' align='right'>
                                    <img src='img/carimbo-transp.png' width='180'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='4' class='footer-rec'>
                                    <p>Endereço Comercial: RUA DJALMA DUTRA - 44 - NOSSA SENHORA DAS GRACAS | MANAUS-AM - CEP 63.053-400 |</p>
                                    <p>Contato: (92)9271-7118 | Email: infortread.am@gmail.com | Site: www.infortread.com.br</p>
                                </td>
                            </tr>
                        </table>
                    </div>


                </div>

            ";

            return Pdf::loadHTML($html)->setPaper('A4', 'portrait') // Define o tamanho do papel
                                        ->set_option('isHtml5ParserEnabled', true) // Garante compatibilidade com HTML5
                                        ->set_option('isRemoteEnabled', true) // Permite imagens externas
                                        ->set_option('isPhpEnabled', false)
                                        ->download("Recibo de $nome.pdf");



        }
    }


