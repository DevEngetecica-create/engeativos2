<?php 

namespace App\Traits;

Trait FuncoesAdaptadas
{
    static function formata_moeda($expressao){
        $expressao = str_replace("R$ ", "", $expressao);
        $expressao = str_replace(",", '.', $expressao);
        return $expressao;
    }

    static function formata_moeda_reverse($expressao = null)
    {
        if(!$expressao) return "R$ 0,00";
        return "R$ " . number_format($expressao ?? '0.00', '2', ',', '.');
    }

    static function dd(...$data)
    {
        foreach ($data as $dt) {
            echo "<pre>";
            echo print_r($dt, true);
            echo "</pre>";
        }
        exit;
    }

    static function dv(...$data)
    {
        foreach ($data as $dt) {
            echo "<pre>";
            echo var_dump($dt, true);
            echo "</pre>";
        }
        exit;
    }

    public function importar_cliente_nome($nome){        
        if(!$nome) return null;
        return mb_strtoupper(str_replace("+", "", $nome));        
    }


    public function formata_data($data){
        return date("d/m/Y H:i", strtotime($data));
    }
    
}