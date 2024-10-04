<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Config;
use App\Notifications\NotificatioEmail;
use App\Helpers\Tratamento;
use App\Models\Notification;
use App\Models\ConfiguracaoNotificacaoEmail;
use Exception;

use App\Models\TransferenciaFerramenta;
use App\Models\{AtivoExterno, AtivoExernoStatus, AtivoExternoEstoque, CadastroObra, CustomLog, TransferenciaFerramentaObra, User};


use Illuminate\Support\Facades\Storage;

use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\URL;

class TransferenciaFerramentalController extends Controller
{
    //
    public function index()
    {
        $id_obra = Session::get('obra')['id'];

        $listPage = 10;

        $search = request('search');
        if ($id_obra == null) {


            $transferenciaObras = TransferenciaFerramentaObra::with('obraDestino', 'obraOrigem', 'usuario', 'situacao')
                ->when(request('search') != null, function ($query) {
                    return  $query->where('id_obraOrigem', 'like', '%' . request('search') . '%');
                })
                ->orderBy("id", "desc")
                ->paginate(12);
        } else {

            $transferenciaObras = TransferenciaFerramentaObra::with('obraDestino', 'obraOrigem', 'usuario', 'situacao')
                ->when(request('search') != null, function ($query) {
                    return  $query->where('id_obraOrigem', 'like', '%' . request('search') . '%');
                })

                ->orderBy("id", "desc")
                ->where('id_obraDestino', $id_obra)
                ->paginate(12);
        }

        //limpar as sessões do checkbox
        Session::forget('selectedIds');
        Session::forget('patrimonio');
        Session::forget('titulo');

        $obras = CadastroObra::all();

        return view('pages.ativos.externos.transferencia.index', compact('transferenciaObras', 'obras'));
    }

    public function bloqueio()
    {
        $id_obra = Session::get('obra')['id'];

        $consulta = [];

        if ($id_obra == null) {

            $consulta =
                [
                    'message' =>  "Você não pode transferir todas as ferremantas para uma única obra!!!!",
                    'type' => 'error'
                ];
        } else {

            $informacoes = AtivoExternoEstoque::with('ativo_externo', 'configuracao', 'obra', 'situacao')
                ->where('status', [6, 3])
                ->where('id_obra', Session::get('obra')['id'])
                ->get();

            $consulta =
                [
                    'message' =>  $informacoes,
                    'type' => 'success'
                ];
        }
        return response()->json($consulta);
    }

    
    public function create(Request $request)
    {


        $id_obra = Session::get('obra')['id'];

        $listPage = 10;

        $search = request('search');

        if ($id_obra == null) {

            $ativos = AtivoExternoEstoque::with('configuracao', 'situacao')->when(request('search') != null, function ($query) {
                return  $query->where('patrimonio', 'like', '%' . request('search') . '%')
                    ->orWhere('calibracao', 'LIKE', '%' . request('search') . '%');
            })->orWhereHas('configuracao', function ($query) use ($search) {
                $query->where('titulo', 'like', "%$search%");
            })

                ->paginate(10);
        } else {

            $ativos = AtivoExternoEstoque::with('configuracao', 'situacao')->when(request('search') != null, function ($query) {
                return  $query->where('patrimonio', 'like', '%' . request('search') . '%')
                    ->orWhere('calibracao', 'LIKE', '%' . request('search') . '%');
            })->orWhereHas('configuracao', function ($query) use ($search) {
                $query->where('titulo', 'like', "%$search%");
            })
                ->where('id_obra', $id_obra)
                ->paginate(10);
        }
        $obras = CadastroObra::all();

        return view('pages.ativos.externos.transferencia.form', compact('ativos', 'obras'));
    }

    public function store(Request $request)
    {
       
   
        $obra_origem = $request->id_obraOrigem;
        $obra_destino = $request->id_obraDestino;

        try {
           
            
            if ($request->id_obraOrigem == "") {

                $notification = array(
                    'title' => "Atenção!!!",
                    'message' => "Selecione uma obra!!!!",
                    'type' => 'error'
                );
    
                return redirect()->route('ativo.externo.transferencia.create')->with($notification);
            }
            // 
                    //salvar a transferencia
                    $transferenciaObra = new TransferenciaFerramentaObra([
                        'id_obraOrigem'             => $obra_origem,
                        'id_obraDestino'            => $obra_destino,
                        'id_usuario'                =>  Auth::user()->id,
                        'motivo_transferencia'      => $request->motivo_transferencia,
                        'status'                    => 18,
                    ]);
    
                    $transferenciaObra->save();
    
                    $arrayPatrimonio = [];
                    $patrimonio = [];
    
                    //looping para salvar os itens da tansferencia
                    foreach ($request->id_ativo_externo as $key => $value) {
                       
                        $transferenciaItem = new TransferenciaFerramenta();
                        $transferenciaItem->id_ativo_externo_estoque    = $value;
                        $transferenciaItem->id_obraOrigem               = $obra_origem;
                        $transferenciaItem->id_obraDestino              = $obra_destino;
                        $transferenciaItem->id_transferencia            = $transferenciaObra->id;
                        $transferenciaItem->id_usuario                  = Auth::user()->id;
              
                        $transferenciaItem->save();
    
                        //identificar o patrimonio que esta sendo transferido
                        $patrimonio = AtivoExternoEstoque::where('id', $value)->first();
    
                        //atualizar varias registros ao mesmo tempo/ sequencia
                        AtivoExternoEstoque::where('id', $value)->update([
                            'id_obra' => $obra_destino,
                                 
                        ]);
    
                        //monta array para a lista dos patriminios
                        array_push($arrayPatrimonio,  $patrimonio->patrimonio);
                    }
    
                    //converte a arryaPatrimonio em linha para salvar na tabela de logs
                    $stringPatrimonio = implode(', ', $arrayPatrimonio);
    
    
    
                    //Envia Notificação por e-mail no endereço para nova Manutencão
                    $email_config = Config::where('id', 1)->first();
    
                    $title = "Houve uma desmobilização de obra . Lembre-se de desativar a Obra";
    
                    //link de acesso
                    $link = env('APP_URL') . "admin/ativo/externo/tranferencia/show/" . $transferenciaObra->id;
    
                    //mensagem de envio
                    $menssagem = "Transferencia da(s) ferramenta(s) " . $stringPatrimonio . " da obra " . $obra_origem_nome->nome_fantasia . " para a obra " . $obra_destino_nome->nome_fantasia;
    
                    $email_config->notify(new NotificatioEmail($title, $email_config->email,  $menssagem, $link));
    
                    //Noticação na dashboard              
    
                    $notication = new Notification(
                        [
                            "id_servico" => $transferenciaObra->id,
                            "id_obra" => $obra_origem,
                            "tipo" => 'transf_ferramenta',
                            "mensagem" => $menssagem,
                            "status"    => "unread",
                            "link_acesso" => $link
                        ]
                    );
    
                    $notication->save();
    
                    //limpar as sessões do checkbox
                    /*Session::forget('selectedIds');
                    Session::forget('patrimonio');
                    Session::forget('titulo');*/
    
                    //conteúdo do log
                    $detalhes = Auth::user()->email . ' | TRANSFERIU A FERRAMENTA | PATRIMONIO: ' . $stringPatrimonio . 'da obra ' . $obra_origem_nome->nome_fantasia . 'para a obra ' . $obra_destino_nome->nome_fantasia . '| DATA: ' . date('Y-m-d H:i:s');
                    Log::channel('main')->info($detalhes);
    
                    //salvar os dados na tabela de log
                    $customLog = new CustomLog(
                        [
                            'id_usuario'    => Auth::user()->id,
                            'id_modulo'     => 32,
                            'metodo'        => 'create',
                            'tipo'          => 'success',
                            'descricao'     => $detalhes,
                            'ip_acesso'     => Tratamento::getRealIpAddr(),
                            'detalhes'      => $detalhes
                        ]
                    );
    
                    $customLog->save();
    
                    $notification = array(
                        'title' => "Sucesso!!!",
                        'message' => "As ferramentas transferidas com sucesso!!!",
                        'type' => 'success'
                    );
    
                    return redirect()->route('ativo.externo.transferencia')->with($notification);
                
            
            
        } catch (Exception $e) {


            //salvar os dados na tabela de log
            $customLog = new CustomLog(
                [
                    'id_usuario'    => Auth::user()->id,
                    'id_modulo'     => 32,
                    'metodo'        => 'create',
                    'tipo'          => 'error',
                    'ip_acesso'     => Tratamento::getRealIpAddr(),
                    'detalhes' => Auth::user()->email . ' | ' . $e
                ]
            );

            $customLog->save();

            $notification = array(
                'title' => "Error!!!",
                'message' => "Erro ao transferiri as ferramentas!!!",
                'type' => 'error'
            );

            return redirect()->route('ativo.externo.transferencia')->with($notification);
        }
    }




    public function show(Request $request, $id)
    {

        $show_transferencia = TransferenciaFerramentaObra::find($id);

        $itens_transferidos = AtivoExternoEstoque::with('ativo_externo', 'configuracao', 'obra', 'situacao')
            ->join('ativos_trensfer_ferramentas', 'ativos_externos_estoque.id', 'ativos_trensfer_ferramentas.id_ativo_externo_estoque')
            ->where('ativos_trensfer_ferramentas.id_transferencia', $id)
            ->get();

        return view('pages.ativos.externos.transferencia.show', compact('show_transferencia', 'itens_transferidos'));
    }

    public function romaneio_transferencia_feramenta(Request $request, int $id)
    {
        $romaneio_transferencia = TransferenciaFerramentaObra::with('obraOrigem', 'obraDestino')
            ->where('id_obraOrigem', $id)
            ->get();

        $romaneio_itens_transferidos = TransferenciaFerramenta::select(
            'ativos_externos.titulo',
            'ativos_externos_estoque.patrimonio',
            'ativos_externos_estoque.status as status_ativo',
            'ativos_transferencias.id as id_trasferencia',
            'ativos_externos_status.titulo as titulo_status',
            'obras_destino.nome_fantasia as destino_nome_fantasia',
            'obras_origem.nome_fantasia as origem_nome_fantasia'
        )
            ->join('ativos_externos_estoque', 'ativos_trensfer_ferramentas.id_ativo_externo_estoque', 'ativos_externos_estoque.id')
            ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', 'ativos_externos.id')
            ->join('obras as obras_destino', 'ativos_trensfer_ferramentas.id_obraDestino', 'obras_destino.id')
            ->join('ativos_transferencias', 'ativos_trensfer_ferramentas.id_transferencia', 'ativos_transferencias.id')
            ->join('obras as obras_origem', 'ativos_transferencias.id_obraOrigem', 'obras_origem.id')
            ->join('ativos_externos_status', 'ativos_transferencias.status', 'obras_origem.id')
            ->where('ativos_trensfer_ferramentas.id_transferencia', $id)
            ->get();


        //** Nome do Arquivo  ativos_trensfer_ferramentas
        $nome_romaneio_transferencia = 'romaneio_transferencia' . date("dmYHis") . '.pdf';



        if (php_sapi_name() !== 'cli') {
            header('Content-type: application/pdf');
        }

        /** Gerar PDF */
        $pdf = new Dompdf(
            [
                'enable_remote' => true
            ]
        );

        $pdf->setPaper("A4");
        $pdf = PDF::loadView('components.romaneio.romaneio_transferencia_ferramentas', compact('romaneio_itens_transferidos', 'romaneio_transferencia'));

        /** Upload do Termo Automatico */
        $content = $pdf->download()->getOriginalContent();
        Storage::put('public/uploads/romaneios/transferencia/' .  $nome_romaneio_transferencia, $content);

        // Exibe o PDF no navegador web
        return $pdf->stream();
    }
}
