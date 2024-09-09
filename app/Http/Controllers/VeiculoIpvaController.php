<?php

namespace App\Http\Controllers;

use App\Interfaces\VeiculoIpvaRepositoryInterface;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Toastr;
use Log;

class VeiculoIpvaController extends Controller
{
    protected $repository;

    public function __construct(VeiculoIpvaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $ipvas = $this->repository->getAll();
        return view('pages.ativos.veiculos.ipva.index', compact('ipvas'));
    }

    public function create(Veiculo $veiculo)
    {
        return view('pages.ativos.veiculos.ipva.create', compact('veiculo'));
    }

    public function store(Request $request)
    {
        try {
            $ipva = $this->repository->create($request->all()); 

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "IPVA cadastrado com sucesso!",
                'type' => 'success'
            );

            Log::info('IPVA cadastrado', ['ipva' => $ipva]);

            return redirect()->route('veiculo.show', $request->veiculo_id."#ipvas")->with($notification);


        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao cadastrar IPVA.",
                'type' => 'warning'
            );
            
            Log::error('Erro ao cadastrar IPVA', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }

    public function edit(Veiculo $veiculo, $id)
    {
        $ipva = $this->repository->getById($id);
        return view('pages.ativos.veiculos.ipva.edit', compact('ipva', 'veiculo'));
    }

    public function update(Request $request, $id)
    {
        try {

            $ipva = $this->repository->update($id, $request->all());

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "IPVA atualizado com sucesso!",
                'type' => 'success'
            );

            Log::info('IPVA atualizado', ['ipva' => $ipva]);

            return redirect()->route('veiculo.show', $request->veiculo_id."#ipvas")->with($notification);

        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao atualizar IPVA.",
                'type' => 'warning'
            );

            Log::error('Erro ao atualizar IPVA', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }

    public function delete($id)
    {
        try {
            
            $ipva =  $this->repository->getById($id);

            $this->repository->delete($id);
            
            $notification = array(
                'title' => "Secesso!!!",
                'message' => "IPVA deletado com sucesso!",
                'type' => 'success'
            );

            Log::info('IPVA deletado', ['id' => $id]);

            return redirect()->route('veiculo.show', $ipva->veiculo_id."#ipvas")->with($notification);

        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao deletar IPVA.",
                'type' => 'warning'
            );

            Log::error('Erro ao deletar IPVA', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }
}