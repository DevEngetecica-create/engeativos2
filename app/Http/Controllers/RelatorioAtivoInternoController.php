<?php

namespace App\Http\Controllers;

use App\Exports\VeiculosExport;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioAtivoInternoController extends Controller
{
    public function index()
    {
        return view('pages.relatorios.ativo-interno.index');
    }
    
    public function gerar(Request $request)
    {

        

    }
}
