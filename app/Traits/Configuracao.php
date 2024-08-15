<?php

namespace App\Traits;

use App\Models\AtivoExternoEstoque;
use App\Models\AtivosInterno;

/*
 * Estrutura de configurações extras que não necessitam do banco de dados
 * @acoes_permitidas
 * @integrador
 * @tipo_pix
 */

Trait Configuracao
{

    static function PatrimonioSigla()
    {
        return "ENG";
    }

    static function PatrimonioAtual()
    {
        // Obtém o último número da tabela Interno
        $lastInternal = AtivosInterno::select('patrimonio')
        ->orderByDesc('id')
        ->first();

        // Obtém o último número da tabela Externo
        $lastExternal = AtivoExternoEstoque::select('patrimonio')
        ->orderByDesc('id')
        ->first();

        // Obtém o maior número entre as tabelas
        $lastNumber = 0;

        if ($lastInternal && $lastInternal->patrimonio) {
            $lastNumber = max($lastNumber, intval(str_replace(self::PatrimonioSigla(), '', $lastInternal->patrimonio)));
        }

        if ($lastExternal && $lastExternal->patrimonio) {
            $lastNumber = max($lastNumber, intval(str_replace(self::PatrimonioSigla(), '', $lastExternal->patrimonio)));
        }

        // Incrementa o número para obter o próximo
        $nextNumber = $lastNumber + 1;

        return $nextNumber;

        // $atual = "ENG999";
        // $atual_numero = str_replace(self::PatrimonioSigla(), "", $atual);
        // return $atual_numero;

    }


    /* Ações Permitidas */
    static function acoes_permitidas()
    {
        return [
            'Visualizar' => 'view',
            'Adicionar' => 'add',
            'Editar' => 'edit',
            'Excluir' => 'delete',
            'Outros' => 'other'
        ];
    }

    /* Integradores de Pagamento */
    static function integrador()
    {
        return [
            'Cielo',
            'Rede',
            'Stone Pagamentos',
            'Mercado Pago'
        ];
    }

    /* Configurações para Tipos de Pix */
    static function tipo_pix()
    {
        return [
            'Celular',
            'CPF',
            'CNPJ',
            'E-mail',
            'Chave Aleatória'
        ];
    }


    /* Estados */
    static function estados()
    {
        return [
                'AC' => 'Acre',
                'AL' => 'Alagoas',
                'AP' => 'Amapá',
                'AM' => 'Amazonas',
                'BA' => 'Bahia',
                'CE' => 'Ceará',
                'DF' => 'Distrito Federal',
                'ES' => 'Espírito Santo',
                'GO' => 'Goiás',
                'MA' => 'Maranhão',
                'MT' => 'Mato Grosso',
                'MS' => 'Mato Grosso do Sul',
                'MG' => 'Minas Gerais',
                'PA' => 'Pará',
                'PB' => 'Paraíba',
                'PR' => 'Paraná',
                'PE' => 'Pernambuco',
                'PI' => 'Piauí',
                'RJ' => 'Rio de Janeiro',
                'RN' => 'Rio Grande do Norte',
                'RS' => 'Rio Grande do Sul',
                'RO' => 'Rondônia',
                'RR' => 'Roraima',
                'SC' => 'Santa Catarina',
                'SP' => 'São Paulo',
                'SE' => 'Sergipe',
                'TO' => 'Tocantins'
        ];

    }

}

