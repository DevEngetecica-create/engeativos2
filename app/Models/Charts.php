<?php

namespace App\Models;

use App\Models\CadastroEmpresa;
use App\Models\CadastroObra;
use App\Models\MarcaMaquina;
use App\Models\ModeloMaquina;
use App\Models\Veiculo;
use App\Models\VeiculoAbastecimento;
use App\Models\VeiculoDepreciacao;
use App\Models\VeiculoIpva;
use App\Models\VeiculoManutencao;
use App\Models\VeiculoQuilometragem;
use App\Models\VeiculoSeguro;
use App\Models\Anexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Database\Seeders\VeiculoSeguroSeeder;

use App\Http\Controllers\Controller;
use App\Charts\SampleChart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charts extends Model
{
    public static function valoresGraficosVeiculos()
    {
        $valorVeiculos = Veiculo::select('tipo', DB::raw('tipo, SUM(CAST(REPLACE(valor_fipe, ".", "") AS DECIMAL(13, 2))) as sumtotalVeiculos'))
            ->groupBy('tipo')
            ->whereNull('deleted_at')
            ->get();
            
            return $valorVeiculos;
       
    }

    public static function contaModelos()
    {

        $contarModelo = Veiculo::selectRaw('modelo, COUNT(id) AS qtdeModelo')
            ->groupBy('modelo')
            //->whereNotNull('modelo')
            ->whereNull('deleted_at')
            ->get();

        return $contarModelo;
    }

    public static function valorTotalVeiculos()
    {

        $valorTotalVeiculos = Veiculo::select('tipo', DB::raw('SUM(CAST(REPLACE(valor_fipe, ".", "") AS DECIMAL(13, 2))) as totalVeiculos'))
            ->where('tipo', '!=', 'maquinas')
            ->groupBy('tipo')
            ->get();

        return $valorTotalVeiculos;
    }
    
    public static function TotalVeiculos()
    {
        $valorTotalVeiculos = Veiculo::select('tipo', DB::raw('SUM(CAST(REPLACE(valor_fipe, ".", "") AS DECIMAL(13, 2))) as totalVeiculos'))
            ->whereNull('deleted_at')
            ->groupBy('tipo') // Adding GROUP BY clause here
            ->get();
        return $valorTotalVeiculos;
    }


    public static function valorTotalMaquinas()
    {

        $valorTotalMaquinas = Veiculo::select('tipo', DB::raw('SUM(CAST(REPLACE(valor_fipe, ".", "") AS DECIMAL(13, 2))) as totalMaquinas'))
            ->where('tipo', '=', 'maquinas')
            ->groupBy('tipo')
            ->get();

        return $valorTotalMaquinas;
    }

    public static function totalModelo()
    {
        $totalModelo = Veiculo::selectRaw('count(id) as totalModelos')
            //->whereNotNull('modelo')
            ->whereNull('deleted_at')
            ->get();
        return $totalModelo;
    }

    public static function ativosExternos()
    {


        $cumulative = 0;

        $anoAtual = date('Y'); // Obtém o ano atual

        // Consulta para calcular a quantidade criada por mês
        $ativosExternos = DB::table('ativos_externos_estoque')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes')
            ->selectRaw('SUM(CASE WHEN deleted_at IS NULL THEN 1 ELSE 0 END) as quantidade_criados')
            ->whereYear('created_at', $anoAtual)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Consulta para calcular a quantidade anterior por mês
        $anteriores = DB::table('ativos_externos_estoque')
            ->selectRaw('DATE_FORMAT(data_descarte, "%Y-%m") as mes')
            ->selectRaw('SUM(CASE WHEN status = 9 AND data_descarte IS NOT NULL THEN 1 ELSE 0 END) as quantidade_anterior')
            ->where('status', 9)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Mapeia os resultados e adiciona a quantidade acumulada criada
        $cumulative = 0;
        $resultado = $ativosExternos->map(function ($ativo) use ($anteriores, &$cumulative) {
            $anterior = $anteriores->where('mes', $ativo->mes)->first();
            $ativo->quantidade_anterior = $anterior ? $anterior->quantidade_anterior : 0;
            $ativo->quantidade_acumulada_criados = $cumulative += $ativo->quantidade_criados;
            return $ativo;
        });

        /* $cumulative = 0;
        $anoAtual = date('Y'); // Obtém o ano atual

        $ativosExternos = DB::table('ativos_externos_estoque')
            ->select([DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'), DB::raw('SUM(CASE WHEN ativos_externos_estoque.deleted_at IS NULL THEN 1 ELSE 0 END) as quantidade_criados')])
            ->selectRaw('SUM(CASE WHEN status = 9 AND data_descarte IS NOT NULL THEN 1 ELSE 0 END) as quantidade_status_9')
            ->whereYear('ativos_externos_estoque.created_at', $anoAtual)
            ->groupBy('mes')
            ->orderBy('mes')
            ->orderBy('quantidade_status_9')
            ->get();

        foreach ($ativosExternos as $ativo) {
            $ativo->quantidade_acumulada_criados = $cumulative += $ativo->quantidade_criados;
        }

        $anteriores = DB::table('ativos_externos_estoque AS p2')
            ->select(DB::raw('DATE_FORMAT(data_descarte, "%Y-%m") as mes'))
            ->select(DB::raw('SUM(CASE WHEN ativos_externos_estoque.deleted_at IS NULL THEN 1 ELSE 0 END) as quantidade_anterior'))
            //->selectRaw('COUNT(*) as quantidade_anterior')
            ->where('status', 9)
          
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $resultado = $ativosExternos->map(function ($ativo) use ($anteriores) {
            $anterior = $anteriores->where('mes', $ativo->mes)->first();
            $ativo->quantidade_anterior = $anterior ? $anterior->quantidade_anterior : 0;

            return $ativo;
        });
 */

        if ($resultado->count()) {


            $incI = 0;
            foreach ($resultado as $keyAtv => $arrDataAtvExterno) {
                $countAtivos[$incI] = $arrDataAtvExterno;

                $incI++;
            }

            //Convert array to json form...
            return json_encode($countAtivos);

            foreach (($resultado) as $chave => $key) {

                $dadosVeiculos = json_decode($key, true);

                //dd($valorVeiculos);
                $countAtivos = [];
                $incI = 0;
                foreach ($dadosVeiculos as $chaveAtivos => $valorAtivos) {
                    $countAtivos[$chaveAtivos] = $valorAtivos['mes'];
                    $countAtivos[$chaveAtivos] = $valorAtivos['quantidade_total'];
                    $countAtivos[$chaveAtivos] = $valorAtivos['quantidade_status_9'];
                    $incI++;
                }
            }

            return $countAtivos;
        } else {

            return null;
        }
    }

    public static function vencimentoIPVA()
    {
        $vencimentoIPVA = VeiculoIpva::select('veiculos.placa', 'veiculos.codigo_da_maquina', 'veiculo_ipvas.data_de_vencimento', 'veiculo_ipvas.id as idIPVA')
            ->selectRaw("DATEDIFF(veiculo_ipvas.data_de_vencimento, NOW()) AS diasRestantesIpva")
            ->selectRaw('DATEDIFF(veiculo_ipvas.data_de_vencimento, NOW()) * 100 / DATEDIFF(veiculo_ipvas.data_de_vencimento, data_de_pagamento ) as porcDiasIpva')
            ->join('veiculos', 'veiculo_ipvas.veiculo_id', '=', 'veiculos.id')
            ->havingRaw('diasRestantesIpva > 0 AND diasRestantesIpva <= 30')
            ->get();

        return $vencimentoIPVA;
    }

    public static function vencimentoSeguro()
    {
        $vencimentoSeguro = VeiculoSeguro::select('veiculos.placa', 'veiculos.codigo_da_maquina', 'veiculo_seguros.carencia_final', 'veiculo_seguros.id as idSeguro')
            ->selectRaw("DATEDIFF(veiculo_seguros.carencia_final, NOW()) AS diasRestantesSeguro")
            ->selectRaw("DATEDIFF(veiculo_seguros.carencia_final, veiculo_seguros.carencia_inicial) AS diferencaDiasCarencia")
            ->selectRaw('DATEDIFF(veiculo_seguros.carencia_final, NOW()) * 100 / DATEDIFF(veiculo_seguros.carencia_final, carencia_inicial ) as porcDiasSeguro')
            ->join('veiculos', 'veiculo_seguros.veiculo_id', '=', 'veiculos.id')
            ->havingRaw('diasRestantesSeguro > 0 AND diasRestantesSeguro <= 30')
            ->get();

        return $vencimentoSeguro;
    }

    public static function calibracaoAtivosExternos()
    {

        $calibracaoAtivosExternos = AtivoExternoEstoque::select([
            DB::raw('SUM(CASE WHEN calibracao = "Sim" AND ativos_externos_estoque.deleted_at IS NULL THEN 1 ELSE 0 END) as quantidade_calibrados'),
            DB::raw('SUM(CASE WHEN ativos_externos_estoque.deleted_at IS NULL THEN 1 ELSE 0 END) as quantidade_total')
        ])
            ->get();

        return $calibracaoAtivosExternos;
    }

    public static function qtdeAtivosObras()
    {
        $qtdeAtivosObras = DB::table('ativos_externos_estoque')
            ->select('obras.nome_fantasia')
            ->selectRaw('COUNT(ativos_externos_estoque.id_obra) as qtdeAtivosObras')
            ->selectRaw('CAST(SUM(CASE WHEN calibracao = "Sim" AND obras.deleted_at IS NULL THEN 1 ELSE 0 END) AS SIGNED) as qtdeCalibrados  ')
            ->whereNull('ativos_externos_estoque.deleted_at')
            ->join('obras', 'ativos_externos_estoque.id_obra', '=', 'obras.id')
            ->groupBy('obras.nome_fantasia')
            ->get();

        return $qtdeAtivosObras;
    }
    
    
    public static function vencimentoCalibracao()
    {
        $vencimentoCalibracao = DB::table('ativos_externos_estoque')
            ->join('anexos', 'ativos_externos_estoque.id', '=', 'anexos.id_item')
            ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
            ->join('obras', 'ativos_externos_estoque.id_obra', '=', 'obras.id')
            ->where('nome_modulo', '=', 'ativo_externo')
            ->where('ativos_externos_estoque.status', '!=', 9)
            ->whereNull('ativos_externos_estoque.deleted_at')
            ->whereIn('anexos.data_vencimento', function($query) {
                $query->select(DB::raw('MAX(data_vencimento)'))
                      ->from('anexos')
                      ->groupBy('id_item');
            })
            ->select(
                'ativos_externos_estoque.patrimonio',
                'ativos_externos.titulo',
                'obras.nome_fantasia',
                DB::raw('MAX(anexos.data_vencimento) as max_data_vencimento')
            )
            ->selectRaw("DATEDIFF(MAX(anexos.data_vencimento), NOW()) AS diasRestantesCalibracao")
            ->selectRaw("DATEDIFF(MAX(anexos.data_vencimento), NOW()) * 100 / DATEDIFF(MAX(anexos.data_vencimento), anexos.data_calibracao) as porcDiasCalibracao")
            ->groupBy(
                'ativos_externos_estoque.patrimonio',
                'ativos_externos.titulo',
                'obras.nome_fantasia',
                'anexos.data_calibracao'
            )
            ->havingRaw('diasRestantesCalibracao <= 30')
            ->get();
    
        return $vencimentoCalibracao;
    }


}























































