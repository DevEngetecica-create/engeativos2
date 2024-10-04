<?php

namespace App\Repositories;

use App\Interfaces\VeiculosDocsLegaisRepositoryInterface;
use App\Interfaces\DocsLegaisRepositoryInterface;
use App\Models\DocsLegais;
use App\Models\VeiculosDocsLegais;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VeiculosDocsLegaisRepository implements VeiculosDocsLegaisRepositoryInterface
{
    private $documentos;

    public function __construct(DocsLegaisRepositoryInterface $documentos)
    {
        $this->documentos = $documentos;
    }

    public function index()
    {
        return VeiculosDocsLegais::all();
    }

    public function create(array $data)
    {
        return VeiculosDocsLegais::create($data);
    }

    public function store(array $data)
    {
        // Obtenha os documentos legais associados ao tipo de veículo
        $create_docs = DocsLegais::where('tipo_veiculo', $data['tipo'])->get();

        foreach ($create_docs as $documentos) {
            // Crie uma nova instância do modelo VeiculosDocsLegais
            $docs = new VeiculosDocsLegais;

            // Preencha os campos do documento legal do veículo
            $docs->id_tipo_veiculo = $documentos->tipo_veiculo; // Acessa diretamente o atributo
            $docs->id_doc_legal = $documentos->id;
            $docs->id_veiculo = $data['id']; // Utilize o ID do veículo passado no $data
            $docs->arquivo = "";
            $docs->data_documento = $documentos->data_documento ?? null;
            $docs->validade = $documentos->validade ?? null;
            $docs->data_validade = $documentos->data_validade ?? null;

            // Salve o documento legal do veículo
            $docs->save();
        }

        return true;
    }


    public function edit(int $id)
    {
        return VeiculosDocsLegais::findOrFail($id);
    }

    public function update(int $id, array $data, $arquivos)
    {
        $doc = VeiculosDocsLegais::findOrFail($id);

        if ($arquivos) {
            $nome_arquivo = $arquivos->getClientOriginalName();

            $caminho_arquivo = 'uploads/veiculos/docs_legais/' . $doc->id_veiculo . '/' . $doc->arquivo;

            // Verifica se o arquivo já existe e o exclui antes de salvar o novo
            if (Storage::disk('public')->exists($caminho_arquivo)) {
                Storage::disk('public')->delete($caminho_arquivo);
            }

            // Armazena o novo arquivo
            $arquivos->storeAs('uploads/veiculos/docs_legais/' . $doc->id_veiculo, $nome_arquivo, 'public');

            // Atualiza o campo de nome de arquivo no banco de dados
            $doc->arquivo = $nome_arquivo;
        }

        // Atualiza os outros campos
        $doc->data_documento = $data['data_documento'];
        $doc->data_validade = $data['data_validade'];

        // Salva as alterações
        $doc->save();

        return $doc;
    }


    public function show(int $id)
    {
        return VeiculosDocsLegais::findOrFail($id);
    }

    public function delete(int $id)
    {
        $doc = VeiculosDocsLegais::findOrFail($id);
        $doc->delete();
        return $doc;
    }

    public function search(string $query)
    {
        return VeiculosDocsLegais::where('nome_documento', 'like', '%' . $query . '%')->get();
    }

    public function paginate(int $perPage)
    {
        return VeiculosDocsLegais::paginate($perPage);
    }

    public function upload(int $id)
    {
        $doc = VeiculosDocsLegais::findOrFail($id);
       
         // Armazena o novo arquivo
         $arquivos->storeAs('uploads/veiculos/docs_tecnicos/' . $doc->id_veiculo, $nome_arquivo, 'public');
    }

    public function downloads(int $id)
    {
        try {
            
            $doc = VeiculosDocsLegais::findOrFail($id);
    
            // Caminho do arquivo no storage público
            $path = "uploads/veiculos/docs_legais/" . $doc->id_veiculo . "/" . $doc->arquivo;
    
            // Verifica se o arquivo existe no disco 'public'
            if (Storage::disk('public')->exists($path)) {

                return Storage::disk('public')->download($path);

            } else {

                throw new \Exception('Arquivo não encontrado.');
            }
        } catch (\Exception $e) {

            Log::error('Erro ao fazer o download: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao fazer o download');

        }
    }
}
