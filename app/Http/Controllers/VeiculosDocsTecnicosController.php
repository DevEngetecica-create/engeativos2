<?php


namespace App\Http\Controllers;

use App\Interfaces\VeiculosDocsTecnicosRepositoryInterface;
use App\Models\VeiculosDocsTecnicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VeiculosDocsTecnicosController extends Controller
{
    private $repository;

    public function __construct(VeiculosDocsTecnicosRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        try {

            $docs = $this->repository->index();
            return view('veiculos.partials.docs_tecnicos.index', compact('docs'));
        } catch (\Exception $e) {

            Log::error('Erro ao listar documentos: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao listar documentos');
        }
    }

    public function create($id)
    {

        return view('veiculos.partials.docs_tecnicos.create', compact('id'));
    }

    public function store(Request $request)
    {        

        try {

            $this->repository->store($request->all());

            toastr()->success('Documento cadastrado com sucesso!');

            return redirect()->route('veiculos.show', $request->id_veiculo);
        } catch (\Exception $e) {

            Log::error('Erro ao cadastrar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao cadastrar documento');
        }
    }

    public function edit($id)
    {
        try {

            $doc = $this->repository->edit($id);
            return view('veiculos.partials.docs_tecnicos.edit', compact('doc'));
        } catch (\Exception $e) {

            Log::error('Erro ao editar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao editar documento');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->repository->update($id, $request->all(), $request->file('arquivo'));

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Documento atualizado com sucesso!",
                'type' => 'success'
            );

            return redirect()->route('veiculo.show', $request->veiculo_id."#abastecimentos")->with($notification);

        } catch (\Exception $e) {

            Log::error('Erro ao atualizar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao atualizar documento');
        }
    }

    public function delete($id)
    {
        try {
            $this->repository->delete($id);
            toastr()->success('Documento deletado com sucesso!');
            return redirect()->route('veiculos_docs_tecnicos.index');
        } catch (\Exception $e) {

            Log::error('Erro ao deletar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao deletar documento');
        }
    }

    public function upload(Request $request, $id)
    {
        try {

            $doc_tecnico = $this->repository->upload($id, $request->all(), $request->file('arquivo'));

            Log::info('Adicionado arquivo', ['doc_tecnico' => $doc_tecnico]);

            return response()->json('ok');

        } catch (\Exception $e) {

            Log::error('Erro ao atualizar arquivo', ['error' => $e->getMessage()]);

            return response()->json('ok');
        }
    }


    public function download($id)
    {
        try {
            // Obter o documento
            $doc = $this->repository->index()->where('id', $id)->first();

            if($doc->arquivo)

            // Executar o download através do método do repositório
            return $this->repository->download($id);

            toastr()->success('Download efetuado com sucesso!');

            return redirect()->back()->withErrors('Erro ao atualizar documento');
            
        } catch (\Exception $e) {
            Log::error('Erro ao fazer o download: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao fazer o download');
        }
    }
}
