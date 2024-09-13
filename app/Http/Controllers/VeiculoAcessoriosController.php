<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Models\VeiculoAcessorios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class  VeiculoAcessoriosController extends Controller
{

    public function index(Veiculo $veiculo, Request $request )
    {
       
        if ($request->ajax()) 
        {
            
            $acessorios=VeiculoAcessorios::with('veiculo')->where('veiculo_id', $veiculo->id)->orderByDesc('id');

            return DataTables::of($acessorios)->make(true);            
           
        }

        return view('pages.ativos.veiculos.acessorios.index', compact('veiculo'));

    }

   
    public function create(Veiculo $veiculo)
    {
        return view('pages.ativos.veiculos.acessorios.create', compact('veiculo'));
    }

    public function store(Request $request)
    {

        $data = $request->all();
        $save = VeiculoAcessorios::create($data);

        if($save){

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | STORE ACESSORIOS: ' . $request->veiculo_id);

            return redirect()->route('ativo/veiculo/acessorios/index', $request->veiculo_id)->with('success', 'Registro salvo com sucesso');
        } else {
            return redirect()->route('ativo/veiculo/acessorios/index', $request->veiculo_id)->with('fail', 'Erro ao salvar registro');
        }

    }

    public function edit($id)
    {
        $acessorios = VeiculoAcessorios::with('veiculo')->where('id', $id)->first();        

        return view('pages.ativos.veiculos.acessorios.edit', compact('acessorios'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        if (! $save =  VeiculoAcessorios::find($id)) {
            return redirect()->route('ativo.veiculo.acessorios.editar', $id)->with('fail', 'Problemas para localizar o registro.');
        }

      
        $data = $request->all();
        $save->update($data);

        if($save) {
            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | EDIT ACESSORIOS: ' . $save->id);

            return redirect()->route('ativo/veiculo/acessorios/index', $request->veiculo_id)->with('success', 'Registro salvo com sucesso.');
        } else {
            return redirect()->route('ativo.veiculo.acessorios.editar', $id)->with('fail', 'Erro ao salvar registro.');
        }

    }

    public function delete($id)
    {
        $quilometragem = VeiculoAcessorios::findOrFail($id);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE ACESSORIOS: ' . $quilometragem->id);

        if($quilometragem->delete()) {
            return redirect()->back()->with('success', 'Registro excluído com sucesso.');
        } else {
            return redirect()->back()->with('fail', 'Erro ao excluir registro.');
        }
    }
}
