<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Models\VeiculoTacografo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Response;

class  VeiculoTacografoController extends Controller
{

    public function index(Veiculo $veiculo, Request $request )
    {
       // dd($request->ajax());
       
        if ($request->ajax()) 
        {
            
            $tacografo = VeiculoTacografo::with('veiculo')->where('veiculo_id', $veiculo->id)->orderByDesc('id');

            return DataTables::of($tacografo)->make(true);            
           
        }

        return view('pages.ativos.veiculos.tacografo.index', compact('veiculo'));

    }

   
    public function create(Veiculo $veiculo)
    {
        return view('pages.ativos.veiculos.tacografo.create', compact('veiculo'));
    }

    public function store(Request $request)
    {

       // dd($request->data_da_emissao);

        $data = $request->validate([
            'veiculo_id' => 'required',
            'descricao' => 'required',
            'data_da_emissao' => 'required',
            'data_do_vencimento' => 'required',
            'observação'
        ]);

        $all = VeiculoTacografo::create($data);
        
        if($all){

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | STORE TACOGRAFO: ' . $request->veiculo_id);

            return redirect()->route('ativo/veiculo/tacografo/index', $request->veiculo_id)->with('success', 'Registro salvo com sucesso');
        } else {
            return redirect()->route('ativo/veiculo/tacografo/index', $request->veiculo_id)->with('fail', 'Erro ao salvar registro');
        }

    }

    public function edit($id)
    {
        $tacografo = VeiculoTacografo::with('veiculo')->where('id', $id)->first();        

        return view('pages.ativos.veiculos.tacografo.edit', compact('tacografo'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        if (! $save =  VeiculoTacografo::find($id)) {
            return redirect()->route('ativo.veiculo.tacografo.editar', $id)->with('fail', 'Problemas para localizar o registro.');
        }

      
        $data = $request->all();
        $save->update($data);

        if($save) {
            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | EDIT ACESSORIOS: ' . $save->id);

            return redirect()->route('ativo/veiculo/tacografo/index', $request->veiculo_id)->with('success', 'Registro salvo com sucesso.');
        } else {
            return redirect()->route('ativo.veiculo.tacografo.editar', $id)->with('fail', 'Erro ao salvar registro.');
        }

    }

    public function delete($id)
    {
        $quilometragem = VeiculoTacografo::findOrFail($id);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE ACESSORIOS: ' . $quilometragem->id);

        if($quilometragem->delete()) {
            return redirect()->back()->with('success', 'Registro excluído com sucesso.');
        } else {
            return redirect()->back()->with('fail', 'Erro ao excluir registro.');
        }
    }
}
