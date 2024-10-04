<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstoqueCategoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;

use App\Traits\Configuracao;

class EstoqueCategoriaController extends Controller
{
    use Configuracao;

    public function index()
    {


        $listPage = request('lista') ?? 10;
        
        $categorias = EstoqueCategoria::when(request('search') != null, function 
        ($query) {
            return  $query->where('name', 'like', '%' . request('search') . '%');
        })
        ->orderBy('id', 'desc')
        ->paginate($listPage);

        return view('pages.estoque.categorias.index', compact('categorias'));
        
    }

    public function create()
    {
        
        return view('pages.estoque.categorias.create');
        
    }

    public function store(Request $request)
    {
       $categoria = new EstoqueCategoria([
                "name" => $request->name,
                "color" => $request->color
            ]);
        
        if($categoria->save()){

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | CADASTRO DA CATEGORIA: ' . $request->name);

            return redirect()->route('ativo.estoque.categorias.index')->with('success', 'Registro salvo com sucesso');

        } else {
            return redirect()->route('ativo.estoque.categorias.create')->with('fail', 'Erro ao salvar registro');
        }
    }

    public function edit($id)
    {
        
        $editCategoria = EstoqueCategoria::find($id);


        if(!$id or !$editCategoria) {

            return redirect('ativo.estoque.categorias.index')->with('fail', 'Esse registro não foi encontrado.');
        }

          return view('pages.estoque.categorias.edit', compact('editCategoria'));
    }

    public function update(Request $request, $id)
    {
        
        $updateCategoria = EstoqueCategoria::find($id);
        
        $updateCategoria->update([
                                'name' => $request->name,
                                'color' => $request->color
            ]);
        
        
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT CATEGORIA: ' . $request->name);

        return redirect()->route('ativo.estoque.categorias.index')->with('success', 'Registro atualizado com sucesso!');
        
    }

    public function destroy($id)
    {
        
        $destroyCategoria = EstoqueCategoria::find($id);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE CATEGORIA: ' . $destroyCategoria->name);

        if ($destroyCategoria->delete()) {
            
            return redirect()->route('ativo.estoque.categorias.index')->with('success', 'Registro excluído com sucesso.');
            
        } else {

            return redirect()->route('ativo.estoque.categorias.index')->with('fail', 'Um erro ocorreu na tentativa de exclusão');
            
        }
        
    }

   

   

}
