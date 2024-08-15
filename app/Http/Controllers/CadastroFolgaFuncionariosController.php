<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CadastroFolgaFuncionarios;
use App\Models\CadastroObra;
use App\Models\CadastroFuncionario;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use DB;

use App\Traits\Configuracao;

class CadastroFolgaFuncionariosController extends Controller
{
    use Configuracao;

    public function index()
    {


        $listPage = request('lista') ?? 10;
        
        $folgaFuncionarios = CadastroFolgaFuncionarios::with('obra','funcionarios')
                        ->when(request('search') != null, function 
                        ($query) {
                            return  $query->where('id_funcionario', 'like', '%' . request('search') . '%');
                        })->paginate($listPage);

        return view('pages.cadastros.funcionario.folgas.partials.list', compact('folgaFuncionarios'));
        
    }

    public function create(Request $request)
    {
        if (Session::get('obra')['id'] == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
        } else {
            $obras = CadastroObra::where('id', Session::get('obra')['id'])->orderByDesc('id')->get();
        }
        
        
         if (Session::get('obra')['id'] == null) {
            $funcionarios = CadastroFuncionario::all();
        } else {
            $funcionarios = CadastroFuncionario::where('id_obra', Session::get('obra')['id'])->get();
        }
        
        return view('pages.cadastros.funcionario.folgas.form', compact('obras','funcionarios'));
        
    }

    public function store(Request $request)
    {
            $creatFolgaFuncionarios = new CadastroFolgaFuncionarios([
                                        'id_funcionario'  => $request->id_funcionario,
                                        'id_obra'  => $request->id_obra,
                                        'data_inicio' => $request->data_inicio,
                                        'data_fim'  => $request->data_fim,
            ]);
        
        if($creatFolgaFuncionarios->save()){

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | CADASTRO FOLGA FUNCIONARIO: ' . $request->id_funcionario);

            return redirect('admin/cadastro/funcionario/folga')->with('success', 'Registro salvo com sucesso');
        } else {
            return redirect()->route('cadastro.funcionario.folga.adicionar')->with('fail', 'Erro ao salvar registro');
        }
    }

    public function edit($id)
    {
        
        $editFolgaFuncionarios = CadastroFolgaFuncionarios::find($id);
        
       if (Session::get('obra')['id'] == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
        } else {
            $obras = CadastroObra::where('id', Session::get('obra')['id'])->orderByDesc('id')->get();
        }
        
        
         if (Session::get('obra')['id'] == null) {
            $funcionarios = CadastroFuncionario::all();
        } else {
            $funcionarios = CadastroFuncionario::where('id_obra', Session::get('obra')['id'])->get();
        }
    

        if(!$id or !$editFolgaFuncionarios) {
            return redirect('admin/ativo/veiculo/categoria')->with('fail', 'Esse registro não foi encontrado.');
        }

          return view('pages.cadastros.funcionario.folgas.form', compact('editFolgaFuncionarios', 'obras', 'funcionarios'));
    }

    public function update(Request $request, $id)
    {
        
        $updateFolgaFuncionarios = CadastroFolgaFuncionarios::find($id);
        
        $updateFolgaFuncionarios->update([
                                'id_funcionario'  => $request->id_funcionario,
                                'id_obra'  => $request->id_obra,
                                'data_inicio' => $request->data_inicio,
                                'data_fim'  => $request->data_fim,
            ]);
        
        
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT FOLGA FUNCIONARIO: ' . $request->id_funcionario);

         return redirect('admin/cadastro/funcionario/folga')->with('success', 'Registro atualizado com sucesso!');
        
    }

    public function destroy($id)
    {
        
        $destroyCategoria = CadastroFolgaFuncionarios::find($id);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE CATEGORIA: ' . $destroyCategoria->nomeCategoria);

        if ($destroyCategoria->delete()) {
            
            return redirect('admin/cadastro/funcionario/folga')->with('success', 'Registro excluído com sucesso.');
            
        } else {
            return redirect('admin/cadastro/funcionario/folga')->with('fail', 'Um erro ocorreu na tentativa de exclusão');
            
        }
        
    }

   

   

}
