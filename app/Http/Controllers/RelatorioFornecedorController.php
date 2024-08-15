<?php

namespace App\Http\Controllers;

use App\Exports\FornecedoresExport;
use App\Models\CadastroFornecedor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioFornecedorController extends Controller
{
    public function index()
    {
        $empresas = CadastroFornecedor::all();
        return view('pages.relatorios.fornecedor.index', compact('empresas'));
    }

    public function gerar(Request $request)
    {
        $tipo_arquivo = $request->tipo_arquivo;
        $periodo = $request->periodo;
        $inicio = $request->inicio;
        $fim = $request->fim;

        $fornecedores = CadastroFornecedor::query();

        if ($periodo == 'hoje') {
            $fornecedores->whereDate('created_at', today());
        } elseif ($periodo == 'ontem') {
            $fornecedores->whereDate('created_at', yesterday());
        } elseif ($periodo == '7dias') {
            $fornecedores->whereBetween('created_at', [today()->subDays(6), now()]);
        } elseif ($periodo == '30dias') {
            $fornecedores->whereBetween('created_at', [today()->subDays(29), now()]);
        } elseif ($periodo == '60dias') {
            $fornecedores->whereBetween('created_at', [today()->subDays(59), now()]);
        } elseif ($periodo == '90dias') {
            $fornecedores->whereBetween('created_at', [today()->subDays(89), now()]);
        } elseif ($periodo == '180dias') {
            $fornecedores->whereBetween('created_at', [today()->subDays(179), now()]);
        } elseif ($periodo == '365dias') {
            $fornecedores->whereBetween('created_at', [today()->subDays(364), now()]);
        } elseif ($periodo == '730dias') {
            $fornecedores->whereBetween('created_at', [today()->subDays(729), now()]);
        } elseif ($periodo == 'outro') {
            $fornecedores->whereDate('created_at', '>=', $inicio)
                ->whereDate('created_at', '<=', $fim);
        }

        $fornecedores = $fornecedores->get();

        $data_de_geracao = now()->format('d/m/Y H:i:s');

        if ($tipo_arquivo == 'pdf') {
            $pdf = PDF::loadView('pages.relatorios.fornecedor.pdf', compact('fornecedores', 'data_de_geracao'));
            $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
            return $pdf->stream('fornecedores.pdf');
        } elseif ($tipo_arquivo == 'xls') {
            return Excel::download(new FornecedoresExport($periodo, $inicio, $fim), 'fornecedores.xlsx');
        } else {
            return redirect()->back();
        }
    }
}
