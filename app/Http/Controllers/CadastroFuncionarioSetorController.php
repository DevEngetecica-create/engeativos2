<?php

namespace App\Http\Controllers;

use App\Interfaces\CadastroFuncionarioSetorRepositoryInterface;
use App\Models\Log as ModelsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;
use Toastr;
use Log;

class CadastroFuncionarioSetorController extends Controller
{
    protected $repository;

    public function __construct(CadastroFuncionarioSetorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $setores = $this->repository->getAll();
        return view('pages.cadastros.funcionario.setores.index', compact('setores'));
    }

    public function create()
    {
        return view('pages.cadastros.funcionario.setores.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nome_setor' => 'required|unique:funcionarios_setor'
            ],
            [
                'nome_setor.required' => "O campo do setor está vazio",
                'nome_setor.unique'     => "Este setor já está cadastrado"
            ]
        );

        try {

            $setor = $this->repository->create($request->all());

            $notification = array(
                'title' => "Sucesso",
                'message' => "Setor cadastrado",
                'type' => 'success'
            );

            FacadesLog::info('Setor cadastrado', ['categoria' => $request->nome_setor]);

            return redirect()->route('cadastro.funcionario.setores.index')->with($notification);;
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao cadatrar o setor!!!",
                'type' => 'warning'
            );

            FacadesLog::error('Erro ao cadastrar setor', ['error' => $e->getMessage()]);

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $setor = $this->repository->getById($id);
        return view('pages.cadastros.funcionario.setores.edit', compact('setor'));
    }

    public function show($id)
    {
        $setor = $this->repository->getById($id);
        
        return view('pages.cadastros.funcionario.setores.show', compact('setor'));
    }

    public function update(Request $request, $id)
    {

        $request->validate(
            [
                'nome_setor' => 'required|unique:funcionarios_setor'
            ],
            [
                'nome_setor.required' => "O campo do setor está vazio",
                'nome_setor.unique'     => "Este setor já está cadastrado"
            ]
        );

        try {

            $funcionario = $this->repository->update($id, $request->all());

            $notification = array(
                'title' => "Sucesso",
                'message' => "Setor atualizado",
                'type' => 'success'
            );

            FacadesLog::info('Setor atualizado', ['setor' => $funcionario->nome_setor]);

            return redirect()->route('cadastro.funcionario.setores.index')->with($notification);

        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção",
                'message' => "Erro ao atualizar o setor",
                'type' => 'warning'
            );

            FacadesLog::error('Erro ao atualizar o setor', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);;
        }
    }

    public function delete($id)
    {
        try {

            $this->repository->delete($id);

            $notification = array(
                'title' => "Sucesso",
                'message' => "Setor deletado com sucesso!",
                'type' => 'success'
            );

            FacadesLog::info('Setor deletado', ['id' => $id]);

            return redirect()->route('cadastro.funcionario.setores.index');

        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção",
                'message' => "Erro ao deletar o setor.",
                'type' => 'warning'
            );

            FacadesLog::error('Erro ao deletar o setor', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);;
        }
    }
}
