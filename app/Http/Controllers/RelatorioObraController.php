<?php

namespace App\Http\Controllers;

use App\Exports\ObrasExport;
use App\Models\CadastroEmpresa;
use App\Models\CadastroObra;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioObraController extends Controller
{
    public function index()
    {
        $empresas = CadastroEmpresa::all();
        return view('pages.relatorios.obra.index', compact('empresas'));
    }

    public function gerar(Request $request)
    {
        $tipo_arquivo = $request->tipo_arquivo;
        $periodo = $request->periodo;
        $inicio = $request->inicio;
        $fim = $request->fim;
        $empresa = $request->empresa;

        $obras = CadastroObra::query();

        if ($periodo == 'hoje') {
            $obras->whereDate('created_at', today());
        } elseif ($periodo == 'ontem') {
            $obras->whereDate('created_at', yesterday());
        } elseif ($periodo == '7dias') {
            $obras->whereBetween('created_at', [today()->subDays(6), now()]);
        } elseif ($periodo == '30dias') {
            $obras->whereBetween('created_at', [today()->subDays(29), now()]);
        } elseif ($periodo == '60dias') {
            $obras->whereBetween('created_at', [today()->subDays(59), now()]);
        } elseif ($periodo == '90dias') {
            $obras->whereBetween('created_at', [today()->subDays(89), now()]);
        } elseif ($periodo == '180dias') {
            $obras->whereBetween('created_at', [today()->subDays(179), now()]);
        } elseif ($periodo == '365dias') {
            $obras->whereBetween('created_at', [today()->subDays(364), now()]);
        } elseif ($periodo == '730dias') {
            $obras->whereBetween('created_at', [today()->subDays(729), now()]);
        } elseif ($periodo == 'outro') {
            $obras->whereDate('created_at', '>=', $inicio)
                ->whereDate('created_at', '<=', $fim);
        }

        if (!empty($empresa)) {
            $obras->whereHas('empresa', function ($query) use ($empresa) {
                $query->where('id_empresa', $empresa);
            });
        } elseif (!empty($empresa)) {
            $obras = CadastroObra::where('id_empresa', $empresa)->pluck('id');
            $obras->whereIn('id_obra', $obras);
        }


        $obras = $obras->get();

        $data_de_geracao = now()->format('d/m/Y H:i:s');

        if ($tipo_arquivo == 'pdf') {
            $pdf = PDF::loadView('pages.relatorios.obra.pdf', compact('obras', 'data_de_geracao'));
            $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
            return $pdf->stream('obras.pdf');
        } elseif ($tipo_arquivo == 'xls') {
            return Excel::download(new ObrasExport($periodo, $inicio, $fim, $empresa), 'obras.xlsx');
        } else {
            return redirect()->back();
        }
    }
}
