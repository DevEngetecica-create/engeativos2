<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    AtivoConfiguracao,
    AtivoExternoEstoque,
    AtivoExternoManutencao,
    AtivoExterno,
    AtivoExternoEstoqueItem,
    AtivosExternosStatus,
    CadastroEmpresa,
    CadastroFornecedor,
    CadastroObra,
    User,
    Anexo,
    Notification,
    Config
};



use App\Traits\{
    Configuracao,
    FuncoesAdaptadas
};

use Illuminate\Contracts\Cache\Store;
use App\Notifications\NotificaManutencao;
use App\Report\AtivosExternosReport;
use Illuminate\Support\Facades\DB;
use DOMDocument;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Auth,
    Storage,
    Log
};
use Illuminate\Support\Facades\Session;

class AtivoExternoManutencaoController extends Controller
{

    use Configuracao, FuncoesAdaptadas;

    /*protected $ativoExternoManutencao;
    protected $ativoExternoEstoque_report;

    public function __construct(
        AtivoExternoManutencao $ativoExternoManutencao,
        AtivosExternosManutencaoReport $ativoExternoManutencao_report
    ) {
        $this->ativoExternoManutencao = $ativoExternoManutencao;
        $this->ativoExternoManutencao_report = $ativoExternoManutencao_report;
    }*/

    public function index(Request $request, AtivoExterno $ativo)
    {

        $listPage = request('lista') ?? 7;

        $search = request('search');

        if (Session::get('obra')['id'] == null) {

            $ativos = AtivoExternoManutencao::with('ativo_externo_estoque', 'configuracao', 'obra', 'situacao')
                ->paginate(10);
        } else {
            $ativos = AtivoExternoManutencao::where('id_obra', Session::get('obra')['id'])
                ->with('ativo_externo_estoque', 'configuracao', 'obra', 'situacao')
                ->paginate(10);
        }

        if (Session::get('obra')['id'] == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
        } else {
            $obras = CadastroObra::where('id', Session::get('obra')['id'])->orderByDesc('id')->get();
        }

        $categorias = AtivoConfiguracao::where('id_relacionamento', '>', 0)->get();

        $situacoes = AtivosExternosStatus::all();

        $fornecedores = CadastroFornecedor::select('id', 'nome_fantasia')->get();

        $ativo_configuracoes = AtivoConfiguracao::where('id_relacionamento', '>', 0)->where('status', 'Ativo')->get();

        $empresas = CadastroEmpresa::all();

        return view('pages.ativos.externos.manutencao.index', compact('obras', 'categorias', 'situacoes', 'ativo_configuracoes', 'ativos', 'fornecedores'));
    }


    public function list(Request $request, AtivoExterno $ativo)
    {

        $id_obra = Session::get('obra')['id'];

        $listPage = request('lista') ?? 7;

        $search = request('search');

        $id_fornecedor = request('id_fornecedor');



        if ($id_obra == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
        } else {
            $obras = CadastroObra::where('id', $id_obra)->orderByDesc('id')->get();
        }

        if ($id_obra == null) {

            //PESQUISAR EM TODAS AS OBRAS

            $ativos = AtivoExternoManutencao::where(function ($query) use ($search) {
                $query->where('nomeAtivo', 'like', "%$search%");
            })
                ->orWhereHas('fornecedor', function ($query) use ($search) {
                    $query->where('nome_fantasia', 'like', "%$search%");
                })
                ->with('ativo_externo_estoque', 'configuracao', 'situacao', 'emOprecacao', 'funcionario', 'fornecedor')
                ->paginate($listPage);
        } else {

            $ativos = AtivoExternoManutencao::where('id_obra', $id_obra)
                ->with('ativo_externo_estoque', 'configuracao', 'situacao', 'emOprecacao', 'funcionario', 'fornecedor')
                ->where(function ($query) use ($search) {
                    $query->where('nomeAtivo', 'like', "%$search%");
                })

                ->orWhereHas('fornecedor', function ($query) use ($search) {
                    $query->where('nome_fantasia', 'like', "%$search%");
                })
                ->where('id_obra', $id_obra)
                ->paginate($listPage);
        }

        $categorias = AtivoConfiguracao::where('id_relacionamento', '>', 0)->get();
        $situacoes = AtivosExternosStatus::all();
        $ativo_configuracoes = AtivoConfiguracao::where('id_relacionamento', '>', 0)->where('status', 'Ativo')->get();

        $empresas = CadastroEmpresa::all();

        return view('pages.ativos.externos.manutencao.partials.list', compact('obras', 'categorias', 'situacoes', 'ativo_configuracoes', 'ativos'));
    }

    public function create()
    {

        if (Session::get('obra')['id'] == null) {
            $obras = CadastroObra::orderByDesc('id')->orderBy('id')->get();

            $ativosManutencao = AtivoExternoManutencao::with('ativo_externo_estoque', 'configuracao', 'situacao', 'obra', 'fornecedor')
                ->get();

            $ativosExternosEsqtoque = AtivoExternoEstoque::with('ativo_externo', 'configuracao', 'obra', 'situacao')
                ->get();
        } else {

            $obras = CadastroObra::where('id', Session::get('obra')['id'])->orderBy('id')->get();

            $ativosManutencao = AtivoExternoManutencao::where('id_obra', Session::get('obra')['id'])
                ->with('ativo_externo_estoque', 'configuracao', 'obra', 'situacao')
                ->get();

            $ativosExternosEsqtoque = AtivoExternoEstoque::where('id_obra', Session::get('obra')['id'])
                ->with('ativo_externo', 'configuracao', 'obra', 'situacao')
                ->get();
        }


        $fornecedores = CadastroFornecedor::select('id', 'nome_fantasia')->get();

        $situacoes = AtivosExternosStatus::all();

        return view('pages.ativos.externos.manutencao.form', compact('obras', 'ativosManutencao', 'fornecedores', 'situacoes', 'ativosExternosEsqtoque'));
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
                ->where('patrimonio', 'like', "%$request->selecao%")
                ->with('ativo_externo', 'configuracao', 'obra', 'situacao')
                ->get();
        }

        return response()->json($informacoes);
    }


    public function store(Request $request)

    {

        //dd( Auth::user()->id);

        $request->validate(
            [
                'id_ativo_externo_estoque'      => 'required',
                'id_ativo_externo'              => 'required',
                'id_obra'                       => 'required',
                'id_fornecedor'                 => 'required',
                'nomeAtivo'                     => 'required',
                'valor'                         => 'required',
                'data_retirada'                 => 'required',
                'data_prevista'                 => 'required',
            ],
            [
                'id_ativo_externo_estoque'      => 'required',
                'id_ativo_externo'              => 'required',
                'id_obra'                       => 'O campo da obra é obrigatório',
                'id_fornecedor'                 => 'O campo do fornecedor é obrigatório',
                'id_status'                     => 'O campo status é obrigatório',
                'nomeAtivo'                     => 'O campo nome da ferramenta é obrigatório',
                'valor'                         => 'O campo valor é obrigatório',
                'data_retirada'                 => 'O campo data da retirada é obrigatório',
                'data_prevista'                 => 'O campo data de entrega é obrigatório',
            ]
        );



        $description = $request->description;

        $dom = new DOMDocument();
        $dom->loadHTML($description, 9);

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $key_img => $img) {
            $data = base64_decode(explode(',', explode(';', $img->getAttribute('src'))[1])[1]);
            $image_name = "/storage/ativo_manutencao/" . time() . $key_img . '.png';
            file_put_contents(public_path() . $image_name, $data);

            $img->removeAttribute('src');
            $img->setAttribute('src', $image_name);
        }
        $description = $dom->saveHTML();



        $manutencaoAtivos = new AtivoExternoManutencao([
            'id_ativo_externo_estoque'      => $request->id_ativo_externo_estoque,
            'id_ativo_externo'              => $request->id_ativo_externo,
            'id_obra'                       => $request->id_obra,
            'id_fornecedor'                 => $request->id_fornecedor,
            'id_user_solicitante'           => Auth::user()->id,
            'id_status'                     => 10,
            'nomeAtivo'                     => $request->nomeAtivo,
            'description'                   => $description,
            'valor'                         => $request->valor,
            'data_retirada'                 => $request->data_retirada,
            'data_prevista'                 => $request->data_prevista,
        ]);

        $manutencaoAtivos->save();

        if ($request->id_ativo_externo_estoque) {

            /** atualiza o statu para Em manutenção */
            /** Salvar Ativo Externo */
            $estoque = AtivoExternoEstoque::find($request->id_ativo_externo_estoque);
            $estoque->status = 10; // em estoque
            $estoque->save();
            $userLog = Auth::user()->email;


            //Envia Notificação por e-mail no endereço para nova Manutencão
            $email_config = Config::where('id', 1)->first();
            $id_manutencao = $manutencaoAtivos->id;

            //$pesqNomeFerramenta =  AtivoExterno::where('id', $request->id_ativo_externo)->first();
            $method = 'solicite';

            $nomeFerramenta = $request->patrimonio . " - " . $request->nomeAtivo;

            $email_config->notify(new NotificaManutencao($email_config->email, $id_manutencao, $nomeFerramenta, $method));

            //Noticação na dashboard
            $link_acesso_notification = env('APP_URL') . '/admin/ativo/externo/manutencao/detalhes/' . $id_manutencao;

            $notication = new Notification(
                [
                    "id_retirada" => $id_manutencao,
                    "id_obra" => $request->id_obra,
                    "tipo" => "manutencao_ferramenta",
                    "mensagem" => "Há uma nova solicitação de manutenção no Sistema para a ferramenta {$nomeFerramenta}!",
                    "link_acesso" => $link_acesso_notification
                ]
            );

            $notication->save();


            Log::channel('main')->info($userLog . ' | CADASTRO DA MANUTENCAO: ' . $request->nome_ativo_externo_estoque);

            return redirect()->route('ativo.externo.manutencao.detalhes',  $manutencaoAtivos->id)->with('success', 'Registro salvo com sucesso');
        } else {

            return redirect()->route('ativo.externo.manutencao.adicionar')->with('fail', 'Erro ao salvar registro');
        }
    }


    public function edit(Request $request, $id)
    {

        if (Session::get('obra')['id'] == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
        } else {
            $obras = CadastroObra::where('id', Session::get('obra')['id'])->orderByDesc('id')->get();
        }

        $editAtivos = AtivoExternoManutencao::find($id);

        if (Session::get('obra')['id'] == null) {
            $ativosExternosEsqtoque = AtivoExternoEstoque::with('obra', 'situacao', 'ativo_externo')
                ->where('id', $editAtivos->id_ativo_externo_estoque)
                ->get();
        } else {

            $ativosExternosEsqtoque = AtivoExternoEstoque::with('obra', 'situacao', 'ativo_externo')
                ->where('id_obra', Session::get('obra')['id'])
                ->where('id', $editAtivos->id_ativo_externo_estoque)
                ->get();
        }


        $fornecedores = CadastroFornecedor::where('id', $editAtivos->id_fornecedor)
            ->get();

        $situacoes = AtivosExternosStatus::all();

        return view('pages.ativos.externos.manutencao.form', compact('obras', 'editAtivos', 'fornecedores', 'situacoes', 'ativosExternosEsqtoque'));
    }

    public function update(Request $request, $id)
    {

        $updateManutencaoAtivos = AtivoExternoManutencao::find($id);
//dd($request->id_ativo_externo_estoque);

        //Verificar se é devolução pelo request da data realizado/ devolução
        if (isset($request->data_realizada)) {

            //se tiver data de devolução 
            $updateManutencaoAtivos->update([
                'id_status'         => 4, //devolver a ferramenta para o estoque
                'data_realizada'    => $request->data_realizada // registra a date de devolução
            ]);

            //atualizar a tabela de estoque de ferramentas
            $aprovedOrcamento = AtivoExternoEstoque::find($request->id_ativo_externo_estoque);       
    
            $aprovedOrcamento->update([
                'status'   => 4,               
            ]);
    

        } else { // se não atualiza os demais campos

            $description = $request->description;

            $dom = new DOMDocument();
            $dom->loadHTML($description, 9);

            $images = $dom->getElementsByTagName('img');

            foreach ($images as $key => $img) {

                // Check if the image is a new one
                if (strpos($img->getAttribute('src'), 'data:image/') === 0) {

                    $data = base64_decode(explode(',', explode(';', $img->getAttribute('src'))[1])[1]);
                    $image_name = "/storage/ativo_manutencao/" . time() . $key . '.png';
                    file_put_contents(public_path() . $image_name, $data);

                    $img->removeAttribute('src');
                    $img->setAttribute('src', $image_name);
                }
            }

            $description = $dom->saveHTML();

            $updateManutencaoAtivos->update([
                'id_ativo_externo_estoque'      => $request->id_ativo_externo_estoque,
                'id_ativo_externo'              => $request->id_ativo_externo,
                'id_obra'                       => $request->id_obra,
                'id_fornecedor'                 => $request->id_fornecedor,
                'id_status'                     => 18,
                'nomeAtivo'                     => $request->nomeAtivo,
                'description'                   => $description,
                'valor'                         => $request->valor,
                'data_retirada'                 => $request->data_retirada,
                'data_prevista'                 => $request->data_prevista,
                'image'                         => $request->image ?? '',
            ]);
        }

        if ($updateManutencaoAtivos->update()) {

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | EDIT A MANUTENCAO: ' . $request->nome_ativo_externo_estoque);

            return redirect()->route('ativo.externo.manutencao.detalhes', $updateManutencaoAtivos->id)->with('success', 'Registro atualizado com sucesso!');
        } else {

            return redirect()->route('ativo.externo.manutencao.editar')->with('fail', 'Erro ao salvar registro');
        }
    }

    public function aprovedOrcamento(Request $request, $id)
    {

        $aprovedOrcamento = AtivoExternoManutencao::find($id);

        $patrimonio = AtivoExternoEstoque::where('id', $aprovedOrcamento->id_ativo_externo_estoque)->first();

        $aprovedOrcamento->update([
            'id_status'   => 2,
            'id_user_aprovacao' => Auth::user()->id
        ]);


        //Envia Notificação por e-mail no endereço para nova Manutencão
        $email_config =  User::where('id', $aprovedOrcamento->id_user_solicitante)->first();

        //id da manutenção
        $id_manutencao = $id;

        //patrimonio e nome da verramenta
        $nomeFerramenta = $patrimonio->patrimonio . " - " . $aprovedOrcamento->nomeAtivo;

        //mensagem de envio
        $menssagem = " O orçamento da ferramenta {$nomeFerramenta} foi apvoddo para enviar para manutenção";

        // dd($email_config . ' | ' . $id_manutencao.  ' | ' . $method. ' | ' .$nomeFerramenta );

        $email_config->notify(new NotificaManutencao($email_config->email, $id_manutencao, $menssagem));

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | APROVOU O ORCAMENTO: ' . $aprovedOrcamento->id);

        $informacoes = "Orcamento Liberado";

        return response()->json($informacoes);
    }

    public function show(Request $request, $id)
    {

        $id_obra = Session::get('obra')['id'];

        if ($id_obra == null) {

            $showtAtivos = AtivoExternoManutencao::with('ativo_externo_estoque','obra', 'situacao', 'ativo_externo', 'fornecedor')
                ->where('id', $id)->get();
        } else {

            $showtAtivos = AtivoExternoManutencao::with('ativo_externo_estoque', 'obra', 'situacao', 'ativo_externo', 'fornecedor')
                ->where('id', $id)
                ->where('id_obra', $id_obra)
                ->get();
        }

        $anexos = Anexo::where('id_item', $id)
            ->where('id_modulo', 31)
            ->get();

        return view('pages.ativos.externos.manutencao.show', compact('showtAtivos', 'anexos'));
    }



    public function destroy($id)
    {

        //dd($id);
        $destroyAtivoManut = AtivoExternoManutencao::findOrFail($id);

        $dom = new DOMDocument();
        $dom->loadHTML($destroyAtivoManut->description, 9);
        $images = $dom->getElementsByTagName('img');

        foreach ($images as $key => $img) {

            $src = $img->getAttribute('src');
            $path = Str::of($src)->after('/');


            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $destroyAtivoManut->delete();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | DELETADO A MANUTENCAO: ' . $destroyAtivoManut->id . " | " . $destroyAtivoManut->id_ativo_externo_estoque);

        return redirect()->route('ativo.externo.manutencao')->with('success', 'Registro deletado com sucesso!');;
    }

    //Inserção de arquivo como link no corpo da postagem
    public function upload(Request $request)
    {

        $data = $request->all();

        // dd($request->tipo);

        if ($request->hasFile('file')) {

            $ext = $request->file("file")->getClientOriginalExtension();


            if ($ext == 'pdf' or $ext == 'xls' or $ext == 'xlsx' or $ext == 'jpg' or $ext == 'png' or $ext == 'jpeg' or $ext == 'gif') {

                $nome_arquivo = preg_replace('/[ -]+/', '-', @$_FILES['file']['name']);

                $request->file('file')->storeAs('uploads/anexos_ativos_internos', $nome_arquivo, 'public');
            } else {

                return redirect()->back()->with('failed', 'A extensão do arquivo não é permitida!!!');
                session()->forget('failed');
            }

            //dd(Auth::user()->id);

            Anexo::create([
                'id_usuario' => Auth::user()->id,
                'id_modulo' => 31,
                'id_item' => $request->id_ativo_interno,
                'nome_modulo' => 'manutenca_ativo_externo',
                'titulo' => $request->titulo,
                'arquivo' => $nome_arquivo,
                'tipo' => $ext,
                'descricao' => $request->descricao,

            ]);


            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | ADD ANEXOS DA MANUTENCAO: ' . $request->id);

            if ($request->id_ativo_interno) {

                return redirect()
                    ->route('ativo.externo.manutencao.detalhes', $request->id_ativo_interno)
                    ->with('success', 'Anexo cadastrado com sucesso.');
            } else {

                return redirect()->back()->with('fail', 'Um erro impediu o cadastro.');
                session()->forget('fail');
            }
        }
    }

    /** Download de Arquivos */
    public function download(Request $request, $id)
    {
        $anexo = (Anexo::find($id)->arquivo);

        $moduloDownload = (Anexo::find($id)->nome_modulo);

        $userLog = Auth::user()->email;

        Log::channel('main')->info($userLog . ' | DOWNLOAD ANEXO: ' . $anexo);

        if ($anexo === null) {

            return redirect()
                ->route('ativo.externo.manutencao.detalhes', $request->id_ativo_interno)
                ->with('error', 'Não foi possível localizar o arquivo solicitado.');
        }

        return Storage::download('public/uploads/anexos_ativos_internos/' . $anexo);
    }


    /** Excluir Anexo */
    public function destroyAnexo($id, $modulo = null)
    {


        $anexo = Anexo::find($id);

        //recupera o id do modulu
        $id_modulo = $anexo->id_modulo;


        //comparar se o id_item é igual ao $id requisitado e o modulo
        if ($anexo->id_item == $anexo->id_item && $id_modulo == 31) {

            //se for igual deleta
            $anexo->delete();

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | DELETE ANEXO: ' . $anexo->titulo);

            return redirect()
                ->route('ativo.externo.manutencao.detalhes', $anexo->id_item)
                ->with('success', 'Registro exclúido com successo');
        } else { // se for retorna mensagem de erro

            return redirect()
                ->route('ativo.externo.manutencao.detalhes', $anexo->id_item)
                ->with('error', 'Não foi possível localizar o arquivo solicitado.');
        }
    }
}
