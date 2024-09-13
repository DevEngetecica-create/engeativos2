<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstoqueCategoria;
use App\Models\EstoqueSubCategoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;

use App\Traits\Configuracao;

class EstoqueSubCategoriaController extends Controller
{
    use Configuracao;

    public function index()
    {

        $listPage = request('lista') ?? 10;

        $subcategorias = EstoqueSubCategoria::when(request('search') != null, function ($query) {
            return  $query->where('name', 'like', '%' . request('search') . '%');
        })
        ->orderBy('id', 'desc')
        ->paginate($listPage);

        return view('pages.estoque.subcategorias.index', compact('subcategorias'));
    }

    public function create(Request $request)
    {
        $categorias = EstoqueCategoria::all();

        return view('pages.estoque.subcategorias.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $categoria = new EstoqueSubCategoria([
            "name" => $request->name,
            "color" => $request->color,
            "category_id" => $request->category_id
        ]);

        if ($categoria->save()) {

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | CADASTRO DA SUBCATEGORIA: ' . $request->name);

            return redirect('ativo.estoque.subcategorias.index')->with('success', 'Registro salvo com sucesso');
        } else {
            return redirect()->route('ativo.estoque.subcategorias.create')->with('fail', 'Erro ao salvar registro');
        }
    }

    public function edit($id)
    {

        $editSubCategoria = EstoqueSubCategoria::find($id);

        $categorias = EstoqueCategoria::all();


        if (!$id or !$editSubCategoria) {

            return redirect('pages.estoque.subcategorias.index')->with('fail', 'Esse registro não foi encontrado.');
        }

        return view('pages.estoque.subcategorias.edit', compact('editSubCategoria', 'categorias'));
    }

    public function update(Request $request, $id)
    {

        $updateCategoria = EstoqueSubCategoria::find($id);

        $updateCategoria->update([
            'name' => $request->name,
            'color' => $request->color,
            "category_id" => $request->category_id
        ]);


        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | EDIT CATEGORIA: ' . $request->name);

        return redirect()->route('ativo.estoque.subcategorias.index')->with('success', 'Registro atualizado com sucesso!');
    }

    public function destroy($id)
    {

        $destroySubCategoria = EstoqueSubCategoria::find($id);       

        if ($id) {

            $destroySubCategoria->delete();

            $userLog = Auth::user()->email;

            Log::channel('main')->info($userLog . ' | DELETE SUBCATEGORIA: ' . $destroySubCategoria->name);

            return redirect()->route('ativo.estoque.subcategorias.index')->with('success', 'Registro excluído com sucesso.');

        } else {

            return redirect()->route('ativo.estoque.subcategorias.index')->with('fail', 'Um erro ocorreu na tentativa de exclusão');
        }
    }
}
