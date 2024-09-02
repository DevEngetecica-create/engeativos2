<?php

namespace App\Http\Controllers;

use App\Interfaces\DocsLegaisRepositoryInterface;
use App\Interfaces\TiposVeiculosRepositoryInterface;
use App\Models\DocsLegais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocsLegaisController extends Controller
{
    private $repository;
    private $tipo_veiculo;

    public function __construct(
        DocsLegaisRepositoryInterface $repository,
        TiposVeiculosRepositoryInterface $tipo_veiculo
    ) {
        $this->repository = $repository;
        $this->tipo_veiculo = $tipo_veiculo;
    }

    public function index($tipo_veiculo_id)
    {
        try {
            // Se um ID de tipo de veículo for passado, filtra os documentos por esse tipo
            if ($tipo_veiculo_id) {

                $docs = $this->repository->getByTipoVeiculo($tipo_veiculo_id);

            } else {

                $docs = $this->repository->index(); // Retorna todos os documentos se nenhum tipo de veículo for especificado
            }

            $tipo_veiculo = $this->tipo_veiculo->getAll();

            return view('pages.ativos.veiculos.tipos_veiculos.docs_legais.index', compact('docs', 'tipo_veiculo_id', 'tipo_veiculo'));

        } catch (\Exception $e) {

            Log::error('Erro ao listar documentos: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao listar documentos');
        }
    }


    public function create($tipo_veiculo_id)
    {
       
        $tipo_veiculo = $this->tipo_veiculo->getAll();
        
        return view('pages.ativos.veiculos.tipos_veiculos.docs_legais.create', compact('tipo_veiculo', 'tipo_veiculo_id'));
    }

    public function store(Request $request)
    {
       
        try {

            $this->repository->store($request->all());

           $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Cadastro efetuado com sucesso!",
                'type' => 'success'
            );

            return redirect()->route('docs_legais.index', $request->tipo_veiculo)->with($notification);

        } catch (\Exception $e) {

            Log::error('Erro ao cadastrar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao cadastrar documento');
        }

    }

    public function edit($id)
    {
        try {

            $tipo_veiculo = $this->tipo_veiculo->getAll();

            $doc = $this->repository->edit($id);

            return view('pages.ativos.veiculos.tipos_veiculos.docs_legais.edit', compact('doc', 'tipo_veiculo'));

        } catch (\Exception $e) {
            Log::error('Erro ao editar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao editar documento');
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $this->repository->update($id, $request->all());

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Registro atualizado",
                'type' => 'success'
            );

            return redirect()->route('docs_legais.index', $request->tipo_veiculo)->with($notification);

        } catch (\Exception $e) {

            Log::error('Erro ao atualizar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao atualizar documento');
        }
    }

    public function show($id)
    {
        try {

            $tipo_veiculo = $this->tipo_veiculo->getAll();

            $doc = $this->repository->edit($id);

            return view('pages.ativos.veiculos.tipos_veiculos.docs_legais.show', compact('doc', 'tipo_veiculo'));

        } catch (\Exception $e) {

            Log::error('Erro ao editar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao editar documento');
        }
    }

    public function delete($id)
    {
        try {
            $this->repository->delete($id);

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Registro deletado.",
                'type' => 'success'
            );

            return redirect()->route('docs_legais.index')->with($notification);

        } catch (\Exception $e) {
            Log::error('Erro ao deletar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao deletar documento');
        }
    }
}
