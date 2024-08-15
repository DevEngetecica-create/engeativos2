<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstoqueMarcas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EstoqueMarcasController extends Controller
{
   
    public function index()
    {

        $listPage = request('lista') ?? 10;
        
        $marcas = EstoqueMarcas::when(request('search') != null, function 
        ($query) {
            return  $query->where('name', 'like', '%' . request('search') . '%');
        })
        ->orderBy('id', 'desc')
        ->paginate($listPage);

        return view('pages.estoque.marcas.index', compact('marcas'));
        
    }

    public function create()
    {
        
        return view('pages.estoque.marcas.create');
        
    }

    public function store(Request $request)
    {
       $marca = new EstoqueMarcas([
                "name" => $request->name               
            ]);
        
        if($marca->save()){

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | CADASTRO DA MARCA: ' . $request->name);

            return redirect()->route('ativo.estoque.marcas.index')->with('success', 'Registro salvo com sucesso');

        } else {
            return redirect()->route('ativo.estoque.marcas.create')->with('fail', 'Erro ao salvar registro');
        }
    }

    public function edit($id)
    {
        
        $editMarca = EstoqueMarcas::find($id);


        if(!$id or !$editMarca) {

            return redirect('ativo.estoque.marcas.index')->with('fail', 'Esse registro não foi encontrado.');
        }

          return view('pages.estoque.marcas.edit', compact('editMarca'));
    }

    public function update(Request $request, $id)
    {
        
        $updateMarca = EstoqueMarcas::find($id);
        
        $updateMarca->update([
                                'name' => $request->name                                
            ]);
        
        
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT MARCA: ' . $request->name);

         return redirect()->route('ativo.estoque.marcas.index')->with('success', 'Registro atualizado com sucesso!');
        
    }

    public function destroy($id)
    {
        
        $destroyMarca = EstoqueMarcas::find($id);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE MARCA: ' . $destroyMarca->name);

        if ($destroyMarca->delete()) {
            
            return redirect()->route('ativo.estoque.marcas.index')->with('success', 'Registro excluído com sucesso.');
            
        } else {

            redirect()->route('ativo.estoque.marcas.index')->with('fail', 'Um erro ocorreu na tentativa de exclusão');
            
        }
        
    }

   

   

}
