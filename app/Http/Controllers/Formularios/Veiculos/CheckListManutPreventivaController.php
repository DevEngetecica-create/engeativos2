<?php

namespace App\Http\Controllers\Formularios\Veiculos;

use App\Http\Controllers\Controller;
use App\Interfaces\CheckListManutPreventivaRepositoryInterface;
use App\Interfaces\VeiculoPreventivaRepositoryInterface;
use App\Models\CheckListManutPreventiva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CheckListManutPreventivaController extends Controller
{
    private $preventivaRepository;
    private $checkListRepository;

    public function __construct(
        VeiculoPreventivaRepositoryInterface $preventivaRepository,
        CheckListManutPreventivaRepositoryInterface $checkListRepository
    ) {
        $this->preventivaRepository = $preventivaRepository;
        $this->checkListRepository = $checkListRepository;
    }

    public function index($id_manut_preventiva)
    {

        $preventiva = $this->preventivaRepository->getById($id_manut_preventiva);
        $checkLists = $this->checkListRepository->getByPreventivaId($id_manut_preventiva);

        return view('pages.ativos.veiculos.preventivas.checklist.index', compact('preventiva', 'checkLists'));
    }



    public function create(Request $request, $id_manut_preventiva)
    {
        $preventiva = $this->preventivaRepository->getById($id_manut_preventiva);
        $periodo = $request->query('periodo'); // Obtém o período da URL
        $id_veiculo = $request->query('id_veiculo');

        return view('pages.ativos.veiculos.preventivas.checklist.create', compact('preventiva', 'periodo', 'id_veiculo'));
    }

    public function store(Request $request)
    {

        // Validação dos dados do formulário
        $data = $request->validate(
            [
                'id_manut_preventiva' => 'required|integer',
                'nome_servicos' => 'required|array', // JSON string
                'checklist' => 'required|array',
                'observacoes' => 'nullable|array',
                'file.*.*' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:2048'
            ],
            [
                'file.*.*.mimes' => "Extensão inválida. Extensões permitidas: jpg, png, pdf, doc, docx"
            ]
        );

        // Inicializar o array $files com null para todos os índices
        $files = array_fill(0, count($data['checklist']), null);

        foreach ($data['checklist'] as $key => $checklist) {
            // Adicionar arquivos se existir
            if ($request->file("file.$key")) {
                $file = $request->file("file.$key");
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/veiculos/preventivas/' . $data['id_manut_preventiva'], $fileName, 'public');
                $files[$key] = $filePath;
            }
        }

        // Converter arrays para JSON
       
        $data['nome_servico'] = json_encode($data['nome_servicos']);
        $data['situacaoPreventiva'] = $request->situacaoPreventiva;
        $data['periodo'] = $request->periodo;
        $data['id_veiculo'] = $request->id_veiculo;
        $data['situacao'] = json_encode($data['checklist']);
        $data['observacoes'] = json_encode($data['observacoes']);
        // Converter o array $files para JSON
        $data['files'] = json_encode($files);

        // Salvar os dados no repositório
        $this->checkListRepository->create($data);

        return redirect()->route('veiculo.show', $request->id_veiculo.'#cheklist_preventiva')->with('success', 'Checklist salvo com sucesso!');
    }




    public function edit(Request $request, $id)
    {

        $checklist = $this->checkListRepository->find($id);

        $preventiva = $this->preventivaRepository->getById($checklist->id_manut_preventiva);
        $periodo = $request->query('periodo'); // Obtém o período da URL

        return view('pages.ativos.veiculos.preventivas.checklist.edit', compact('checklist', 'preventiva', 'periodo'));
    }

    public function update(Request $request, $id)
    {
        // Validação dos dados do formulário
        $data = $request->all();

        // Obter os arquivos existentes do banco de dados
        $arquivo = CheckListManutPreventiva::find($id);
        $arquivoArray = json_decode($arquivo->files, true) ?? [];
        $files = array_fill(0, count($data['checklist']), "null");

        foreach ($data['checklist'] as $key => $checklist ) {
            // Verificar se um novo arquivo foi enviado
            if ($request->hasFile("file.$key")) {
                // Deletar o arquivo antigo se existir
                if (isset($arquivoArray[$key]) && $arquivoArray[$key] !== "null") {
                    Storage::disk('public')->delete($arquivoArray[$key]);
                }

                // Armazenar o novo arquivo
                $file = $request->file("file.$key");
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/veiculos/preventivas/' . $data['id_manut_preventiva'], $fileName, 'public');
                $files[$key] = $filePath;
            } else {
                // Manter o arquivo existente ou "null" se não houver arquivo
                $files[$key] = $arquivoArray[$key] ?? "null";
            }
        }

        // Converter arrays para JSON
        $data['situacao'] = json_encode($data['checklist']);
        $data['observacoes'] = json_encode($data['observacoes']);

        // Custom converter for the files array to match the required format
        $filesJson = '[' . implode(',', array_map(function ($file) {
            return $file === "null" ? '"null"' : ($file === null ? 'null' : '"' . $file . '"');
        }, $files)) . ']';

        $data['files'] = $filesJson;

        // Atualizar os dados no repositório
        $this->checkListRepository->update($id, $data);

        return redirect()->route('veiculo.show', $arquivo->id_veiculo.'#cheklist_preventiva')
            ->with('success', 'Checklist atualizado com sucesso!');
    }


    public function show($id)
    {
        $checklist = $this->checkListRepository->find($id);
        $preventiva = $this->preventivaRepository->getById($checklist->id_manut_preventiva);

        return view('pages.ativos.veiculos.preventivas.checklist.show', compact('checklist', 'preventiva'));
    }

    public function destroy($id)
    {
        $checklist = $this->checkListRepository->find($id);

        $this->checkListRepository->delete($id);

        return redirect()->route('veiculo.show', $checklist->id_veiculo.'#cheklist_preventiva')->with('success', 'Checklist excluído com sucesso.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $checkLists = $this->checkListRepository->search($query);
        return view('veiculos.preventivas.checklist.index', compact('checkLists'));
    }
}
