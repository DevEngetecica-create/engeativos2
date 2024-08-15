<?php

namespace App\Http\Controllers;

use App\Models\CadastroFuncionario;
use App\Models\FolgaFuncionario;
use App\Models\FuncaoFuncionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;


class  FolgaFuncionarioController extends Controller
{

    public function index(Request $request)
    {
        $folgas = FolgaFuncionario::with('funcionarios');

        return view('pages.cadastros.funcionario.folgas.index', compact('folgas'));

    }

   
    public function create(CadastroFuncionario $cadastroFuncionario)
    {
        return view('pages.cadastros.funcionario.folga.create', compact('cadastroFuncionario'));
    }

    public function store(Request $request)
    {

        $data = $request->all();
        $save = FolgaFuncionario::create($data);

        if($save){

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | STORE ACESSORIOS: ' . $request->veiculo_id);

            return redirect()->route('cadastro.funcionario.folgas.index')->with('success', 'Registro salvo com sucesso');
        } else {
            return redirect()->route('cadastro.funcionario.folgas.index')->with('fail', 'Erro ao salvar registro');
        }

    }

    public function edit($id)
    {
        $acessorios = FolgaFuncionario::with('veiculo')->where('id', $id)->first();        

        return view('pages.cadastros.funcionario.folga.edit', compact('acessorios'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        if (! $save =  FolgaFuncionario::find($id)) {
            return redirect()->route('funcionario.folga.editar', $id)->with('fail', 'Problemas para localizar o registro.');
        }

      
        $data = $request->all();
        $save->update($data);

        if($save) {
            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | EDIT ACESSORIOS: ' . $save->id);

            return redirect()->route('cadastro.funcionario.folgas.index', $request->veiculo_id)->with('success', 'Registro salvo com sucesso.');
        } else {
            return redirect()->route('cadastro.funcionario.folgas.index', $id)->with('fail', 'Erro ao salvar registro.');
        }

    }

    public function delete($id)
    {
        $quilometragem = FolgaFuncionario::findOrFail($id);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE ACESSORIOS: ' . $quilometragem->id);

        if($quilometragem->delete()) {
            return redirect()->back()->with('success', 'Registro excluído com sucesso.');
        } else {
            return redirect()->back()->with('fail', 'Erro ao excluir registro.');
        }
    }
}
