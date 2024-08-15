<?php

namespace App\Http\Controllers;

use App\Models\{
    Anexo,
    AtivoExterno,
    AtivoExternoEstoque,
    AtivoExternoEstoqueItem,
    CadastroEmpresa,
    FerramentalRetirada,
    FerramentalRetiradaItens,
    FerramentalRetiradaAutenticar,
    CadastroFuncionario,
    CadastroObra,
    Config,
    FerramentalRetiradaItem,
    FerramentalRetiradaItemDevolver
};

use Illuminate\Http\Request;
use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRFpdf;
use Illuminate\Support\Facades\{
    Auth,
    Session,
    Storage
};

//relatório da devolução
use DOMDocument;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use RealRashid\SweetAlert\Facades\Alert;

use App\Traits\{
    Configuracao,
    FuncoesAdaptadas
};

use Illuminate\Support\Facades\Log;

use App\Helpers\Tratamento;

use Barryvdh\DomPDF\Facade\Pdf;
use DB;

use Exception;

//Notification mail
use App\Notifications\NotificaRetirada;

//Notification telegram
use App\Notifications\NotificaRetiradaTelegram;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class FerramentalRetiradaController extends Controller
{

   public function index(Request $request)
    {


        $id_obra = Session::get('obra')['id'] ?? null;

        $solicitante = $request->solicitante;

        if ($id_obra !== null && $id_obra > 0) {

            if ($solicitante) {

                $retiradas = FerramentalRetirada::where('id_obra', $id_obra)
                    ->where('id_funcionario', $solicitante)
                    ->with('obra', 'usuario', 'funcionario', 'situacao')
                    ->orderByDesc('id', 'desc')
                    ->paginate(10);
            } else {

                $retiradas = FerramentalRetirada::where('id_obra', $id_obra)
                    ->with('obra', 'usuario', 'funcionario', 'situacao')
                    ->orderByDesc('id', 'desc')
                    ->paginate(10);
            }

            $solicitantes = CadastroFuncionario::where('id_obra', $id_obra)
                    ->with('obra')
                    ->get();
        } else {

            if ($solicitante) {

                $retiradas = FerramentalRetirada::with('obra', 'usuario', 'funcionario', 'situacao')
                    ->where('id_funcionario', $solicitante)
                    ->orderByDesc('id', 'desc')
                    ->paginate(10);

            } else {

                $retiradas = FerramentalRetirada::with('obra', 'usuario', 'funcionario', 'situacao')
                    ->orderByDesc('id', 'desc')
                    ->paginate(10);
            }

            $solicitantes = CadastroFuncionario::get();
        }

        return view('pages.ferramental.retirada.index', compact('retiradas', 'solicitantes'));
    }

    public function list(Request $request)
    {

        $search = $request->search;

        if (Session::get('obra')['id']) {

            if ($request->search == null) {

                $estoques = AtivoExternoEstoque::where('id_obra', Session::get('obra')['id'])
                    ->with('configuracao', 'ativo_externo', 'obra', 'situacao')
                    ->where('status', 4)
                    ->whereNull('deleted_at')
                    ->paginate(12);
            } else {

                $estoques = AtivoExternoEstoque::where(function ($query) use ($search) {
                        $query->where('patrimonio', 'like', "%$search%");
                    })
                    ->orWhereHas('configuracao', function ($query) use ($search) {
                        $query->where('titulo', 'like', "%$search%");
                    })

                    ->with('configuracao', 'ativo_externo', 'obra', 'situacao')
                    ->where('status', 4)
                    ->where('id_obra', Session::get('obra')['id'])
                    ->whereNull('deleted_at')
                    ->paginate(12);
            }
        } else {


            if ($request->search == null) {

                $estoques = AtivoExternoEstoque::when(request('search') != null, function ($query) {
                        return  $query->where('patrimonio', 'like', '%' . request('search') . '%');
                    })
                    ->whereNull('deleted_at')
                    ->paginate(12);

                $limparPaginacao = false;
            } else {

                $estoques = AtivoExternoEstoque::where(function ($query) use ($search) {
                        $query->where('patrimonio', 'like', "%$search%");
                    })
                    ->orWhereHas('configuracao', function ($query) use ($search) {
                        $query->where('titulo', 'like', "%$search%");
                    })
                    ->with('configuracao', 'ativo_externo', 'obra', 'situacao')
                    ->where('status', 4)
                    ->whereNull('deleted_at')
                    ->paginate(12);
            }
        }


        return view('pages.ferramental.retirada._partials.listRetirada', compact('estoques'));
    }

    public function preecherCampos(Request $request)
    {

        $selecao = $request->selecao;

        if (Session::get('obra')['id'] == null) {
            $informacoes = AtivoExternoEstoque::with('ativo_externo', 'configuracao', 'obra', 'situacao')
                ->where('patrimonio', $selecao)
                ->get();
        } else {
            $informacoes = AtivoExternoEstoque::where('id_obra', Session::get('obra')['id'])
                ->where('patrimonio', 'like', "ENG18")
                ->with('ativo_externo', 'configuracao', 'obra', 'situacao')
                ->get();
        }

        return response()->json($informacoes);
    }




    public function create(Request $request)
    {

        $search = $request->search;

        if (Session::get('obra')['id']) {


            $obras = CadastroObra::where('id', Session::get('obra')['id'])->where('status_obra', 'Ativo')->get();
            $funcionarios = CadastroFuncionario::where('id_obra', Session::get('obra')['id'])->where('status', 'Ativo')->get();
        } else {

            $obras = CadastroObra::where('status_obra', 'Ativo')->get();
            $funcionarios = CadastroFuncionario::where('status', 'Ativo')->get();
        }
        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();



        return view('pages.ferramental.retirada.form', compact('funcionarios', 'obras', 'empresas'));
    }

    public function store(Request $request)
    {
        //dd($request->id_ativo_externo);
        $request->validate(
            [
                'id_obra' => 'required',
                'id_funcionario' => 'required',
                'id_ativo_externo' => 'required',
                'devolucao_prevista' => 'required'
            ],
            [
                'id_obra.required' => 'Qual obra você deseja efetivar esta retirada?',
                'id_funcionario.required' => 'Você precisa selecionar o funcionário.',
                'id_ativo_externo.required' => 'Nenhum item foi selecionado para retirada.',
                'devolucao_prevista.required' => 'Preencha a data e hora para devolução.'
            ]
        );

        //dd($request->id_ativo_externo);

        $retirada = new FerramentalRetirada();
        $retirada->id_relacionamento = null;
        $retirada->id_obra = $request->id_obra;
        $retirada->id_usuario = Auth::user()->id ?? 1;
        $retirada->id_funcionario = $request->id_funcionario;
        $retirada->data_devolucao_prevista = $request->devolucao_prevista;
        $retirada->data_devolucao = null;
        $retirada->status = 2;
        $retirada->observacoes = $request->observacoes ?? NULL;
        $retirada->save();

        $id_retirada = $retirada->id;


        if ($id_retirada) {

            if ($request->id_ativo_externo) {
                foreach ($request->id_ativo_externo as $key => $value) {
                    //  dd($value);
                    $retirada_item = new FerramentalRetiradaItem();
                    $retirada_item->id_ativo_externo = $value;
                    $retirada_item->id_retirada = $id_retirada;
                    $retirada_item->status = 2;
                    $retirada_item->save();
                    
                    //atualizar varias registros ao mesmo tempo/ sequencia
                    AtivoExternoEstoque::where('id', $value)->update([
                        'status' => 6,
                        // Adicione outros campos e valores que deseja atualizar
                    ]);
                }
            }

            //Registro no Log
            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | ADD RETIRADA | ID: ' . $id_retirada . ' | DATA: ' . date('Y-m-d H:i:s'));

            //Notificação por e-mail no endereço cadastrado nas configurações de notificações
            $email_config = Config::where('id', 1)->first();
            $email_config->notify(new NotificaRetirada($email_config->email));

            // Notificação por telegram no canal registrado (API depende de https)
            /*if (env('APP_ENV')=== 'production') {
            Notification::route('telegram', env('TELEGRAM_CHAT_ID'))
                    ->notify(new NotificaRetiradaTelegram('atendimento@codigosdigitais.com.br'));//$email_config->email
            }*/

            return redirect()->route('ferramental.retirada.detalhes', $id_retirada)->with('success', 'Sua retirada foi registrada com sucesso!');
        } else {
            return redirect()->route('ferramental.retirada')->with('error', 'Não foi possível registrar sua solicitação, entre em contato com suporte.');
        }
    }

    public function items(Request $request, int $id)
    {

       // dd($request->id_ferramentas_retiradas);

        if (!$request->id_ferramentas_retiradas) {
            return redirect()->route('ferramental.retirada')->with('fail', 'Não foi possível localizar esta Retirada.');
        }

        $detalhes = FerramentalRetirada::getRetiradaItems($request->id_ferramentas_retiradas);

        return view('pages.ferramental.retirada.items', compact('detalhes'));
    }

    public function show(int $id)
    {
        if (!$id) {
            return redirect()->route('ferramental.retirada')->with('fail', 'Não foi possível localizar esta Retirada.');
        }

        $detalhes = FerramentalRetirada::getRetiradaItems($id);


        if (!$detalhes) {
            return redirect()->route('ferramental.retirada')->with('fail', 'Não foi possível localizar esta Retirada.');
        }

        return view('pages.ferramental.retirada.show', compact('detalhes'));
    }

    public function edit($id)
    {
        $obras = CadastroObra::where('status_obra', 'Ativo')->get();

        $funcionarios = CadastroFuncionario::where('status', 'Ativo')->get();

        $itens = FerramentalRetirada::getRetiradaItems($id);

        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();

        return view('pages.ferramental.retirada.edit', compact('obras', 'itens', 'funcionarios', 'empresas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'id_obra' => 'required',
                'id_funcionario' => 'required',
                'id_ativo_externo' => 'required',
                'devolucao_prevista' => 'required'
            ],
            [
                'id_obra.required' => 'Qual obra você deseja efetivar esta retirada?',
                'id_funcionario.required' => 'Você precisa selecionar o funcionário.',
                'id_ativo_externo.required' => 'Nenhum item foi selecionado para retirada.',
                'devolucao_prevista.required' => 'Preencha a data e hora para devolução.'
            ]
        );

        $retirada = FerramentalRetirada::find($id);
        $retirada->id_relacionamento = null;
        $retirada->id_obra = $request->id_obra;
        $retirada->id_usuario = Auth::user()->id ?? 1;
        $retirada->id_funcionario = $request->id_funcionario;
        $retirada->data_devolucao_prevista = $request->devolucao_prevista;
        $retirada->data_devolucao = null;
        $retirada->status = 2;
        $retirada->observacoes = $request->observacoes ?? NULL;
        $retirada->update();

        $id_retirada = $retirada->id;


        if ($id_retirada) {

            if ($request->id_ativo_externo) {
                foreach ($request->id_ativo_externo as $key => $value) {
                    FerramentalRetiradaItem::where('id', $value)->delete();
                }
            }

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | EDIT RETIRADA | ID: ' . $id_retirada . ' | DATA: ' . date('Y-m-d H:i:s'));

            return redirect()->route('ferramental.retirada.detalhes', $id_retirada)->with('success', 'Sua retirada foi modificada com sucesso!');
        } else {
            return redirect()->route('ferramental.retirada')->with('fail', 'Não foi possível registrar sua solicitação, entre em contato com suporte.');
        }
    }

    public function destroy($id)
    {
        $retirada = FerramentalRetirada::findOrFail($id);
        $retirada->status = 6;

        $itens = FerramentalRetiradaItem::where('id_retirada', $retirada->id)->get();

        foreach ($itens as $item) {
            $restart = AtivoExternoEstoque::where('id', $item->id_ativo_externo)->first();
            $restart->update(['status' => 4]);

            $item->update(['status' => 6]);
        }



        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | CANCEL RETIRADA | ID: ' . $id . ' | DATA: ' . date('Y-m-d H:i:s'));

        if ($retirada->save()) {
            return redirect()->route('ferramental.retirada')->with('success', 'Retirada cancelada com sucesso');
        } else {
            return redirect()->route('ferramental.retirada')->with('error', 'Não foi possível cancelar a retirada, entre em contato com suporte!');
        }
    }

    public function consultarCredenciaisTermo(Request $request, $id)
    {

        $detalhes = FerramentalRetirada::getRetiradaItems($id);
        $funcionario = CadastroFuncionario::find($detalhes->id_funcionario);
        $termo_responsabilidade = 'termo_retirada_' . date("dmYHis") . '.pdf';

        $password = $funcionario->password;

       // dd($funcionario->id);

        if ($password == $request->password) {

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | ASSINATURA VERIFICA: ' . $termo_responsabilidade);

            $mensagemSuccess = 'success';

            return response()->json($mensagemSuccess);

        } elseif ($request->password == '' or $request->password == null) {

            $mensagemFail = 'Digite a sua senha';

            return response()->json($mensagemFail);

        } else {

            $mensagemFail = 'Senha incorreta!!!';

            return response()->json($mensagemFail);
        }
        
    }


    public function assinarTermo(Request $request, $id)
    {

        $detalhes = FerramentalRetirada::getRetiradaItems($id);

        $user = CadastroFuncionario::find($detalhes->id_funcionario);

        $termo_responsabilidade = 'termo_retirada_' . date("dmYHis") . '.pdf';

        if ($request->devolver_itens == "" or $request->devolver_itens == null) {
            // /** Salvar autenticação do termo */
            $autenticar = new FerramentalRetiradaAutenticar();
            $autenticar->id_retirada = $detalhes->id ?? null;
            $autenticar->id_usuario = Auth::user()->id ?? 1;
            $autenticar->id_funcionario = $detalhes->id_funcionario ?? null;
            $autenticar->termo_responsabilidade = $termo_responsabilidade;
            $autenticar->save();

            $confirmarRetirada = FerramentalRetirada::where('status', $id);

            $confirmarRetirada->update([
                'status' => 2, // Status => 2 é para informar que foi entregue a ferramenta e gerado o Termo
            ]);
        } else {


            //Atualiza a devolução autenticada
            $updateDevolucao = FerramentalRetiradaAutenticar::where('id_retirada', $id);
            $updateDevolucao->update([
                "entrega"           => "SIM",
            ]);

            // dd($request->id_ativo_externo);

            if ($request->id_ativo_externo) {

                $status_retirada = null;

                foreach ($request->id_ativo_externo as $key => $value) {

                    // dd($value);

                    if ($value == 2) {
                        $status_retirada == 2;
                    } else {
                        $status_retirada = $value;
                    }

                    $description = $request->description ?? null;

                    $dom = new DOMDocument();
                    $dom->loadHTML($description, 9);

                    $images = $dom->getElementsByTagName('img');

                    foreach ($images as $key_img => $img) {
                        $data = base64_decode(explode(',', explode(';', $img->getAttribute('src'))[1])[1]);
                        $image_name = "/storage/devolucao/" . time() . $key_img . '.png';
                        file_put_contents(public_path() . $image_name, $data);

                        $img->removeAttribute('src');
                        $img->setAttribute('src', $image_name);
                    }
                    $description = $dom->saveHTML();

                    /** Salvar Retirada Item */
                    $item = FerramentalRetiradaItemDevolver::find($key);
                    $item->status = $value ?? 2;
                    $item->save();

                    /** Salvar Ativo Externo */
                    $estoque = AtivoExternoEstoque::find($item->id_ativo_externo);
                    $estoque->status = 4; // em estoque
                    $estoque->save();
                }

                /** Salvar Retirada */
                $retirada = FerramentalRetirada::find($item->id_retirada);
                $retirada->devolucao_observacoes = $description ?? null;
                $retirada->data_devolucao = now();
                $retirada->updated_at = now();
                $retirada->status = $status_retirada;
                $retirada->save();

                if ($retirada->id_relacionamento !== null) {
                    /** Salvar Retirada do Relacionamento */
                    $relacionamento = FerramentalRetirada::find($retirada->id_relacionamento);
                    $relacionamento->devolucao_observacoes = $description ?? null;
                    $relacionamento->data_devolucao = now();
                    $relacionamento->updated_at = now();
                    $relacionamento->status = $status_retirada;
                    $relacionamento->save();
                }


                $userLog = Auth::user()->email;
                Log::channel('main')->info($userLog . ' | SALVOU DEVOLUÇÃO: ' . $retirada->id);

                return redirect()->route('ferramental.retirada')->with('success', 'Devolução efetuada com sucesso!');
            }
        }


        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | TERMO AUTENICADO: ' . $termo_responsabilidade);
    }


    /**
     * Termo de Responsabilidade
     * Upload do termo automaticamente via storage
     */

    public function gerar_termo_digital(Request $request, int $id)
    {
        /** Conteudo do PDF */
        $detalhes = FerramentalRetirada::getRetiradaItems($id);



        $termo = FerramentalRetirada::find($id);
        $termo->termo_responsabilidade_gerado = now();
        $termo->save();

        $detalhes = FerramentalRetirada::getRetiradaItems($id);
        //Váriaveis do QRcode
        $options = new QROptions;

        $options->version          = 7;
        $options->outputInterface  = QRFpdf::class;
        $options->scale            = 7;
        $options->fpdfMeasureUnit  = 'mm';
        $options->bgColor          = [222, 222, 222];
        $options->drawLightModules = true;
        $options->outputBase64     = true;
        $options->moduleValues     = [
            // finder
            QRMatrix::M_FINDER_DARK    => [0, 63, 255],    // dark (true)
            QRMatrix::M_FINDER_DOT     => [0, 63, 255],    // finder dot, dark (true)
            QRMatrix::M_FINDER         => [255, 255, 255], // light (false)
            // alignment
            QRMatrix::M_ALIGNMENT_DARK => [255, 0, 255],
            QRMatrix::M_ALIGNMENT      => [255, 255, 255],
            // timing
            QRMatrix::M_TIMING_DARK    => [255, 0, 0],
            QRMatrix::M_TIMING         => [255, 255, 255],
            // format
            QRMatrix::M_FORMAT_DARK    => [67, 191, 84],
            QRMatrix::M_FORMAT         => [255, 255, 255],
            // version
            QRMatrix::M_VERSION_DARK   => [62, 174, 190],
            QRMatrix::M_VERSION        => [255, 255, 255],
            // data
            QRMatrix::M_DATA_DARK      => [0, 0, 0],
            QRMatrix::M_DATA           => [255, 255, 255],
            // darkmodule
            QRMatrix::M_DARKMODULE     => [0, 0, 0],
            // separator
            QRMatrix::M_SEPARATOR      => [255, 255, 255],
            // quietzone
            QRMatrix::M_QUIETZONE      => [255, 255, 255],
        ];

        $qrcodeEntregarItens = (new QRCode($options))->render("Entregue à: " . $detalhes->funcionario . " - MATRICULA: " . $detalhes->funcionario_matricula);

        //dd($request->devolver_itens);

        if ($request->devolver_itens == "false") {
            // Gera o Qrcode
            $qrcodeDevolverItens = "";
            
        }else{
            
            $qrcodeDevolverItens = (new QRCode($options))->render("Devolvido por: " . $detalhes->funcionario . " - MATRICULA: " . $detalhes->funcionario_matricula);
        }
        
        
        if (php_sapi_name() !== 'cli') {
            header('Content-type: application/pdf');
        }

        /** Nome do Arquivo */
        $termo_responsabilidade = 'termo_retirada_' . $detalhes->funcionario_matricula . '.pdf';

        //Gera o Log
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | GEROU TERMO RETIRADA: ' . $termo->termo_responsabilidade_gerado);

        /** Gerar PDF */

        $pdf = PDF::loadView('components.termo.termo_retirada', compact('detalhes', 'qrcodeEntregarItens', 'qrcodeDevolverItens'));

        /** Upload do Termo Automatico */
        $content = $pdf->download()->getOriginalContent();
        
        Storage::put('public/uploads/termos_retirada/' . $termo_responsabilidade, $content);

        // Exibe o PDF no navegador web
        return $pdf->stream($termo_responsabilidade, array("Attachment" => false));
    }



    /** Download do Termo Atual */
    public function termo_download(int $id)
    {

        $termo_responsabilidade = (FerramentalRetirada::getRetiradaItems($id)->anexo->arquivo) ?? null;

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | DOWNLOAD TERMO RETIRADA: ' . $termo_responsabilidade);

        if ($termo_responsabilidade === null) {
            Alert::error('Atenção', 'Não foi possível localizar o arquivo solicitado.');
            return redirect(route('ferramental.retirada'));
        }
        return Storage::download('public/uploads/termos_retirada/' . $termo_responsabilidade);
    }

    /**
     * Listagem de Retiradas
     * Via service-side
     */
    // public function lista(Request $request)
    // {
    //     if ($request->ajax()) {

    //         $listaRetirada = FerramentalRetirada::getRetirada();

    //         return DataTables::of($listaRetirada)

    //             ->editColumn('created_at', function ($row) {
    //                 return ($row->created_at) ? Tratamento::FormatarData($row->created_at) : '-';
    //             })
    //             ->editColumn('devolucao', function ($row) {
    //                 return ($row->data_devolucao_prevista) ? Tratamento::FormatarData($row->data_devolucao_prevista) : '-';
    //             })
    //             ->editColumn('status', function ($row) {
    //                 $status_classe = Tratamento::getStatusRetirada($row->status)['classe'];
    //                 $status_titulo = Tratamento::getStatusRetirada($row->status)['titulo'];
    //                 $status = "<div class='badge badge-" . $status_classe . "'>" . $status_titulo . "</div>";
    //                 return $status;
    //             })
    //             ->editColumn('acoes', function ($row) {

    //                 $dropdown = '<div class="dropdown"><div class="btn-group"><button class="btn btn-gradient-danger btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Selecione</button><ul class="dropdown-menu" aria-labelledby="drodownAcoes">';

    //                 /** Devolver Itens */
    //                 if ($row->status == "2" || $row->status == "5" && $row->termo_responsabilidade_gerado) {
    //                     $dropdown .= '<li><a class="dropdown-item" href="' . route('ferramental.retirada.devolver', $row->id) . '"><i class="mdi mdi-redo-variant"></i> Devolver Itens</a></li>';
    //                 }

    //                 /** Gerar Termo */
    //             if ($row->status == "1" && !$row->termo_responsabilidade_gerado) {
    //                     $dropdown .= '<li><a class="dropdown-item" href="' . route('ferramental.retirada.termo', $row->id) . '"><i class="mdi mdi-access-point-network"></i> Gerar Termo</a></li>';
    //                 }

    //                 /** Baixar Termo */
    //             if ($row->status == "2" or $row->status == "3" && $row->termo_responsabilidade_gerado) {
    //                     $dropdown .= '<li><a class="dropdown-item" href="' . route('ferramental.retirada.termo', $row->id) . '"><i class="mdi mdi-download"></i> Baixar Termo</a></li>';
    //                 }

    //             if ($row->status == "1" && !$row->termo_responsabilidade_gerado) {

    //                 /** Modificar Retirada */
    //                 $dropdown .= '<li><a class="dropdown-item" href="' . route('ferramental.retirada.editar', $row->id) . '"><i class="mdi mdi-pencil"></i> Modificar Retirada</a></li>';

    //                 /** Cancelar Retirada */
    //                 $dropdown .= '<li><form action="' . route('ferramental.retirada.destroy', $row->id) . '" method="POST">'.csrf_field().'<input type="hidden" name="_method" value="DELETE"><button type="submit" class="dropdown-item" onclick="return confirm(\'Deseja realmente cancelar a retirada?\')"><i class="mdi mdi-cancel"></i> Cancelar Retirada</button></form></li>';

    //             }

    //                 $dropdown .= '<li><a class="dropdown-item" href="' . route('ferramental.retirada.detalhes', $row->id) . '"><i class="mdi mdi-minus"></i> Detalhes</a></li> ';

    //                 /** Ver Termo */
    //             if ($row->status == "3" or $row->status == "4" && $row->termo_responsabilidade_gerado) {
    //                 $dropdown .= '<li><a class="dropdown-item" href="' . route('ferramental.retirada.termo', $row->id) . '"><i class="mdi mdi-printer"></i> Ver Termo</a></li></ul></div>';
    //             }

    //                 return $dropdown;
    //             })
    //             ->rawColumns(['acoes', 'status'])
    //             ->make(true);
    //     }
    // }

    /**
     * Devolver Itens
     * getRetiradaItems id_retirada
     */
    public function devolver(int $id)
    {
        if (!$id) {
            Alert::error('Atenção', 'Não foi possível localizar esta Retirada.');
            return redirect(route('ferramental.retirada'));
        }

        $detalhes = FerramentalRetirada::getRetiradaItems($id);

        if (!$detalhes) {
            Alert::error('Atenção', 'Não foi possível localizar esta Retirada.');
            return redirect(route('ferramental.retirada'));
        }

        return view('pages.ferramental.retirada.devolver', compact('detalhes'));
    }

    /**
     * Salvar devolução
     */

    public function devolver_salvar(Request $request)
    {

        if ($request->id_ativo_externo) {

            $status_retirada = null;

            foreach ($request->id_ativo_externo as $key => $value) {

                if ($value == 2) {
                    $status_retirada = 2;
                } else {
                    $status_retirada = $value;
                }

                /** Salvar Retirada Item */
                $item = FerramentalRetiradaItemDevolver::find($key);
                $item->status = $value ?? 1;
                $item->save();

                /** Salvar Ativo Externo */
                $estoque = AtivoExternoEstoque::find($item->id_ativo_externo);
                $estoque->status = 4; // em estoque
                $estoque->save();
            }

            /** Salvar Retirada */
            $retirada = FerramentalRetirada::find($item->id_retirada);
            $retirada->devolucao_observacoes = $request->observacoes ?? null;
            $retirada->data_devolucao = now();
            $retirada->updated_at = now();
            $retirada->status = $status_retirada;
            $retirada->save();

            if ($retirada->id_relacionamento !== null) {
                /** Salvar Retirada do Relacionamento */
                $relacionamento = FerramentalRetirada::find($retirada->id_relacionamento);
                $relacionamento->devolucao_observacoes = $request->observacoes ?? null;
                $relacionamento->data_devolucao = now();
                $relacionamento->updated_at = now();
                $relacionamento->status = $status_retirada;
                $relacionamento->save();
            }

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | SALVOU DEVOLUÇÃO: ' . $retirada->devolucao_observacoes);

            return redirect()->route('ferramental.retirada')->with('success', 'Devolução efetuada com sucesso!');
        }
    }

    public function bloqueio($usuario)
    {
        $now = Carbon::now();

        $bloqueio = FerramentalRetirada::with('funcionario')
            ->where('id_funcionario', $usuario)
            ->where('data_devolucao_prevista', '<', $now->format('Y-m-d H:i:s'))
            ->where('status', [2, 5])
            ->count();

        return response()->json(['quantidade' => $bloqueio]);
    }

    public function ampliar($id)
    {
        $obras = CadastroObra::where('status_obra', 'Ativo')->get();
        $funcionarios = CadastroFuncionario::where('status', 'Ativo')->get();
        $itens = FerramentalRetirada::getRetiradaItems($id);
        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();

        return view('pages.ferramental.retirada.prazo', compact('obras', 'itens', 'funcionarios', 'empresas'));
    }

    public function ampliarStore(Request $request)
    {

        $request->validate(
            [
                'id_obra' => 'required',
                'id_funcionario' => 'required',
                'id_ativo_externo' => 'required'
            ],
            [
                'id_obra.required' => 'Qual obra você deseja efetivar esta retirada?',
                'id_funcionario.required' => 'Você precisa selecionar o funcionário.',
                'id_ativo_externo.required' => 'Nenhum item foi selecionado para retirada.',
                'devolucao_prevista.required' => 'Preencha a data e hora para devolução.'
            ]
        );

        /** Salvar Retirada anterior como devolvida ou devolvida parcialmente */
        try {
            if (count($request->id_ativo_externo) <  FerramentalRetiradaItem::where('id_retirada', $request->id_relacionamento)->count()) {
                $situacao_retirada = 7; // devolução parcial
            } else {
                $situacao_retirada = 3; // devolução total
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            return redirect()->route('ferramental.retirada.detalhes', $request->id_relacionamento)->with('error', $message);
            exit;
        }

        /** Marca retirada */
        $retirada_anterior = FerramentalRetirada::find($request->id_relacionamento);
        $retirada_anterior->devolucao_observacoes = $request->observacoes ?? null;
        $retirada_anterior->data_devolucao = now();
        $retirada_anterior->updated_at = now();
        $retirada_anterior->status = $situacao_retirada ?? 8; // se não, finalizada (8) - finalizado
        $retirada_anterior->save();

        /** Nova Retirada */
        $retirada = new FerramentalRetirada();
        $retirada->id_relacionamento = $request->id_relacionamento;
        $retirada->id_obra = $request->id_obra;
        $retirada->id_usuario = Auth::user()->id ?? 100;
        $retirada->id_funcionario = $request->id_funcionario;
        $retirada->data_devolucao_prevista = $request->devolucao_prevista;
        $retirada->data_devolucao = null;
        $retirada->status = 2;
        $retirada->observacoes = $request->observacoes ?? NULL;
        $retirada->save();

        $id_retirada_nova = $retirada->id;

        try {

            /** Atualiza itens da retirada anterior */
            $items_retirada = FerramentalRetirada::getRetiradaItems($request->id_relacionamento);

            foreach ($items_retirada->itens as $item) {


                /** Buscar ativos que serão renovados */
                if (in_array($item->id_ativo_externo, $request->id_ativo_externo)) {

                    $retirada_item = new FerramentalRetiradaItem();
                    $retirada_item->id_ativo_externo = $item->id_ativo_externo;
                    $retirada_item->id_retirada = $id_retirada_nova;
                    $retirada_item->status = 2;
                    $retirada_item->save();

                    $items_renovar = AtivoExternoEstoque::find($item->id_ativo_externo);
                    $items_renovar->status = 6; // em operação
                    $items_renovar->save();
                } else {
                    $items_renovar = AtivoExternoEstoque::find($item->id_ativo_externo);
                    $items_renovar->status = 4; // em estoque
                    $items_renovar->save();
                }
            }

            /** Registro no Log */
            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | AMPLIOU PRAZO DE ENTREGA RETIRADA | ID: ' . $id_retirada_nova . ' | DATA: ' . date('Y-m-d H:i:s'));

            /** Notificação por e-mail no endereço cadastrado nas configurações de notificações */
            /*  $email_config = Config::where('id', 1)->first();
            $email_config->notify(new NotificaRetirada($email_config->email));

            /** Notificação por telegram no canal registrado (API depende de https) */
            /* if (env('APP_ENV') === 'production') {
                Notification::route('telegram', env('TELEGRAM_CHAT_ID'))
                    ->notify(new NotificaRetiradaTelegram('atendimento@codigosdigitais.com.br')); //$email_config->email
            } */

            return redirect()->route('ferramental.retirada.detalhes', $id_retirada_nova)->with('success', 'Aumento de prazo solicitado com sucesso!');
        } catch (Exception $e) {
            $message = $e->getMessage();
            return redirect()->route('ferramental.retirada.detalhes', $request->id_relacionamento)->with('error', $message);
            exit;
        }
    }
}
