<?php

namespace App\Http\Controllers;

use App\Models\VeiculoDepreciacao;
use Illuminate\Http\Request;
use App\Models\Veiculo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\returnCallback;

class VeiculoDepreciacaoController extends Controller
{
    public function index(Veiculo $veiculo)
    {
        $depreciacoes = VeiculoDepreciacao::where('veiculo_id', $veiculo->id)->orderByDesc('id')->get();

        return view('pages.ativos.veiculos.depreciacao.index', compact('veiculo', 'depreciacoes'));
    }

    public function create(Veiculo $veiculo)
    {
        return view('pages.ativos.veiculos.depreciacao.create', compact('veiculo'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $data = $request->all();
        $data['valor_atual'] = str_replace('R$ ', '', $request->valor_atual);
        $save = VeiculoDepreciacao::create($data);

        if($save){
            return redirect()->route('ativo.veiculo.depreciacao.index', $request->veiculo_id)->with('success', 'Registro salvo com sucesso');
        } else {
            return redirect()->route('ativo.veiculo.depreciacao.index', $request->veiculo_id)->with('fail', 'Erro ao salvar registro');
        }
    }

    public function edit($id)
    {
        $depreciacao = VeiculoDepreciacao::with('veiculo')->where('id', $id)->first();

        return view('pages.ativos.veiculos.depreciacao.edit', compact('depreciacao'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        if (! $depreciacao = VeiculoDepreciacao::find($id)) {
            return redirect()->route('ativo.veiculo.depreciacao.editar', $id)->with('fail', 'Problemas para localizar o registro.');
        }

        $data = $request->all();
        $data['valor_atual'] = str_replace('R$ ', '', $request->valor_atual);
        $depreciacao->update($data);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT DEPRECIACAO: ' . $id);

        return redirect()->route('ativo.veiculo.depreciacao.editar', $id)->with('success', 'O registro foi alterado com sucesso');
    }

    public function delete($id)
    {
        $veiculo = VeiculoDepreciacao::findOrFail($id);

        if($veiculo->delete()){

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | DELETE DEPRECIACAO: ' . $veiculo->id);

            return redirect()->route('ativo.veiculo.depreciacao.index', $veiculo->veiculo_id)->with('success', 'Registro excluído com sucesso');
        } else {
            return redirect()->route('ativo.veiculo.depreciacao.index', $veiculo->veiculo_id)->with('fail', 'Erro ao excluir registro');
        }

    }
}
