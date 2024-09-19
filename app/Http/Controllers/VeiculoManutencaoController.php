<?php

namespace App\Http\Controllers;

use App\Interfaces\VeiculoManutencaoRepositoryInterface;
use App\Interfaces\VeiculoRepositoryInterface;
use App\Models\CadastroFornecedor;
use App\Models\CadastroObra;
use App\Models\Servico;
use App\Models\Veiculo;
use App\Models\VeiculoManutencaoImagens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VeiculoManutencaoController extends Controller
{
    protected $repository;
    protected $repository_veiculo;

    public function __construct(
        VeiculoManutencaoRepositoryInterface $repository,
        VeiculoRepositoryInterface $repository_veiculo
    ) {
        $this->repository = $repository;
        $this->repository_veiculo = $repository_veiculo;
    }

    public function index()
    {
        $manutencoes = $this->repository->getAll();
        return view('pages.ativos.veiculos.manutencao.index', compact('manutencoes'));
    }

    public function create(Veiculo $veiculo,)
    {

        $fornecedores = CadastroFornecedor::select('id', 'razao_social')->get();
        $servicos = Servico::select('id', 'nomeServico')->get();
        $obras = CadastroObra::all();

        $maiorValorQuilometragem = DB::table('veiculo_quilometragems')->where('veiculo_id', $veiculo->id)
            ->whereNull('deleted_at')
            ->max('quilometragem_nova');

        return view('pages.ativos.veiculos.manutencao.create', compact('veiculo', 'obras', 'fornecedores', 'servicos', 'maiorValorQuilometragem'));
    }

    public function store(Request $request)
    {
        try {
            //dd($request->veiculo_id);
            $manutencao = $this->repository->create($request->all(), $request->file('arquivo'));

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Manutenção cadastrada com sucesso!",
                'type' => 'success'
            );

            Log::info('Manutenção cadastrada', ['manutencao' => $manutencao]);

            return redirect()->route('veiculo.show', $request->veiculo_id . "#manutencoes")->with($notification);
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Erro ao cadastrar manutenção.",
                'type' => 'warning'
            );

            Log::error('Erro ao cadastrar manutenção', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);;
        }
    }

    public function edit($id)
    {
        $manutencao = $this->repository->getById($id);

        return view('pages.ativos.veiculos.manutencao.edit', compact('manutencao'));
    }

    public function show($id)
    {

        $manutencoes = $this->repository->getById($id);

        $obras = CadastroObra::select('id', 'codigo_obra', 'razao_social')->orderByDesc('id')->get();

        $imagens = VeiculoManutencaoImagens::where('manutencao_id', $id)->get();

        return view('pages.ativos.veiculos.manutencao.show', compact('obras', 'imagens', 'manutencoes'));
    }

    public function update(Request $request, $id)
    {

        try {
            $manutencao = $this->repository->update($id, $request->all(), $request->file('arquivos'));
            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Manutenção atualizada com sucesso!",
                'type' => 'success'
            );

            Log::info('Manutenção atualizada', ['manutencao' => $manutencao]);

            return redirect()->route('veiculo_manutencoes.index')->with($notification);
        } catch (\Exception $e) {
            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao atualizar manutenção.",
                'type' => 'warning'
            );

            Log::error('Erro ao atualizar manutenção', ['error' => $e->getMessage()]);
            return redirect()->back()->with($notification);;
        }
    }

    public function upload(Request $request, $id)
    {
        try {
            $manutencao = $this->repository->upload($id, $request->all(), $request->file('arquivo'));

            Log::info('Adicionado arquivo', ['manutencao' => $manutencao]);
            return response()->json('ok');

        } catch (\Exception $e) {

            Log::error('Erro ao atualizar arquivo', ['error' => $e->getMessage()]);
            return response()->json('ok');
        }
    }

    public function destroy($id)
    {
        try {
            $this->repository->delete($id);

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Manutenção deletada com sucesso!",
                'type' => 'success'
            );

            Log::info('Manutenção deletada', ['id' => $id]);

            return redirect()->route('veiculo_manutencoes.index')->with($notification);
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao deletar manutenção.",
                'type' => 'warning'
            );

            Log::error('Erro ao deletar manutenção', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);;
        }
    }

    public function storeImage(Request $request, $id)
    {
        try {

            $this->repository->storeImage($request->manutencao_id, $request->file('imagens'));

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

                $veiculo = $this->repository->updateImage($id, $request->file('imagem'), $request->all());

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

            $this->repository->deleteImage($id);

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

    public function download($id)
    {
        try {
            // Obter o documento
            $doc = $this->repository->getAll()->where('id', $id)->first();

            if ($doc->arquivo)

                // Executar o download através do método do repositório
                return $this->repository->download($id);

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Download efetuado com sucesso!",
                'type' => 'success'
            );

            return redirect()->back()->whit($notification);
            
        } catch (\Exception $e) {
            Log::error('Erro ao fazer o download: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao fazer o download');
        }
    }
}
