<?php

namespace App\Helpers;

use App\Models\CadastroObra;
use App\Models\FerramentalRequisicaoTransito;
use App\Models\FerramentalRetirada;
use App\Models\Preventiva;
use App\Models\VeiculoManutencao;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;

class Tarefa
{
    public static function countObras()
    {
        return CadastroObra::where('nome_fantasia', null)->orderByDesc('id')->count();
    }

    public static function obras()
    {
        return CadastroObra::where('nome_fantasia', null)->orderByDesc('id')->limit(10)->get();
    }

    public static function funcionariosBloqueados($obra)
    {
        $now = Carbon::now();
        if (is_int($obra) && $obra > 0) {
            $bloqueio = FerramentalRetirada::with('funcionario')
                ->where('data_devolucao_prevista', '<', $now->format('Y-m-d H:i:s'))
                ->where('status', [2, 5])
                ->where('id_obra', $obra)
                ->orderByDesc('id')
                ->limit(10)
                ->get();
        } else {
            $bloqueio = FerramentalRetirada::with('funcionario')
                ->where('data_devolucao_prevista', '<', $now->format('Y-m-d H:i:s'))
                ->where('status', [2, 5])
                ->orderByDesc('id')
                ->limit(10)
                ->get();
        }

        return $bloqueio;
    }

    
    public static function funcionariosBloqueadosRetirada($funcionario)
    {
        $now = Carbon::now();

        $bloqueio = FerramentalRetirada::with('funcionario')
            ->where('id_funcionario', $funcionario)
            ->where('data_devolucao_prevista', '<', $now->format('Y-m-d H:i:s'))
            ->where('status', [2, 5])
            ->get();

        return count($bloqueio);

        // if (count($bloqueio) > 0) {
        //     return count($bloqueio);
        // } else {
        //     return count($bloqueio);
        // }

    }



    public static function manutencoes()
    {
        $manutencoes = VeiculoManutencao::with('veiculo', 'quilometragens')->limit(10)
            ->orderBy('id', 'desc')
            ->get();
        return $manutencoes;
    }

    public static function horimetro($obra)
    {
        
        $maiorValor = DB::table('veiculo_manutencaos')
        ->whereNull('veiculo_quilometragems.deleted_at')
        ->select('veiculos.placa', 'veiculo_manutencaos.horimetro_proximo', 'veiculos.codigo_da_maquina')
        ->selectRaw('MAX(veiculo_quilometragems.quilometragem_nova) * 100 / MAX(veiculo_manutencaos.horimetro_proximo) AS valorCalc')
        ->selectRaw('MAX(veiculo_manutencaos.horimetro_proximo) - MAX(veiculo_quilometragems.quilometragem_nova) AS horasRest')
        ->selectRaw('MAX(veiculo_manutencaos.horimetro_proximo) AS proxRev')
        ->join('veiculo_quilometragems', 'veiculo_manutencaos.veiculo_id', '=', 'veiculo_quilometragems.veiculo_id')
        ->join('veiculos', 'veiculo_manutencaos.veiculo_id', '=', 'veiculos.id')
        ->orderByDesc('veiculo_manutencaos.horimetro_proximo')
        ->groupBy('veiculos.placa', 'veiculo_manutencaos.horimetro_proximo', 'veiculos.codigo_da_maquina')
        ->havingRaw('horasRest > 0 AND horasRest <= 50')
        ->get();
    
    
        
        return $maiorValor;
    }

 public static function dataVencimentaManutencao($obra)
{
    $maiorValor = DB::table('veiculo_manutencaos')
        ->select('veiculo_manutencaos.id AS idVeiculoManutencao', 'veiculos.placa', 'veiculo_manutencaos.horimetro_proximo', 'veiculo_manutencaos.data_de_execucao',
            'veiculos.codigo_da_maquina', 'veiculo_manutencaos.data_de_vencimento', 'veiculo_manutencaos.veiculo_id AS idVerManutencao')
        ->selectRaw("DATEDIFF(veiculo_manutencaos.data_de_vencimento, veiculo_manutencaos.data_de_execucao) AS totalDias")
        ->selectRaw("DATEDIFF(veiculo_manutencaos.data_de_vencimento, NOW()) AS diasRestantes")
        ->selectRaw('DATEDIFF(veiculo_manutencaos.data_de_vencimento, NOW()) * 100 / DATEDIFF(veiculo_manutencaos.data_de_vencimento, veiculo_manutencaos.data_de_execucao) AS porDias')
        ->join('veiculo_quilometragems', 'veiculo_manutencaos.veiculo_id', '=', 'veiculo_quilometragems.veiculo_id')
        ->join('veiculos', 'veiculo_manutencaos.veiculo_id', '=', 'veiculos.id')
        ->whereNull('veiculo_manutencaos.deleted_at')
        ->orderBy('diasRestantes')
        ->groupBy('veiculo_manutencaos.id', 'veiculo_manutencaos.data_de_vencimento', 'veiculos.placa', 'veiculo_manutencaos.horimetro_proximo', 'veiculo_manutencaos.data_de_execucao', 'veiculos.codigo_da_maquina', 'veiculo_manutencaos.veiculo_id')  // Include 'veiculo_manutencaos.veiculo_id' in GROUP BY
        ->havingRaw('diasRestantes > 0 AND diasRestantes <= 30')
        ->get();

    return $maiorValor;
}






    public static function transferencias($obra)
    {
        if (isset($obra)) {
            $transferencias = FerramentalRequisicaoTransito::with('requisicao', 'ativo', 'obraOrigem', 'obradestino', 'status')->where('status', 5)
                ->Where('id_obra_origem', $obra)
                ->orWhere('id_obra_destino', $obra)
                ->get();
        } else {
            $transferencias = FerramentalRequisicaoTransito::with('requisicao', 'ativo', 'obraOrigem', 'obradestino', 'status')->where('status', 5)
                ->get();
        }
        return $transferencias;
    }
}
