<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FuncoesAdaptadas;

use Illuminate\Support\Facades\DB;

use App\Models\CadastroVenda;

class Relatorio extends Model
{
    use HasFactory;
    use FuncoesAdaptadas;



    static function RelatorioVendaPeriodo($periodo = 7)
    {

        $periodo = "Now -" . $periodo . " days";

        $consulta['bruto'] = CadastroVenda::where('vendas.created_at', '>=', \Carbon\Carbon::parse($periodo))
            ->join("produtos_configuracoes AS pc", "pc.id", "=", "vendas.id_produto")
            ->sum('pc.valor_venda');

        $consulta['liquido'] = CadastroVenda::where('vendas.created_at', '>=', \Carbon\Carbon::parse($periodo))
            ->join("produtos_configuracoes AS pc", "pc.id", "=", "vendas.id_produto")
            ->sum('pc.valor_lucro');


        return $consulta;
    }
}
