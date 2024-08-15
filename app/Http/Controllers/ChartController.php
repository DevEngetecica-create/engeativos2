<?php

namespace App\Http\Controllers;


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
use App\Models\Charts;
use App\Models\Anexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Database\Seeders\VeiculoSeguroSeeder;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Charts\SampleChart;

class ChartController extends Controller
{
    public function Charts()
    {
        $dataAtual = Carbon::now();

        $QtdeTotalVeiculos = Veiculo::selectRaw('count(tipo)')
        ->where('tipo', '!=', 'maquinas')
        ->groupBy('id')->get();
        
        $valoresGraficosVeiculos = Charts::obterDados();

   
       return view('pages.dashboard.index', compact('QtdeTotalVeiculos','valoresGraficosVeiculos', 'dataAtual'));

       
        
    }
}