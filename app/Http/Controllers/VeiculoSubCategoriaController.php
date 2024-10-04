<?php

namespace App\Http\Controllers;

use App\Models\VeiculoCategoria;
use Illuminate\Http\Request;
use App\Models\VeiculoSubCategoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;

use App\Traits\Configuracao;

class VeiculoSubCategoriaController extends Controller
{
    use Configuracao;

    public function index()
    {


        $listPage = request('lista') ?? 10;
        
        $veiculoSubCategoria = VeiculoSubCategoria::when(request('search') != null, function 
        ($query) {
            return  $query->where('nomeSubCategoria', 'like', '%' . request('search') . '%');
        })
        ->with('categorias')
        ->paginate($listPage);

        return view('pages.ativos.veiculos.subCategorias.partials.list', compact('veiculoSubCategoria'));
        
    }

    public function create(Request $request)
    {
        $categorias = VeiculoCategoria::all();
        
        return view('pages.ativos.veiculos.subCategorias.form', compact('categorias'));
        
    }

    public function store(Request $request)
    {
       $subCategoria = new VeiculoSubCategoria([
                "nomeSubCategoria" => $request->nomeSubCategoria,
                "statusSubCategoria" => $request->statusSubCategoria
            ]);
        
        if($subCategoria->save()){

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | CADASTRO DA CATEGORIA: ' . $request->nomeSubCategoria);

            return redirect('admin/ativo/veiculo/subCategoria')->with('success', 'Registro salvo com sucesso');
        } else {
            return redirect()->route('ativo.veiculo.subCategoria.adicionar')->with('fail', 'Erro ao salvar registro');
        }
    }

    public function edit($id)
    {
        
        $editSubCategoria = VeiculoSubCategoria::with('categorias')->find($id);
        $categorias = VeiculoCategoria::all();


        if(!$id) {
            
            return redirect('admin/ativo/veiculo/subCategoria')->with('fail', 'Esse registro não foi encontrado.');
        }

          return view('pages.ativos.veiculos.subCategorias.form', compact('editSubCategoria', 'categorias'));
          
    }

    public function update(Request $request, $id)
    {
        
        $updateSubCategoria = VeiculoSubCategoria::find($id);
        
        $updateSubCategoria->update([
                                'nomeSubCategoria' => $request->nomeSubCategoria,
                                'statusSubCategoria' => $request->statusSubCategoria
            ]);
        
        
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT CATEGORIA: ' . $request->nomeSubCategoria);

         return redirect('admin/ativo/veiculo/subCategoria')->with('success', 'Registro atualizado com sucesso!');
        
    }

    public function destroy($id)
    {
        
        $destroyCategoria = VeiculoSubCategoria::find($id);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE CATEGORIA: ' . $destroyCategoria->nomeCategoria);

        if ($destroyCategoria->delete()) {
            
            return redirect('admin/ativo/veiculo/subCategoria')->with('success', 'Registro excluído com sucesso.');
            
        } else {
            return redirect('admin/ativo/veiculo/subCategoria')->with('fail', 'Um erro ocorreu na tentativa de exclusão');
            
        }
        
    }

   

   

}
