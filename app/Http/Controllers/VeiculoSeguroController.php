<?php

namespace App\Http\Controllers;

use App\Interfaces\VeiculoSeguroRepositoryInterface;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Toastr;
use Log;

class VeiculoSeguroController extends Controller
{
    protected $repository;

    public function __construct(VeiculoSeguroRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $seguros = $this->repository->getAll();
        return view('pages.ativos.veiculos.seguro.index', compact('seguros'));
    }

    public function create(Veiculo $veiculo)    {
      
        return view('pages.ativos.veiculos.seguro.create', compact('veiculo'));
    }

    public function store(Request $request)
    {
        try {
            $seguro = $this->repository->create($request->all());

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Seguro cadastrado com sucesso!",
                'type' => 'success'
            );
         
            Log::info('Seguro cadastrado', ['seguro' => $seguro]);

            return redirect()->route('veiculo.show', $request->veiculo_id."#seguros")->with($notification);

        } catch (\Exception $e) {           

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao cadastrar seguro.",
                'type' => 'warning'
            );

            Log::error('Erro ao cadastrar seguro', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }

    public function edit($id)
    {
        $seguro = $this->repository->getById($id);
        return view('pages.ativos.veiculos.seguro.edit', compact('seguro'));
    }

    public function update(Request $request, $id)
    {

        try {
            $seguro = $this->repository->update($id, $request->all());
            
            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Seguro atualizado com sucesso!",
                'type' => 'success'
            );

            Log::info('Seguro atualizado', ['seguro' => $seguro]);

            return redirect()->route('veiculo.show', $request->veiculo_id."#seguros")->with($notification);
            
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao atualizar seguro.",
                'type' => 'warning'
            );

            Log::error('Erro ao atualizar seguro', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }

    public function delete($id)
    {
        try {

            $seguro =  $this->repository->getById($id);
            
            $this->repository->delete($id);

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Seguro deletado com sucesso!",
                'type' => 'success'
            );

            Log::info('Seguro deletado', ['id' => $id]);

            return redirect()->route('veiculo.show', $seguro->veiculo_id."#seguros")->with($notification);

        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao deletar seguro.",
                'type' => 'success'
            );

            Log::error('Erro ao deletar seguro', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }
}