<?php


namespace App\Http\Controllers;

use App\Interfaces\VeiculosDocsLegaisRepositoryInterface;
use App\Models\VeiculosDocsLegais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VeiculosDocsLegaisController extends Controller
{
    private $repository;

    public function __construct(VeiculosDocsLegaisRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        try {

            $docs = $this->repository->index();
            return view('veiculos.partials.docs_legais.index', compact('docs'));
        } catch (\Exception $e) {

            Log::error('Erro ao listar documentos: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao listar documentos');
        }
    }

    public function create($id)
    {

        return view('veiculos.partials.docs_legais.create', compact('id'));
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
            return view('veiculos.partials.docs_legais.edit', compact('doc'));
        } catch (\Exception $e) {

            Log::error('Erro ao editar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao editar documento');
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $this->repository->update($id, $request->all(), $request->file('arquivo'));
            toastr()->success('Documento atualizado com sucesso!');
            return redirect()->route('veiculos_docs_legais.index');
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
            return redirect()->route('veiculos_docs_legais.index');
        } catch (\Exception $e) {

            Log::error('Erro ao deletar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao deletar documento');
        }
    }


    public function upload($id)
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
