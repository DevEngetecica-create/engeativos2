<?php

namespace App\Http\Controllers;

use App\Exports\VeiculosExport;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioController extends Controller
{
    public function index()
    {
        return view('pages.relatorio.index');
    }

    public function gerarVeiculosPdf()
    {
        $veiculos = Veiculo::all();

        $data_de_geracao = now()->format('d/m/Y H:i:s');

        $pdf = Pdf::loadView('pages.relatorio.pdf', compact('veiculos', 'data_de_geracao'));

        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);

        return $pdf->stream('teste.pdf');
    }

    public function gerarVeiculosXls()
    {
        return Excel::download(new VeiculosExport, 'users.xlsx');
    }
}
