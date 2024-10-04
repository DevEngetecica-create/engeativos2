<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use LaravelQRCode\Facades\QRCode;
use Illuminate\Http\Response;

use App\Models\{    
    AtivoExternoEstoque,
    AtivoExterno    
};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
//use SimpleSoftwareIO\QrCode\Facades\QrCode;
use LaravelQRCode\Facades\QRCode;
class QRCodeController extends Controller
{
    public function gerarQRCode(Request $request,$id)
    {
        
        
        $titles = AtivoExternoEstoque::with('id_ativo_externo');

        foreach ($titles as $title) {
            dd($title);
        }

        $detalhes = AtivoExterno::with('categoria')->find($id);

       // dd($detalhes->id);

        if (Session::get('obra')['id'] == null) {
            $itens = AtivoExternoEstoque::with('obra', 'situacao')->where('id_ativo_externo', $detalhes->id)->get();
        } else {

            $itens = AtivoExternoEstoque::where('id_obra', Session::get('obra')['id'])->with('obra', 'situacao')->where('id_ativo_externo', $detalhes->id)->get();
        }


        return  QRCode::url(route("ativo.externo.detalhes", $detalhes->id))
            ->setSize(18)
            ->setMargin(2)
            ->png();
    }


    public function gerarQRCodeAtvInt(Request $request,$id)
    {
        
        $detalhes = AtivosInterno::where('id', $request->id)->get();
        
        if (Session::get('obra')['id'] == null) {
            $itens = AtivoExternoEstoque::with('obra', 'situacao')->where('id_ativo_externo', $detalhes->id)->get();
        } else {

            $itens = AtivoExternoEstoque::where('id_obra', Session::get('obra')['id'])->with('obra', 'situacao')->where('id_ativo_externo', $detalhes->id)->get();
        }
       

        $qrCode = QrCode::size(200)->generate(route("https://sga-engeativos.com.br/admin/ativo/interno/1/show"));
        
         return view('pages.ativos.internos.show', compact('$qrCode'));
    }
    
    
    public function etiquetaQRCode(int $id)

    {
        $detalhes = AtivoExterno::with('categoria')->find($id);


 
         if (Session::get('obra')['id'] == null) {
             $itens = AtivoExternoEstoque::with('obra', 'situacao')->where('id_ativo_externo', $detalhes->id)->get();
         } else {
 
             $itens = AtivoExternoEstoque::where('id_obra', Session::get('obra')['id'])->with('obra', 'situacao')->where('id_ativo_externo', $detalhes->id)->get();
         }

        
         // Crie o QR code
        $qrCode = QrCode::size(200)->generate(route("ativo.externo.detalhes", $detalhes->id));

      
        /** Nome do Arquivo */
        $arquivoEtiqueta = 'etiqueta' . date("dmYHis") . '.pdf';

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | GEROU ETIQUETA: ' . $arquivoEtiqueta);


        /** Gerar PDF e mostra na tela */
       // $pdf = PDF::loadView('components.etiquetas.etiquetasAtivos', compact('detalhes', ['qrCode' => $qrCode]));
        $pdf = PDF::loadView('components.etiquetas.etiquetasAtivos',  compact('itens', 'qrCode'));
        return $pdf->stream($arquivoEtiqueta, array("Attachment" => false));
    }

}
