<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Veiculo;
use App\Models\VeiculoSeguro;
use App\Models\Anexo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VeiculoSeguroController extends Controller
{

    public function index(Veiculo $veiculo)
    {
        $seguros = VeiculoSeguro::where('veiculo_id', $veiculo->id)->orderByDesc('id')->get();

        return view('pages.ativos.veiculos.seguro.index', compact('veiculo', 'seguros'));
    }

    public function create(Veiculo $veiculo)
    {
        return view('pages.ativos.veiculos.seguro.create', compact('veiculo'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['valor'] = str_replace('R$ ', '', $request->valor);
        $save = VeiculoSeguro::create($data);
        
       // dd($save->nome_seguradora);

        if($save) {
            return redirect()->route('ativo.veiculo.seguro.index', $save->veiculo_id)->with('success', 'Registro cadastrado com sucesso');
        } else {
            return redirect()->route('ativo.veiculo.seguro.index', $save->veiculo_id)->with('fail', 'Problema para cadastrar o registro');
        }

    }

    public function edit($id)
    {
        $seguro = VeiculoSeguro::with('veiculo')->where('id', $id)->first();

        return view('pages.ativos.veiculos.seguro.edit', compact('seguro'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        if (! $seguro = VeiculoSeguro::find($id)) {
            return redirect()->route('ativo.veiculo.seguro.editar', $id)->with('fail', 'Problemas para localizar o registro.');
        }

        $data = $request->all();
        $data['valor'] = str_replace('R$ ', '', $request->valor);
        $seguro->update($data);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT DEPRECIACAO: ' . $id);

        return redirect()->route('ativo.veiculo.seguro.index', $seguro->veiculo_id)->with('success', 'O registro foi alterado com sucesso');
    }

    public function delete($id)
    {
        $veiculo = VeiculoSeguro::findOrFail($id);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE SEGURO: ' . $veiculo->id);

        $veiculo->delete();

        return redirect()->back()->with('success', 'Registro excluído com sucesso');
    }
    
     public function anexo($id_ativo_externo = null)
    {
        if (!$id_ativo_externo) {
            return redirect()->route('ativo.veiculo.index')->with('fail', 'Problemas para localizar o arquivo.');
        }

        $anexo = Anexo::where('id_item', $id_ativo_externo)
        ->where('id_modulo', 30 )
        ->get();

        if (!$anexo) {
            return [];
        }

        if ($anexo) {
            return view('components.anexo.lista_anexo', compact('anexo'));
        }
    }

}
