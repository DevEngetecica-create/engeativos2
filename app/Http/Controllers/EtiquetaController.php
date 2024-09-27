<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Exception;

class EtiquetaController extends Controller
{
    public function imprimirEtiquetas(Request $request)
    {
        try {
            $connector = new WindowsPrintConnector("TSC TTP-244CE");
            echo "Conector criado com sucesso.\n";
            $printer = null;
            $printer = new Printer($connector);
            echo "Impressora criada com sucesso.\n";
            $printer->text("Hello World!");
            echo $printer->text("Hello World!");
            $printer->cut();
            $printer->close();
            echo "Impressora criada com sucesso 2.\n";
        } catch (Exception $e) {
            echo "Erro ao imprimir: " . $e->getMessage() . "\n";
        }
        
}
}
