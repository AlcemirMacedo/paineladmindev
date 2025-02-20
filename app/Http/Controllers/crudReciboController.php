<?php
namespace App\Http\Controllers;

use App\Models\reciboModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class crudReciboController extends Controller
{
    public function viewGrid(){
        $sql = DB::table('tbrecibo')
                ->leftjoin('tb_fornecedores', 'tb_fornecedores.cpfcnpj', '=', 'tbrecibo.cpfcnpj_recibo')
                ->orderBy('id_recibo', 'desc')
                ->paginate(9);
                $total_recibo = DB::table('tbrecibo')->count();
        return view('gridrecibo', compact('sql', 'total_recibo'));
    }

    public function exluirRecibo($value){

        try {
            $sql = DB::delete('delete from tbrecibo where id_recibo = ?', [$value]);
            return redirect('/gridrecibo');
        } catch (Exception $ex) {
            return back()->with('error', 'Não foi possível excluir este recibo');
        }
    }

    public function viewFormRecibo($value){

        try {
            $sql = DB::select('select * from tbrecibo inner join tb_fornecedores on cpfcnpj = cpfcnpj_recibo where id_recibo = ?', [$value]);
            return view('formRecibo',['sql'=>$sql]);
        } catch (Exception $ex) {
            return back()->with('error', 'Não foi possível acessar o recibo');
        }
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

    public function editarRecibo(Request $request){

        $id = $request->input('id');
        $valor = $request->input('valor');
        $descricao = $request->input('decricao');

        //Remove os pontos
        $valorrecibosemponto = str_replace('.', '', $valor);

        // Substituir a vírgula por ponto
        $stringComPonto = str_replace(',', '.', $valorrecibosemponto);

        // Converter a string para float
        $valorFloat = floatval($stringComPonto);

        $numberInWords = $this->numberToWords($valor);

        try {
            $sql = DB::update('update tbrecibo set desc_recibo = ?, valor_recibo = ?, data_recibo = ?, vlr_extenso = ?  where id_recibo = ?', [
                $descricao,
                $valorFloat,
                Date(now()),
                $numberInWords,
                $id
            ]);

            return redirect('/gridrecibo')->with('success', 'Alterado com sucesso!');

        } catch (Exception $ex) {

            Log::error('Erro ao tentar alterar'.$ex->getMessage());
            return back()->with('error', 'Não foi possível cadastrar');

        }
    }

    public function searchRecibo(Request $request){

        $sql = reciboModel::join('tb_fornecedores', 'tb_fornecedores.cpfcnpj', '=', 'tbrecibo.cpfcnpj_recibo')
        ->Where('num_recibo', 'like', '%'.$request->search.'%')
        ->orWhere('cpfcnpj', 'like', '%'.$request->search.'%')->paginate(10);

        $total_recibo = count($sql);
        if(count($sql) > 0){
            return view('gridrecibo', [
                'sql' => $sql,
                'total_recibo' => $total_recibo
            ]);
        }
        else{
            return back()->with('attention','Registro não encontrado!');
        }
    }
}
