<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Transferencia extends Model
{
    use HasFactory;

    //protected $connection = 'mysql2';

    static function getObrasSGA()
    {
        return DB::connection('mysql2')
            ->table('obra')
            ->select('*')
            ->get();
    }

    /** Get Empresas */
    static function getEmpresasSGA()
    {
        return DB::connection('mysql2')
            ->table('empresa')
            ->select('*')
            ->get();
    }

    /** Get Fornecedores */
    static function getFornecedoresSGA()
    {
        return DB::connection('mysql2')
            ->table('fornecedor')
            ->select('*')
            ->get();
    }

    /** Get Funcionários */
    static function getFuncionariosSGA()
    {
        return DB::connection('mysql2')
            ->table('funcionario')
            ->select('*')
            ->get();
    }

    /** Get Funcionários Funções */
    static function getFuncionariosFuncoesSGA()
    {
        return DB::connection('mysql2')
            ->table('funcionario_funcao')
            ->select('*')
            ->get();
    }

    /** Get Ativo Configuração */
    static function getAtivoConfiguracaoSGA()
    {
        return DB::connection('mysql2')
            ->table('ativo_configuracao')
            ->select('*')
            ->get();
    }

    /** Get Ativo Externo */
    static function getAtivoExternoSGA()
    {
        return DB::connection('mysql2')
        ->table('ativo_externo')
        ->select('*')
            ->groupBy('nome')
            ->get();
    }

    /** Get Ativo Externo by Nome */
    static function getAtivoExternoByNomeSGA($nome)
    {
        return DB::connection('mysql2')
        ->table('ativo_externo')
        ->select('*')
            ->where('nome', $nome)
            ->get();
    }

    /** Set Ativo Externo Situação */
    static function setAtivoExternoSituacao($status)
    {
        $situacao = 1;
        if ($status == '1') { // pendente
            $situacao = 1;
        }
        if ($status == '2') { // liberado
            $situacao = 2;
        }
        if ($status == '3') { // em transito
            $situacao = 3;
        }
        if ($status == '12') { // estoque
            $situacao = 4;
        }
        if ($status == '4') { // recebido
            $situacao = 5;
        }
        if ($status == '5') { // em operacao
            $situacao = 6;
        }
        if ($status == '7') { // transferidoo
            $situacao = 7;
        }
        if ($status == '9') { // devolvido
            $situacao = 8;
        }
        if ($status == '10') { // fora de operação
            $situacao = 9;
        }
        if ($status == '6') { // sem estoque
            $situacao = 13;
        }
        if ($status == '8') { // com defeito
            $situacao = 14;
        }
        if ($status == '11') { // liberado parcialmente
            $situacao = 15;
        }
        if ($status == '13') { // recebido parcialmente
            $situacao = 16;
        }
        if ($status == '14') { // aguardando autorizacao
            $situacao = 17;
        }
        if ($status == '15') { // recusado
            $situacao = 18;
        }
        return $situacao;
    }

 /** Get Veiculo */
    static function getVeiculoSGA()
    {
        return DB::connection('mysql2')
        ->table('ativo_veiculo')
        ->get();
    }

    /** Get Veiculo Seguro */
    static function getVeiculoSGASeguro($id_ativo_veiculo)
    {
        return DB::connection('mysql2')
        ->table('ativo_veiculo_seguro')
        ->where('id_ativo_veiculo', $id_ativo_veiculo)
            ->get();
    }
    /** Get Veiculo Seguro */
    static function getVeiculoSGAIpva($id_ativo_veiculo)
    {
        return DB::connection('mysql2')
        ->table('ativo_veiculo_ipva')
        ->where('id_ativo_veiculo', $id_ativo_veiculo)
            ->get();
    }

    /** Get Veiculo Seguro  */
    static function getVeiculoSGAAbastecnimento($id_ativo_veiculo)
    {
        return DB::connection('mysql2')
        ->table('ativo_veiculo_abastecimento')
        ->where('id_ativo_veiculo', $id_ativo_veiculo)
        ->get();
    }

    /** Get Veiculo Quilometragem */
    static function getVeiculoSGADistPerc($id_ativo_veiculo)
    {
        return DB::connection('mysql2')
        ->table('ativo_veiculo_quilometragem')
        ->where('id_ativo_veiculo', $id_ativo_veiculo)
        ->get();
    }

    /** Get Veiculo Manutencao */
    static function getVeiculoSGAManutencao($id_ativo_veiculo)
    {
        return DB::connection('mysql2')
        ->table('ativo_veiculo_manutencao')
        ->where('id_ativo_veiculo', $id_ativo_veiculo)
        ->get();
    }
}
