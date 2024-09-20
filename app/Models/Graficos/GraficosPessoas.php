<?php



namespace App\Models\Graficos;



use App\Models\Veiculo;

use App\Models\VeiculoAbastecimento;

use App\Models\VeiculoAcessorios;

use App\Models\VeiculoManutencao;

use App\Models\VeiculoIpva;

use App\Models\VeiculoSeguro;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;



class GraficosVeiculos extends Model



{

    public static function countVeiculos()

    {

        $countVeiculos = Veiculo::select('tipo')

            ->selectRaw('COUNT(tipo) AS totalTipo')

            ->whereNull('deleted_at')

            ->groupBy('tipo')

            ->get();



        /* $teste = [];



        foreach ($countVeiculos as $tipoVeiculo) {

            $teste = $tipoVeiculo->totalTipo;

            dd($teste);

        } */



        //dd($countVeiculos[0]['tipo']);



        $totalVeiculos = Veiculo::selectRaw('COUNT(*) AS totalTipo')

            ->whereNull('deleted_at')

            ->get();



        $result = [$totalVeiculos, $countVeiculos];



        return $result;

    }



    public static function totalManutencao()

    {

        $totalManutencao = VeiculoManutencao::select('veiculos.tipo')

            ->selectRaw('COUNT(veiculo_manutencaos.id) AS totalManutencao')

            ->join('veiculos', 'veiculo_manutencaos.veiculo_id', '=', 'veiculos.id')

            ->whereNull('veiculo_manutencaos.deleted_at')

            ->groupBy('veiculos.tipo')

            ->get();



        $totalGeralManutencao = VeiculoManutencao::selectRaw('COUNT(*) AS totalGeralManutencao')

            ->whereNull('deleted_at')

            ->get();



        $result = [$totalManutencao, $totalGeralManutencao];



        return $result;

    }



    public static function totalSeguros()

    {

        $totalSeguros = VeiculoSeguro::select('veiculos.tipo')

            ->selectRaw('COUNT(veiculo_seguros.id) AS totalSeguros')

            ->join('veiculos', 'veiculo_seguros.veiculo_id', '=', 'veiculos.id')

            ->whereNull('veiculo_seguros.deleted_at')

            ->groupBy('veiculos.tipo')

            ->get();



        $totalGeralSeguros = VeiculoSeguro::selectRaw('COUNT(*) AS totalGeralSeguros')

            ->whereNull('deleted_at')

            ->get();



        $result = [$totalSeguros, $totalGeralSeguros];



        return $result;

    }



    public static function totalIpvas()

    {

        $totalIpvas = VeiculoIpva::select('veiculos.tipo')

            ->selectRaw('COUNT(veiculo_ipvas.id) AS totalIpva')

            ->join('veiculos', 'veiculo_ipvas.veiculo_id', '=', 'veiculos.id')

            ->whereNull('veiculo_ipvas.deleted_at')

            ->groupBy('veiculos.tipo')

            ->get();



        $totalGeralIpva = VeiculoIpva::selectRaw('COUNT(*) AS totalGeralIpva')

            ->whereNull('deleted_at')

            ->get();



        $result = [$totalIpvas, $totalGeralIpva];



        return $result;

    }



    public static function custoManutencao()

    {

    }



    public static function valorTotalAtivos()

    {



        if (Session::get('obra')['id'] == null) {



            $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))

                ->whereNull('ativos_externos_estoque.deleted_at')

                ->get();

        } else {



            $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))

                ->whereNull('ativos_externos_estoque.deleted_at')

                ->where('id_obra', Session::get('obra')['id'])

                ->get();

        }



        return $valorTotalAtivos;

    }



    public static function totalAtivos()

    {

        if (Session::get('obra')['id'] !== null) {



            $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(id) AS totalAtivos')

                ->whereNull('ativos_externos_estoque.deleted_at')

                ->where('status', '!=', 9)

                ->where('id_obra', Session::get('obra')['id'])

                ->get();

        } else {



            $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(id) AS totalAtivo')

                ->whereNull('ativos_externos_estoque.deleted_at')

                ->where('status', '!=', 9)

                ->get();

        }



        return $totalAtivos;

    }

}

