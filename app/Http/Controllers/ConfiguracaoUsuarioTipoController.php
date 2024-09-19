<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracaoUsuarioNiveis as UsuarioTipo;
use App\Models\ConfiguracaoModulo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConfiguracaoUsuarioTipoController extends Controller
{

    public function index(Request $request)
    {

        // Obt«±m o termo de pesquisa da query string
        $titulo = $request->titulo;

        // Se um termo de pesquisa foi fornecido, filtra as categorias pelo nome
        if ($titulo) {
            
            $lista = UsuarioTipo::where('titulo', 'LIKE', "%$titulo%")
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else {
            // Se nenhum termo de pesquisa foi fornecido, obt«±m todas as categorias
            
            $lista = UsuarioTipo::orderBy('id', 'desc')
                ->paginate(10);
        }


        $permite_excluir = 0;     

        return view('pages.configuracoes.usuario_tipo.index', compact('lista', 'permite_excluir'));
    }


    public function create()
    {
        $modulos = ConfiguracaoModulo::get_modulos();

        return view('pages.configuracoes.usuario_tipo.form', compact('modulos'));
    }

    public function store(Request $request)
    {
        $tipo = new UsuarioTipo();
        $tipo->titulo = $request->nome;
        $tipo->permissoes = json_encode(($request->permission) ?? []);
        $tipo->save();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | ADD TIPO USUARIO: ' . $tipo->titulo);

        return redirect()->route('usuario_tipo')->with('success', 'Um registro foi adicionado com sucesso!');
    }

    public function edit($id=null)
    {
        $modulos = ConfiguracaoModulo::get_modulos();

        $store = UsuarioTipo::find($id);

        if (!$id or !$store) {
            return redirect()->route('usuario_tipo')->with('fail', 'Registro nÃ£o encontrado!');
        }

        return view('pages.configuracoes.usuario_tipo.form', compact('store', 'modulos'));
    }

    public function update(Request $request, $id)
    {
        $tipo = UsuarioTipo::find($request->id);
        $tipo->titulo = $request->nome;
        $tipo->permissoes = json_encode(($request->permission) ?? []);
        $tipo->save();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT TIPO USUARIO: ' . $tipo->titulo);

        return redirect()->route('usuario_tipo')->with('success', 'Registro modificado com sucesso.');
    }

}
