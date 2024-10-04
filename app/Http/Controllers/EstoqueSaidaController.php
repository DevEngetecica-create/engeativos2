<?php

namespace App\Http\Controllers;

use App\Models\EstoqueSaida;
use App\Models\AnexoAtivoInterno;
use App\Models\AnexoEstoque;
use App\Models\AtivoConfiguracao;
use App\Models\CadastroEmpresa;
use App\Models\CadastroFornecedor;
use App\Models\CadastroFuncionario;
use App\Models\CadastroObra;
use App\Models\Estoque;
use App\Models\EstoqueEntrada;
use App\Models\EstoqueSaidaJustificarEPI;
use App\Models\FuncaoEpi;
use App\Models\FuncaoFuncionario;
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

class EstoqueSaidaController extends Controller

{

    public function index()
    {
        $id_obra = Session::get('obra')['id'];

        // Recupera dados com base no estado da sessão 'obra'
        if ($id_obra == null) {
            $produtos_saidas = EstoqueSaida::with('obra', 'categoria', 'produto', 'funcionario')
                ->orderBy('id', 'desc')
                ->paginate(3);
        } else {
            $produtos_saidas = EstoqueSaida::with('obra', 'categoria', 'produto', 'funcionario')
                ->where('id_obra', $id_obra)
                ->orderBy('id', 'desc')
                ->paginate(3);
        }

        return view('pages.estoque.saidas.index', compact('produtos_saidas'));
    }


    public function create(Request $request)
    {

        $obras = CadastroObra::with('empresa', 'funcionarios')->orderByDesc('id')->get();

        $categorias = AtivoConfiguracao::where('id_relacionamento', '>', 0)->get();

        $ativo_configuracoes = AtivoConfiguracao::with('relacionamento')->where('status', 'Ativo')->get();


        $id_obra = Session::get('obra')['id'];

        $id_categoria = $request->id_categoria;

        // Query base para funcionários, que pode ser refinada dependendo de $id_obra
        $funcionariosQuery = CadastroFuncionario::orderBy('id', 'desc');

        // Adiciona condição sobre id_obra se ela não for nula
        if ($id_obra) {
            $funcionariosQuery->where('id_obra', $id_obra);
        }

        $funcionarios = $funcionariosQuery->get();

        // Query base para produtos saídas
        $produtosSaidasQuery = Estoque::with('obra', 'categoria')->orderBy('id', 'desc');

        // Adiciona condição sobre id_obra se ela não for nula
        if ($id_obra) {
            $produtosSaidasQuery->where('id_obra', $id_obra);
        }

        // Adiciona condição sobre id_categoria se ela for fornecida
        if ($id_categoria) {
            $produtosSaidasQuery->where('id_categoria', 'like', "%$id_categoria%");
        }

        $produtos_saidas = $produtosSaidasQuery->paginate(4);


        // Carregar vistas com dados
        return view('pages.estoque.saidas.create', compact('produtos_saidas', 'funcionarios', 'obras', 'ativo_configuracoes'));
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

            $produto_estoque = new EstoqueSaida([
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
            $qrcodeEntregarItens = (new QRCode($options))->render(env('APP_URL') . '/admin/ativo/estoque/saida/show/' . $produto_estoque->id);
            EstoqueSaida::where('id', $produto_estoque->id)->update([
                'barcode_symbology' => $qrcodeEntregarItens,
            ]);

            //salva os arquivos no diretórios
            if ($request->file("imagem")) {

                $request->file("imagem")->move(public_path("imagens/estoque/saida/"), $nome_img);
            }
            if ($request->file("arquivo_cert_aut")) {

                $file = $request->file("arquivo_cert_aut");
                $file->storeAs('uploads/estoque/saida/', $nome_arquivo_ca, 'public');
            }
            if ($request->file("arquivo_nf")) {

                $file = $request->file("arquivo_nf");
                $file->storeAs('uploads/estoque/saida/', $nome_arquivo_nf, 'public');
            }

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | STORE ATIVOS INTERNOS: ' . $produto_estoque->patrinome_produtomonio);

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Registro cadastrado com sucesso",
                'type' => 'success'
            );

            return redirect()->route('ativo.estoque.saidas.index')->with($notification);
        } catch (Exception $e) {

            $message = $e->getMessage();

            $notification = array(
                'title' => "Atenção!!!",
                'message' => $message,
                'type' => 'warning'
            );

            return redirect()->route('ativo.estoque.saidas.create')->with($notification);

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

        $protudo_etoque = EstoqueSaida::find($id);

        return view('pages.estoque.saidas.show', compact('protudo_etoque', 'marcas', 'obras', 'anexos', 'fornececedores', 'ativo_configuracoes'));
    }

    public function edit($id)
    {
        $obras = CadastroObra::where('status_obra', 'Ativo')->orderByDesc('id')->get();

        $marcas = MarcaPatrimonio::all();

        $anexos = AnexoEstoque::where('id_produto', $id)->get();

        $fornececedores = CadastroFornecedor::where('status', 'Ativo')->get();

        $ativo_configuracoes = AtivoConfiguracao::all();

        $protudo_etoque = EstoqueSaida::find($id);

        return view('pages.estoque.saidas.edit', compact('protudo_etoque', 'marcas', 'obras', 'anexos', 'fornececedores', 'ativo_configuracoes'));
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

            $produto_estoque = EstoqueSaida::find($id);
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

                if (File::exists("imagens/estoque/saida" . $produto_estoque->image_produto)) {
                    File::delete("imagens/estoque/saida" . $produto_estoque->image_produto);
                }

                $file = $request->file("imagem");

                $file->move(public_path("imagens/estoque/saida/"), $nome_img);
            } else {
            }

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Registro atualizado com sucesso",
                'type' => 'success'
            );

            return redirect()->route('ativo.estoque.saida.index', $id)->with($notification);
        } catch (Exception $e) {

            $message = $e->getMessage();

            $notification = array(
                'title' => "Atenção!!!",
                'message' => $message,
                'type' => 'warning'
            );

            return redirect()->route('ativo.estoque.saida.index', $id)->with($notification);
        }
    }

    public function destroy(EstoqueSaida $produto)
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

            return redirect()->route('ativo.estoque.saida.index')->with($notification);
        } catch (Exception $e) {

            $message = $e->getMessage();

            $notification = array(
                'title' => "Atenção!!!",
                'message' => $message,
                'type' => 'warning'
            );

            return redirect()->route('ativo.estoque.saida.edit', $produto->id)->with($notification);
        }
    }

    public function pesquisar_categoria(Request $request)
    {

        $id_obra = Session::get('obra')['id'];
        $id_categoria = $request->input('id_categoria');
        $page = $request->input('page', 1);

        $funcionarios = CadastroFuncionario::when($id_obra, function ($query, $id_obra) {
            return $query->where('id_obra', $id_obra);
        })->orderBy('id', 'desc')->get();

        $produtos_saidas = Estoque::with(['obra', 'categoria', 'entradas', 'saidas'])
            ->when($id_categoria, function ($query, $id_categoria) {
                return $query->where('id_categoria', $id_categoria);
            })
            ->when($id_obra && !$id_categoria, function ($query, $id_obra) {
                return $query->where('id_obra', $id_obra);
            })
            ->orderBy('id', 'desc')
            ->paginate(8, ['*'], 'page', $page);

        // Prepare data for the view
        $viewData = [
            'produtos_saidas' => $produtos_saidas,
            'funcionarios' => $funcionarios
        ];

        // If request expects JSON (likely for AJAX), return JSON
        if ($request->expectsJson()) {
            $view = view('pages.estoque.saidas.partials.list', $viewData)->render();
            return response()->json([
                'html' => $view
            ]);
        }
    }

    public function consulta_epi(Request $request)
    {
        //dd($request->id_produto);

        $funcionario = CadastroFuncionario::where('id', $request->id_funcionario)->first();

        if (!$funcionario) {

            $mensagem = "Funcionário não encontrado.";
            return response()->json($mensagem);
        }

        $epis_funcionarios = FuncaoEpi::where('id_funcao', $funcionario->id_funcao)->get();

        if ($epis_funcionarios->isEmpty()) {

            $mensagem = "Funcionário não possui EPI cadastrado.";
            return response()->json($mensagem);
        }

        // Verificar se algum dos EPIs corresponde ao id_produto
        $epiEncontrado = $epis_funcionarios->contains('id_estoque', $request->id_produto);

        if ($epiEncontrado) {

            $mensagem = '<p> Este EPI está liberado para o funcionário: </p><strong><p>' . $funcionario->nome . "</p></strong>";

            return response()->json(
                [
                    'title' => 'Atenção!',
                    'html' => $mensagem,
                    'icon' => 'success',

                ]
            );
        } else {

            $mensagem = '<p> Este EPI não está liberado para o funcionário: </p><strong><p>' . $funcionario->nome . "</p></strong>" .
                "<p>É necessário justificar a entrega no campo logo abaixo!!! </p>" .

                '<div class="mb-3">
                            <label for="justificar_epi" class="form-label">Justificaficava de entrega de EPI:</label>
                            <textarea class="form-control" id="justificar_epi"  name="justificar_epi" rows="5"></textarea>
                        </div>';

            return response()->json(
                [
                    'title' => 'Atenção!',
                    'html' => $mensagem,
                    'icon' => 'warning',

                ]
            );
            return response()->json($mensagem);
        }

        // Coletar dados dos EPIs que estão em estoque
        $epis_estoque = Estoque::whereIn('id', $epis_funcionarios->pluck('id_estoque'))->get();

        if ($epis_estoque->isEmpty()) {

            $mensagem = "Nenhum EPI disponível em estoque.";
            return response()->json(
                [
                    'title' => 'Atenção!',
                    'html' => $mensagem,
                    'icon' => 'warning',

                ]
            );

            return response()->json("Nenhum EPI disponível em estoque.");
        }
    }


    public function justificar_epi(Request $request)
    {
        try {
            $funcionario = CadastroFuncionario::where('id', $request->id_funcionario)->first();
            if (!$funcionario) {
                return response()->json(["message" => "Funcionário não encontrado."], 404);
            }

            $justificar_epis = new EstoqueSaidaJustificarEPI([
                'id_funcionario'    => $request->id_funcionario,
                'id_funcao'         => $funcionario->id_funcao,
                'id_produto'        => $request->id_produto,
                'usuario'           => Auth::user()->email, // Certifique-se de que o usuário está logado
                'justificar_epi'    => $request->justificar_epi
            ]);

            $justificar_epis->save();

            return response()->json(["message" => "A justificativa para entregar o EPI foi validada"], 200);
        } catch (Exception $e) {

            // Log the error message and the stack trace for better debugging
            Log::error('Error saving justification: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Return a more detailed error message temporarily for debugging
            return response()->json(["message" => "Erro ao salvar a justificativa: " . $e->getMessage()], 500);
        }
    }

    public function listar_lotes_epis($idProduto)
    {
        $lotes = EstoqueEntrada::where('id_produto', $idProduto)
            ->where('quantidade_entrada', '>', 0) // Assegurando que somente lotes com estoque sejam listados
            ->get([
                'num_lote_entrada as numeroLote',
                'data_validade_lote_ca as validade',
                'quantidade_entrada as quantidadeDisponivel'
            ]);

        if ($lotes->isEmpty()) {
            return response()->json(['error' => 'Nenhum lote disponível para este produto.'], 404);
        }

        return response()->json($lotes);
    }
}
