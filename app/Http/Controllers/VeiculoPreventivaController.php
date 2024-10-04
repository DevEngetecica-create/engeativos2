<?php

namespace App\Http\Controllers;

use App\Interfaces\VeiculoPreventivaRepositoryInterface;
use Illuminate\Http\Request;
use Toastr;
use Log;


class VeiculoPreventivaController extends Controller
{
    private $preventivaRepository;

    public function __construct(VeiculoPreventivaRepositoryInterface $preventivaRepository)
    {
        $this->preventivaRepository = $preventivaRepository;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $preventivas = $this->preventivaRepository->paginate(10, $search);
        return view('pages.ativos.veiculos.preventivas.index', compact('preventivas'));
    }

    public function create()
    {
        return view('pages.ativos.veiculos.preventivas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome_preventiva' => 'required|string|max:255',
            'nome_servico' => 'required|array',
            'tipo_veiculo' => 'required|string|max:255',
            'situacao' => 'required|array',
            'periodo' => 'required|array',
            'tipo' => 'required|array'
        ]);

        $data['nome_servico'] = json_encode($data['nome_servico']);
        $data['situacao'] = json_encode($data['situacao']);
        $data['periodo'] = json_encode($data['periodo']);
        $data['tipo'] = json_encode($data['tipo']);

        $this->preventivaRepository->create($data);

        return redirect()->route('veiculo.manut_preventiva.index')->with('success', 'Preventiva criada com sucesso!');
    }


    public function show($id)
    {
        $preventiva = $this->preventivaRepository->getById($id);
        return view('pages.ativos.veiculos.preventivas.show', compact('preventiva'));
    }

    public function edit($id)
    {
        $preventiva = $this->preventivaRepository->getById($id);
        return view('pages.ativos.veiculos.preventivas.edit', compact('preventiva'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nome_preventiva' => 'required|string|max:255',
            'nome_servico' => 'required|array',
            'tipo_veiculo' => 'required|string|max:255',
            'situacao' => 'required|array',
            'periodo' => 'required|array',
            'tipo' => 'required|array'
        ]);
    
        $data['nome_servico'] = json_encode($data['nome_servico']);
        $data['situacao'] = json_encode($data['situacao']);
        $data['periodo'] = json_encode($data['periodo']);
        $data['tipo'] = json_encode($data['tipo']);
    
        $this->preventivaRepository->update($id, $data);
    
        return redirect()->route('veiculo.manut_preventiva.index')->with('success', 'Preventiva atualizada com sucesso!');
    }
    
    public function destroy($id)
    {
        $this->preventivaRepository->delete($id);
        return redirect()->route('veiculo.manut_preventiva.index')->with('success', 'Preventiva excluída com sucesso!');
    }
}
