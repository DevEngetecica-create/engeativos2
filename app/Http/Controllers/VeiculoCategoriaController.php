<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VeiculoCategoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;

use App\Traits\Configuracao;

class VeiculoCategoriaController extends Controller
{
    use Configuracao;

    public function index()
    {


        $listPage = request('lista') ?? 10;
        
        $veiculoCategoria = VeiculoCategoria::when(request('search') != null, function 
        ($query) {
            return  $query->where('nomeCategoria', 'like', '%' . request('search') . '%');
        })->paginate($listPage);

        return view('pages.ativos.veiculos.categorias.partials.list', compact('veiculoCategoria'));
        
    }

    public function create(Request $request)
    {
        
        return view('pages.ativos.veiculos.categorias.form');
        
    }

    public function store(Request $request)
    {
       $categoria = new VeiculoCategoria([
                "nomeCategoria" => $request->nomeCategoria,
                "statusCategoria" => $request->statusCategoria
            ]);
        
        if($categoria->save()){

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | CADASTRO DA CATEGORIA: ' . $request->nomeCategoria);

            return redirect('admin/ativo/veiculo/categoria')->with('success', 'Registro salvo com sucesso');
        } else {
            return redirect()->route('cadastro.veiculo.categoria.adicionar')->with('fail', 'Erro ao salvar registro');
        }
    }

    public function edit($id)
    {
        
        $editCategoria = VeiculoCategoria::find($id);


        if(!$id or !$editCategoria) {
            return redirect('admin/ativo/veiculo/categoria')->with('fail', 'Esse registro não foi encontrado.');
        }

          return view('pages.ativos.veiculos.categorias.form', compact('editCategoria'));
    }

    public function update(Request $request, $id)
    {
        
        $updateCategoria = VeiculoCategoria::find($id);
        
        $updateCategoria->update([
                                'nomeCategoria' => $request->nomeCategoria,
                                'statusCategoria' => $request->statusCategoria
            ]);
        
        
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT CATEGORIA: ' . $request->nomeCategoria);

         return redirect('admin/ativo/veiculo/categoria')->with('success', 'Registro atualizado com sucesso!');
        
    }

    public function destroy($id)
    {
        
        $destroyCategoria = VeiculoCategoria::find($id);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE CATEGORIA: ' . $destroyCategoria->nomeCategoria);

        if ($destroyCategoria->delete()) {
            
            return redirect('admin/ativo/veiculo/categoria')->with('success', 'Registro excluído com sucesso.');
            
        } else {
            return redirect('admin/ativo/veiculo/categoria')->with('fail', 'Um erro ocorreu na tentativa de exclusão');
            
        }
        
    }

   

   

}
