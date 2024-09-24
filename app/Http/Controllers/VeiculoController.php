<?php

namespace App\Http\Controllers;

use App\Interfaces\VeiculoManutencaoRepositoryInterface;
use App\Interfaces\VeiculoSeguroRepositoryInterface;
use App\Interfaces\VeiculoIpvaRepositoryInterface;
use App\Interfaces\VeiculoRepositoryInterface;
use App\Interfaces\VeiculoCategoriaRepositoryInterface;
use App\Interfaces\VeiculoSubCategoriaRepositoryInterface;
use App\Interfaces\VeiculosDocsLegaisRepositoryInterface;
use App\Interfaces\TiposVeiculosRepositoryInterface;
use App\Interfaces\VeiculoPreventivaRepositoryInterface;
use App\Interfaces\CheckListManutPreventivaRepositoryInterface;
use App\Interfaces\VeiculosDocsTecnicosRepositoryInterface;
use App\Interfaces\VeiculoQuilometragemRepositoryInterface;
use App\Models\CadastroFornecedor;
use App\Models\CadastroFuncionario;
use App\Models\VeiculoImagens;
use App\Models\VeiculoSubCategoria;
use App\Models\Veiculo;
use App\Models\VeiculoQuilometragem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Toastr;

class VeiculoController extends Controller
{
    protected $veiculoRepository;
    protected $categorias;
    protected $subCateborias;
    protected $preventivaRepository;
    protected $checkListRepository;
    protected $manutencoes;
    protected $veiculo_quilometragem;
    protected $seguros;
    protected $ipvas;
    protected $docs_legais;
    protected $docs_tecnicos;
    protected $tipo;

    public function __construct(
        VeiculoRepositoryInterface $veiculoRepository,
        VeiculoSeguroRepositoryInterface $seguros,
        VeiculoIpvaRepositoryInterface $ipvas,
        VeiculoCategoriaRepositoryInterface $categorias,
        VeiculoSubCategoriaRepositoryInterface $subCateborias,
        VeiculoManutencaoRepositoryInterface $manutencoes,
        VeiculosDocsLegaisRepositoryInterface $docs_legais,
        VeiculosDocsTecnicosRepositoryInterface $docs_tecnicos,
        TiposVeiculosRepositoryInterface $tipo,
        VeiculoPreventivaRepositoryInterface $preventivaRepository,
        CheckListManutPreventivaRepositoryInterface $checkListRepository,
        VeiculoQuilometragemRepositoryInterface $veiculo_quilometragem

    ) {
        $this->veiculoRepository = $veiculoRepository;
        $this->seguros = $seguros;
        $this->ipvas = $ipvas;
        $this->manutencoes = $manutencoes;
        $this->veiculo_quilometragem = $veiculo_quilometragem;
        $this->categorias = $categorias;
        $this->subCateborias = $subCateborias;
        $this->docs_legais = $docs_legais;
        $this->docs_tecnicos = $docs_tecnicos;
        $this->tipo = $tipo;
        $this->preventivaRepository = $preventivaRepository;
        $this->checkListRepository = $checkListRepository;
    }

    public function index(Request $request)
    {
        //$search = $request->input('search');        
        //$listPage = $request->input('lista', 12); // Padrão para 10 itens por página se 'lista' não estiver definido

        // Paginar os resultados com base no método paginate do repositório
        $veiculos = $this->veiculoRepository->getAll();

        // Adiciona o termo de pesquisa às URLs de paginação
        //$veiculos->appends($request->except('page'));

        // Contagem total de veículos
        $count_veiculos_list = Veiculo::whereNull('deleted_at')->count();

        // Pegue o quilometragem do veículo com ID 51, caso seja necessário para a view
        $quilometragem = VeiculoQuilometragem::where('veiculo_id', 51)->first();

        // Retorne a view com os dados
        return view('pages.ativos.veiculos.index', compact('veiculos', 'quilometragem', 'count_veiculos_list'));
    }


    public function create()
    {
        $categorias =  $this->categorias->getAll();
        $tipos_veiculos =  $this->tipo->index();
        $preventivas = $this->preventivaRepository->getAll();

        return view('pages.ativos.veiculos.create', compact('categorias', 'tipos_veiculos', 'preventivas'));
    }

    public function store(Request $request)
    {
        try {

            $veiculo = $this->veiculoRepository->create($request->all(), $request->file('imagem'));

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Cadastro efetuado com sucesso!",
                'type' => 'success'
            );

            Log::info('Veículo criado: ', ['veiculo' => $veiculo]);
            return redirect()->route('veiculos.index')->with($notification);
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao cadastrar a quilometragem.",
                'type' => 'warning'
            );

            Log::error('Erro ao cadastrar veículo: ', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }


    public function edit($id)
    {

        $tipos_veiculos =  $this->tipo->index();
        $veiculo = $this->veiculoRepository->getById($id);
        $manutencoes = $veiculo->manutencoes()->orderBy('data_de_execucao', 'asc')->paginate(7);
        $docs_legais = $veiculo->documentosLegais()->orderBy('id', 'desc')->paginate(7);
        $categorias =  $veiculo->categorias->get();
        $subCategorias  =  $veiculo->subCategorias->get();
        $imagens = VeiculoImagens::where('veiculo_id', $id)->get();
        $preventivas = $this->preventivaRepository->getAll();

        // Definir a data e aplicar o fuso horário "America/Sao_Paulo"
        $data = Carbon::now()->setTimezone('America/Sao_Paulo');

        // Definir o locale para português
        $data->locale('pt_BR');

        // Formatar a data no estilo de exibição desejado
        $dataFormatada = $data->translatedFormat('l, d \d\e F \d\e Y');  // Exemplo: "quarta-feira, 11 de setembro de 2024"

        return view('pages.ativos.veiculos.edit', compact('veiculo', 'manutencoes', 'docs_legais', 'imagens', 'id', 'categorias', 'subCategorias', 'preventivas', 'tipos_veiculos', 'dataFormatada'));
    }

    public function update(Request $request, $id)
    {
        try {

            $veiculo = $this->veiculoRepository->update($id, $request->all());

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Registro atualizado",
                'type' => 'success'
            );

            Log::info('Veículo atualizado: ', ['veiculo' => $veiculo]);

            return redirect()->route('veiculos.index')->with($notification);
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao atualizar.",
                'type' => 'warning'
            );

            Log::error('Erro ao atualizar veículo: ', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }

    public function show($id)
    {
        $veiculo = $this->veiculoRepository->getById($id);
        $manutencoes = $veiculo->manutencoes()->orderBy('data_de_execucao', 'asc')->paginate(7);
        $docs_legais = $veiculo->documentosLegais()->orderBy('id', 'desc')->paginate(7);
        $docs_tecnicos = $veiculo->documentosTecnicos()->orderBy('id', 'desc')->paginate(7);
        $abastecimentos = $veiculo->abastecimento()->orderBy('id', 'desc')->paginate(7);
        $seguros = $veiculo->seguros()->orderBy('id', 'desc')->paginate(7);
        $ipvas = $veiculo->ipvas()->orderBy('id', 'desc')->paginate(7);
        $preventiva = $this->preventivaRepository->getById($veiculo->id_preventiva);
        $checkLists = $this->checkListRepository->getByIdVeiculo($veiculo->id);

        $fornecedores = CadastroFornecedor::select('id', 'razao_social')->get();
        $funcionarios = CadastroFuncionario::all();

        $abastecimentos = $veiculo->abastecimento()
            ->where('veiculo_id', $veiculo->id)
            ->orderBy('id', 'desc')
            ->get();

        $total_emissao_carbono = 0;
        $quantidade_registros = $abastecimentos->count();

        foreach ($abastecimentos as $abastecimento) {
            // Calcular a quilometragem percorrida
            $abastecimento->quilometragem_percorrida = $abastecimento->km_final - $abastecimento->km_inicial;

            // Calcular o consumo médio (km/l)
            if ($abastecimento->quantidade > 0) {
                $abastecimento->consumo_medio = $abastecimento->quilometragem_percorrida / $abastecimento->quantidade;
            } else {
                $abastecimento->consumo_medio = 0;
            }

            // Calcular o custo por litro
            if ($abastecimento->quantidade > 0) {
                $abastecimento->custo_por_litro = $abastecimento->valor_total / $abastecimento->quantidade;
            } else {
                $abastecimento->custo_por_litro = 0;
            }

            // Calcular o custo por quilômetro
            if ($abastecimento->quilometragem_percorrida > 0) {
                $abastecimento->custo_por_km = $abastecimento->valor_total / $abastecimento->quilometragem_percorrida;
            } else {
                $abastecimento->custo_por_km = 0;
            }

            // Calcular a emissão de carbono com base no tipo de combustível
            switch ($abastecimento->combustivel) {
                case 'gasolina':
                    // Gasolina com 27% de etanol (gasolina C)
                    $fator_emissao = (0.73 * 2.31) + (0.27 * 1.51); // Calculado como 2,094 kg de CO₂ por litro
                    break;
                case 'diesel':
                    $fator_emissao = 2.68; // Diesel: 2,68 kg de CO₂ por litro
                    break;
                case 'etanol':
                    $fator_emissao = 1.51; // Etanol: 1,51 kg de CO₂ por litro
                    break;
                default:
                    $fator_emissao = 0; // Caso não seja um combustível conhecido, emissão zero
                    break;
            }

            // Calcular a emissão de CO₂
            if ($abastecimento->quantidade > 0) {
                $abastecimento->emissao_carbono = $abastecimento->quantidade * $fator_emissao;  // Emissão de CO₂ em kg
            } else {
                $abastecimento->emissao_carbono = 0;
            }

            // Somar ao total de emissão de carbono
            $total_emissao_carbono += $abastecimento->emissao_carbono;
        }

        // Calcular a média de emissão de carbono por abastecimento
        if ($quantidade_registros > 0) {
            $media_emissao_carbono = $total_emissao_carbono / $quantidade_registros;
        } else {
            $media_emissao_carbono = 0;
        }

        //calcular a data de validade dos documentos técnicos
        foreach ($docs_tecnicos as $doc) {
            if (!empty($doc->data_documento) && !empty($doc->data_validade)) {
                // Só faz o parse se os campos não estiverem vazios
                $data_documento = Carbon::parse($doc->data_documento);
                $data_validade = Carbon::parse($doc->data_validade);

                // Calcula a diferença em dias (ou meses, ou anos)
                $doc->diferenca_dias = $data_documento->diffInDays($data_validade);
            } else {
                // Se os campos forem vazios, a diferença será null
                $doc->diferenca_dias = null;
            }
        }



        $imagens = VeiculoImagens::where('veiculo_id', $id)->get();

        return view('pages.ativos.veiculos.show', compact('veiculo', 'seguros', 'ipvas', 'manutencoes', 'preventiva', 'checkLists', 'docs_legais', 'docs_tecnicos', 'imagens', 'id',  'abastecimentos', 'fornecedores', 'funcionarios'));
    }

    public function delete($id)
    {
        try {

            $this->veiculoRepository->delete($id);

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Registro deletado.",
                'type' => 'success'
            );

            Log::info('Veículo deletado: ', ['id' => $id]);

            return redirect()->back()->with($notification);
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao deletar.",
                'type' => 'warning'
            );

            Log::error('Erro ao deletar veículo: ', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }

    public function storeImage(Request $request, $id)
    {
        try {

            $this->veiculoRepository->storeImage($request->veiculo_id, $request->file('imagens'));

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Imagem cadastrada com sucesso!",
                'type' => 'success'
            );

            return redirect()->route('veiculo.show', $request->veiculo_id)->with($notification);
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao criar a imagem.",
                'type' => 'warning'
            );

            Log::error('Erro ao criar a imagem: ', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }

    public function updateImage(Request $request, $id)
    {

        try {

            if ($request->file('imagem') or $request->descricao) {

                $veiculo = $this->veiculoRepository->updateImage($id, $request->file('imagem'), $request->all());

                $notification = array(
                    'title' => "Sucesso!!!",
                    'message' => "Imagem atualizada com sucesso!",
                    'type' => 'success'
                );

                Log::info('Imagem atualizada: ', ['veiculo' => $veiculo]);

                return redirect()->back()->with($notification);
            } else {

                $notification = array(
                    'title' => "Atenção!!!",
                    'message' => "Escolha uma imagem para alterar",
                    'type' => 'warning'
                );

                return redirect()->back()->with($notification);
            }
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao atualizar a imagem.",
                'type' => 'warning'
            );

            Log::error('Erro ao atualizar a imagem: ', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }

    public function deleteImage(Request $request, $id)
    {
        try {

            $this->veiculoRepository->deleteImage($id);

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Imagem deletada com sucesso!",
                'type' => 'success'
            );

            Log::info('Imagem do Veículo deletado: ', ['id' => $id]);

            return redirect()->back()->with($notification);
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao deletar a imagem.",
                'type' => 'warning'
            );

            Log::error('Erro ao deletar a imagem: ', ['error' => $e->getMessage()]);

            return redirect()->back()->whit($notification);
        }
    }

    public function pesquisarSubcategoria(Request $request)
    {
        $selecao = $request->selecao;

        $categoria = VeiculoSubCategoria::where('id_categoria', $selecao)->get();

        if ($categoria->isEmpty()) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Nenhuma subcategoria encontrada",
                'type' => 'warning'
            );

            return redirect()->back()->whit($notification);
        }

        return response()->json($categoria);
    }
}
