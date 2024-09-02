<?php

namespace App\Http\Controllers;

use App\Interfaces\TiposVeiculosRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TiposVeiculosController extends Controller
{
    private $repository;

    public function __construct(TiposVeiculosRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        try {

            $tipos_veiculos = $this->repository->paginate(10);
            return view('pages.ativos.veiculos.tipos_veiculos.index', compact('tipos_veiculos'));

        } catch (\Exception $e) {

            Log::error('Erro ao listar documentos: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao listar documentos');
        }
    }

    public function create()
    {
        return view('pages.ativos.veiculos.tipos_veiculos.create');
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


            return redirect()->route('tipos_veiculos.index')->with($notification);

        } catch (\Exception $e) {

            Log::error('Erro ao cadastrar Tipo de veículo: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao cadastrar Tipo de veículo');
        }
    }

    public function edit($id)
    {
        try {

            $tipo_veiculo = $this->repository->edit($id);
            
            return view('pages.ativos.veiculos.tipos_veiculos.edit', compact('tipo_veiculo'));

        } catch (\Exception $e) {

            Log::error('Erro ao editar Tipo de veículo: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao editar Tipo de veículo');
        }
    }

    public function show($id)
    {
        try {

            $tipo_veiculo = $this->repository->edit($id);
            return view('pages.ativos.veiculos.tipos_veiculos.show', compact('tipo_veiculo'));

        } catch (\Exception $e) {

            Log::error('Erro ao acessar o Tipo de veículo: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao acessar o tipo de veículo');
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

            return redirect()->route('tipos_veiculos.index')->with($notification);

        } catch (\Exception $e) {

            Log::error('Erro ao atualizar Tipo de veículo: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao atualizar Tipo de veículo');
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

            return redirect()->route('tipos_veiculos.index')->with($notification);

        } catch (\Exception $e) {

            Log::error('Erro ao deletar Tipo de veículo: ' . $e->getMessage());

            return redirect()->back()->withErrors('Erro ao deletar Tipo de veículo');

        }
    }
}
