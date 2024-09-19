<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\AnexoAtivoInterno;
use App\Models\AnexoEstoque;
use App\Models\AtivoConfiguracao;
use App\Models\CadastroEmpresa;
use App\Models\CadastroFornecedor;
use App\Models\CadastroObra;
use App\Models\MarcaPatrimonio;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\{
    Auth,
    Storage,
    Log,
    Session,
    File
};

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRFpdf;

use Exception;
use Carbon\Carbon;

class EstoqueController extends Controller
{


    public function index()
    {
        $id_obra = Session::get('obra')['id'];

        if ($id_obra == null) {

            $produtos = Estoque::with('obra', 'categoria')->orderByDesc('id')->paginate(8);
        } else {

            $produtos = Estoque::with('obra', 'categoria')->whereOr('id_obra', $id_obra)->orderByDesc('id')->paginate(8);
        }

        return view('pages.estoque.index', compact('produtos'));
    }


    public function create(Request $request)
    {

        $obras = CadastroObra::with('empresa', 'funcionarios')->orderByDesc('id')->get();
        $marcas = MarcaPatrimonio::all();
        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();

        $fornececedores = CadastroFornecedor::where('status', 'Ativo')->get();

        ($request->id == "") ? "" : $request->id;
        $anexos = AnexoAtivoInterno::where('id_ativo_interno', $request->id)->get();
        $categorias = AtivoConfiguracao::where('id_relacionamento', '>', 0)->get();

        $ativo_configuracoes = AtivoConfiguracao::with('relacionamento')->where('status', 'Ativo')->get();

        return view('pages.estoque.create', compact('obras', 'marcas', 'ativo_configuracoes', 'empresas', 'anexos', 'categorias', 'fornececedores'));
    }


    public function store(Request $request)
    {

        try {


            $request->validate([
                'arquivo_cert_aut' => 'mimes:png,jpg,jpeg,pdf|max:2048',
                'arquivo_nf' => 'mimes:png,jpg,jpeg,pdf|max:2048',
                'imagem' => 'mimes:png,jpg,jpeg|max:2048',
            ], [
                'arquivo_cert_aut.mimes' => 'O tipo de arquivo que você está tentando enviar não é válido.',
                'arquivo_nf.mimes' => 'O tipo de arquivo que você está tentando enviar não é válido.',
                'imagem.mimes' => 'O tipo de arquivo que você está tentando enviar não é válido.',
            ]);


            /*************************************FORMATAR VALOR PARA SALVAR NO BANCO DE DADOS ******************/

            $string_valor_unitario = $request->valor_unitario;
            // Remove o separador de milhar (ponto)
            $string_valor_unitario = str_replace('.', '', $string_valor_unitario);
            // Substitui a vírgula do separador decimal por um ponto
            $string_valor_unitario = str_replace(',', '.', $string_valor_unitario);
            // Converte para float
            $numeroFloat_valor_unitario = (float) $string_valor_unitario;

            $string_valor_toral = $request->valor_total;
            // Remove o separador de milhar (ponto)
            $string_valor_toral = str_replace('.', '', $string_valor_toral);
            // Substitui a vírgula do separador decimal por um ponto
            $string_valor_toral = str_replace(',', '.', $string_valor_toral);
            // Converte para float
            $numeroFloat_valor_toral = (float) $string_valor_toral;

            /******************************************************************************************************/

            //Váriaveis para gerar o QRcode do link de acesso
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

            //Nome dos arquivos
            $nome_img = preg_replace('/[ -]+/', '-', $request->file("imagem")->getClientOriginalName());
            $nome_arquivo_ca = preg_replace('/[ -]+/', '-', $request->file("arquivo_cert_aut")->getClientOriginalName());
            $nome_arquivo_nf = preg_replace('/[ -]+/', '-', $request->file("arquivo_nf")->getClientOriginalName());

            $produto_estoque = new Estoque([
                'nome_produto' => $request->nome_produto,
                'id_ob' => $request->id_obra,
                'id_fornecedor' => $request->id_fornecedor,
                'id_marca' => $request->id_marca,
                'usuario' => Auth::user()->email,
                'quantidade' => $request->quantidade,
                'valor_unitario' => $numeroFloat_valor_unitario,
                'valor_total' => $numeroFloat_valor_toral,
                'data_validade' => $request->data_validade,
                'id_categoria' => $request->id_categoria,
                'estoque_minimo' => $request->estoque_minimo,
                'unidade' => $request->unidade,
                'cert_aut' => $request->cert_aut,
                'num_lote' => $request->num_lote,
                'titulo_nf' => $request->titulo_nf,
                'status_produto' => $request->status_produto,
                'image_produto' => $nome_img
            ]);

            $produto_estoque->save();

            //salvar os dados dos arquivos na tabela anexo_estoque
            $files = [];
            $files = [$nome_arquivo_ca, $nome_arquivo_nf];

            foreach ($files as $file) {
                $anexos_estoque = new AnexoEstoque(
                    [
                        'id_produto' => $produto_estoque->id,
                        'nome_arquivo' => $file,
                        'usuario' => Auth::user()->email,
                    ]
                );

                $anexos_estoque->save();
            }

            //atualizar gravar os dados do QRCode no BD
            //como o QRCode é o link para acessar a página do produto, é feito um update no registro salvo para gravar o QRCode;
            $qrcodeEntregarItens = (new QRCode($options))->render(env('APP_URL') . '/admin/ativo/estoque/show/' . $produto_estoque->id);
            Estoque::where('id', $produto_estoque->id)->update([
                'barcode_symbology' => $qrcodeEntregarItens,
            ]);

            //salva os arquivos no diretórios
            if ($request->file("imagem")) {

                $request->file("imagem")->move(public_path("imagens/estoque/"), $nome_img);
            }
            if ($request->file("arquivo_cert_aut")) {

                $file = $request->file("arquivo_cert_aut");
                $file->storeAs('uploads/estoque/', $nome_arquivo_ca, 'public');
            }
            if ($request->file("arquivo_nf")) {

                $file = $request->file("arquivo_nf");
                $file->storeAs('uploads/estoque/', $nome_arquivo_nf, 'public');
            }

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | STORE ATIVOS INTERNOS: ' . $produto_estoque->patrinome_produtomonio);

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Registro cadastrado com sucesso",
                'type' => 'success'
            );

            return redirect()->route('ativo.estoque.index')->with($notification);


        } catch (Exception $e) {

            $message = $e->getMessage();

            $notification = array(
                'title' => "Atenção!!!",
                'message' => $message,
                'type' => 'warning'
            );

            return redirect()->route('ativo.estoque.create')->with($notification);

            exit;
        }
    }

    public function show($id)
    {
        $obras = CadastroObra::where('status_obra', 'Ativo')->orderByDesc('id')->get();

        $marcas = MarcaPatrimonio::all();

        $anexos = AnexoEstoque::where('id_produto', $id)->get();

        $fornececedores = CadastroFornecedor::where('status', 'Ativo')->get();

        $ativo_configuracoes = AtivoConfiguracao::all();

        $protudo_etoque = Estoque::find($id);

        return view('pages.estoque.show', compact('protudo_etoque', 'marcas', 'obras', 'anexos', 'fornececedores', 'ativo_configuracoes'));
    }

    public function edit($id)
    {
        $obras = CadastroObra::where('status_obra', 'Ativo')->orderByDesc('id')->get();

        $marcas = MarcaPatrimonio::all();

        $anexos = AnexoEstoque::where('id_produto', $id)->get();

        $fornececedores = CadastroFornecedor::where('status', 'Ativo')->get();

        $ativo_configuracoes = AtivoConfiguracao::all();

        $protudo_etoque = Estoque::find($id);

        return view('pages.estoque.edit', compact('protudo_etoque', 'marcas', 'obras', 'anexos', 'fornececedores', 'ativo_configuracoes'));
    }

    public function update(Request $request, $id)
    {

        try {

            /*FORMATAR VALOR PARA SALVAR NO BANCO DE DADOS */

            $string_valor_unitario = $request->valor_unitario;

            // Remove o separador de milhar (ponto)
            $string_valor_unitario = str_replace('.', '', $string_valor_unitario);

            // Substitui a vírgula do separador decimal por um ponto
            $string_valor_unitario = str_replace(',', '.', $string_valor_unitario);

            // Converte para float
            $numeroFloat_valor_unitario = (float) $string_valor_unitario;


            $string_valor_toral = $request->valor_total;
            // Remove o separador de milhar (ponto)
            $string_valor_toral = str_replace('.', '', $string_valor_toral);
            // Substitui a vírgula do separador decimal por um ponto
            $string_valor_toral = str_replace(',', '.', $string_valor_toral);
            // Converte para float
            $numeroFloat_valor_toral = (float) $string_valor_toral;

            if ($request->file("imagem")) {

                $nome_img = preg_replace('/[ -]+/', '-', $request->file("imagem")->getClientOriginalName());
            }

            $produto_estoque = Estoque::find($id);
            $produto_estoque->nome_produto = $request->nome_produto;
            $produto_estoque->id_obra = $request->id_obra;
            $produto_estoque->id_fornecedor = $request->id_fornecedor;
            $produto_estoque->id_marca = $request->id_marca;
            $produto_estoque->usuario = Auth::user()->email;
            $produto_estoque->quantidade = $request->quantidade;
            $produto_estoque->valor_unitario = $numeroFloat_valor_unitario;
            $produto_estoque->valor_total = $numeroFloat_valor_toral;
            $produto_estoque->data_validade = $request->data_validade;
            $produto_estoque->id_categoria = $request->id_categoria;
            $produto_estoque->estoque_minimo = $request->estoque_minimo;
            $produto_estoque->unidade = $request->unidade;
            $produto_estoque->cert_aut = $request->cert_aut;
            $produto_estoque->num_lote = $request->num_lote;
            $produto_estoque->titulo_nf = $request->titulo_nf;
            $produto_estoque->status_produto = $request->status_produto;
            $produto_estoque->image_produto = $nome_img ??  $produto_estoque->image_produto;

            $produto_estoque->save();

            if ($request->file("imagem")) {

                $request->validate(
                    [
                        'imagem' => 'mimes:png,jpg,jpeg,bmp|max:2048'
                    ],
                    [
                        'file.mimes' => 'O tipo de arquivo que você está tentando enviar não é válido.'
                    ]
                );

                if (File::exists("imagens/estoque/" . $produto_estoque->image_produto)) {
                    File::delete("imagens/estoque/" . $produto_estoque->image_produto);
                }

                $file = $request->file("imagem");

                $file->move(public_path("imagens/estoque/"), $nome_img);
            } else {
            }

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Registro atualizado com sucesso",
                'type' => 'success'
            );

            return redirect()->route('ativo.estoque.index', $id)->with($notification);

        } catch (Exception $e) {

            $message = $e->getMessage();

            $notification = array(
                'title' => "Atenção!!!",
                'message' => $message,
                'type' => 'warning'
            );

            return redirect()->route('ativo.estoque.index', $id)->with($notification);
        }
    }

    public function destroy(Estoque $produto)
    {

        try {

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | DELETE ATIVOS INTERNOS: ' . $produto->nome_produto);

            $produto->delete();

            if (File::exists("imagens/estoque/" . $produto->image_produto)) {
                File::delete("imagens/estoque/" . $produto->image_produto);
            }

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Registro excluído com sucesso.",
                'type' => 'success'
            );

            return redirect()->route('ativo.estoque.index')->with($notification);
        } catch (Exception $e) {

            $message = $e->getMessage();

            $notification = array(
                'title' => "Atenção!!!",
                'message' => $message,
                'type' => 'warning'
            );

            return redirect()->route('ativo.estoque.edit', $produto->id)->with($notification);
        }
    }


    /** Download de Arquivos */
    public function anexos(Request $request, $id)
    {
        if ($request->listar == "listar") {

            $ultimoAnexo = AnexoEstoque::where('id_produto', $id)
                ->latest('created_at')
                ->first();

            return response()->json($ultimoAnexo);
        } else {

            $anexos = AnexoEstoque::where('id_produto', $id)->get();
            return view('pages.estoque.partials.anexos', compact('anexos'));
        }
    }

    public function fileUpload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'mimes:png,jpg,jpeg,pdf|max:2048'
            ], [
                'file.mimes' => 'O tipo de arquivo que você está tentando enviar não é válido.'
            ]);

            $id = $request->id; // Pega o ID do request


            $files = $request->file("file");

            $nome_arquivo = $request->file("file")->getClientOriginalName();
            // Armazena o arquivo e obtém o caminho/nome do arquivo
            $files->storeAs('uploads/estoque/', $nome_arquivo, 'public');

            $anexo_estoque = new AnexoEstoque([
                'id_produto'    => $id,
                'nome_arquivo'  => $nome_arquivo, // Salva o caminho/nome do arquivo
                'usuario'  => Auth::user()->email, // Salva o caminho/nome do arquivo
            ]);

            $anexo_estoque->save();
            $mensagem = 'Arquivo(s) cadastrado(s) com sucesso.';

            return response()->json($mensagem);
        } catch (Exception $e) {

            $mensagem = $e->getMessage();

            return response()->json($mensagem);
        }
    }

    /** Download de Arquivos */
    public function download($id)
    {
        $anexo = (AnexoEstoque::find($id)->nome_arquivo) ?? 0;

        $userLog = Auth::user()->email;

        Log::channel('main')->info($userLog . ' | DOWNLOAD ANEXO: ' . $anexo);

        if ($anexo == null or $anexo == "") {

            return redirect()->back()->with('mensagem', 'Não foi possível fazer o download do arquivo, Edite este item e insira um arquivo válido');
            session()->forget('mensagem');
        } else {

            return Storage::download('public/uploads/estoque/' . $anexo);

            return redirect()->back()->with('success', 'Download realizado com successo');
            session()->forget('success');
        }
    }


    /** Excluir Anexo */
    public function destroy_file(Request $request)
    {

        try {

            $id_produto = $request->id_produto; // Pega o ID do produto da solicitação
            
            $anexo = AnexoEstoque::find($id_produto);

            $nomeArquivo = $anexo->nome_arquivo;

            $anexo->delete(); // Deleta o anexo

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | DELETE ANEXO ESTOQUE: ' . $nomeArquivo);

            $mensagem = 'Arquivo deletado com sucesso.';

            return response()->json($mensagem);

        } catch (Exception $e) {

            $mensagem = $e->getMessage();

            return response()->json($mensagem);
        }
    }
}
