<?php

namespace App\Http\Controllers;

use App\Interfaces\VeiculoQuilometragemRepositoryInterface;
use Illuminate\Http\Request;
use Toastr;
use Illuminate\Support\Facades\Log;

class VeiculoQuilometragemController extends Controller
{
    /**
     * Register services.
     *
     * @return void
     */

    protected $repository;

    public function __construct(VeiculoQuilometragemRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $quilometragens = $this->repository->getAll();
        return view('veiculo_quilometragems.index', compact('quilometragens'));
    }

    public function create()
    {
        return view('veiculo_quilometragems.create');
    }

    public function store(Request $request)
    {
        try {

            $quilometragem = $this->repository->create($request->all());

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Cadastro efetuado com sucesso!",
                'type' => 'success'
            );

            Log::info('Quilometragem cadastrada', ['quilometragem' => $quilometragem]);
            return redirect()->route('veiculo_quilometragems.index')->with($notification);
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao cadastrar.",
                'type' => 'warning'
            );

            Log::error('Erro ao cadastrar quilometragem', ['error' => $e->getMessage()]);

            return redirect()->back()->with($notification);
        }
    }

    public function edit($id)
    {
        $quilometragem = $this->repository->getById($id);
        return view('veiculo_quilometragems.edit', compact('quilometragem'));
    }

    public function update(Request $request, $id)
    {
        try {
            $quilometragem = $this->repository->update($id, $request->all());

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Registro atualizado",
                'type' => 'success'
            );

            Log::info('Quilometragem atualizada', ['quilometragem' => $quilometragem]);
            return redirect()->route('veiculo_quilometragems.index')->with($notification);;
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao atualizar quilometragem",
                'type' => 'warning'
            );

            Log::error('Erro ao atualizar quilometragem', ['error' => $e->getMessage()]);
            return redirect()->back()->with($notification);;
        }
    }

    public function destroy($id)
    {
        try {

            $this->repository->delete($id);

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Registro deletado.",
                'type' => 'success'
            );

            Log::info('Quilometragem deletada', ['id' => $id]);
            return redirect()->route('veiculo_quilometragems.index')->with($notification);;
        } catch (\Exception $e) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao deletar.",
                'type' => 'warning'
            );

            Log::error('Erro ao deletar quilometragem', ['error' => $e->getMessage()]);
            return redirect()->back()->with($notification);;
        }
    }
}
