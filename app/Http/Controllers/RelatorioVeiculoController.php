<?php

namespace App\Http\Controllers;

use App\Exports\VeiculosExport;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioVeiculoController extends Controller
{
    public function index()
    {
        return view('pages.relatorios.veiculo.index');
    }

    public function gerar(Request $request)
    {
        $tipo_arquivo = $request->tipo_arquivo;
        $tipo_veiculo = $request->tipo;
        $periodo = $request->periodo;
        $inicio = $request->inicio;
        $fim = $request->fim;

        $veiculos = Veiculo::where('tipo', $tipo_veiculo);

        if ($periodo == 'hoje') {
            $veiculos->whereDate('created_at', today());
        } elseif ($periodo == 'ontem') {
            $veiculos->whereDate('created_at', today()->subDay());
        } elseif ($periodo == '7dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(6), now()]);
        } elseif ($periodo == '30dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(29), now()]);
        } elseif ($periodo == '60dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(59), now()]);
        } elseif ($periodo == '90dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(89), now()]);
        } elseif ($periodo == '180dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(179), now()]);
        } elseif ($periodo == '365dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(364), now()]);
        } elseif ($periodo == '730dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(729), now()]);
        } elseif ($periodo == 'outro' && $inicio && $fim) {
            $veiculos->whereBetween('created_at', [$inicio, $fim]);
        }

        $veiculos = $veiculos->get();

        $data_de_geracao = now()->format('d/m/Y H:i:s');

        if ($request->tipo_arquivo) {
            if ($tipo_arquivo == 'pdf') {
                $pdf = PDF::loadView('pages.relatorios.veiculo.pdf', compact('veiculos', 'data_de_geracao'));
                $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
                return $pdf->stream('veiculos.pdf');
            } elseif ($tipo_arquivo == 'xls') {
                return Excel::download(new VeiculosExport($tipo_veiculo, $periodo, $inicio, $fim), 'veiculos.xlsx');
            } else {
                return redirect()->back();
            }
        } else {

            return view(
                'pages.relatorios.veiculo.index',
                compact(
                    'veiculos',
                    'data_de_geracao',
                    'tipo_veiculo',
                    'periodo'
                )
            );
        }
    }
}
