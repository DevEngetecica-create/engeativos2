<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracaoModulo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Traits\Configuracao;

class ConfiguracaoModuloController extends Controller
{

    use Configuracao;

    public function index(Request $request)
    {

        // Obtém o termo de pesquisa da query string
        $modulo = $request->modulo;

       //dd($request->modulo);

        // Se um termo de pesquisa foi fornecido, filtra as categorias pelo nome
        if ($modulo) {
            //dd($modulo);
            $lista = ConfiguracaoModulo::select("modulos.*", "m2.titulo as vinculo")
                ->join("modulos as m2", "m2.id", "=", "modulos.id_modulo", "left")
                ->where('modulos.titulo', 'LIKE', "%$modulo%")
                ->orderBy('id', 'desc')
                ->whereNull('m2.deleted_at')
                ->paginate(9);
        } else {
            // Se nenhum termo de pesquisa foi fornecido, obtém todas as categorias
            //dd($modulo);
            $lista = ConfiguracaoModulo::select("modulos.*", "m2.titulo as vinculo")
                ->join("modulos as m2", "m2.id", "=", "modulos.id_modulo", "left")
                ->whereNull('modulos.deleted_at')
                ->orderBy('id', 'desc')
                ->paginate(8);
        }

        return view('pages.configuracoes.modulo.index', compact('lista'));
    }

    public function create()
    {
        $modulos = ConfiguracaoModulo::get_modulos();

        $acoes_permitidas = Configuracao::acoes_permitidas();

        return view('pages.configuracoes.modulo.form', compact('modulos', 'acoes_permitidas'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'titulo' => 'required|min:5',
                'url_amigavel' => 'required|unique:modulos,url_amigavel',
                'posicao' => 'required',
                'acoes_permitidas' => 'required'
            ],
            [
                'titulo.required' => 'É necessário preencher o título do Módulo',
                'url_amigavel.required' => 'É necessário configurar uma URL Amigável para acesso ao Módulo',
                'url_amigavel.unique' => 'Esta URL já consta em nosso banco de dados. Use outra!',
                'posicao.required' => 'É necessário posicionar o Módulo de acordo com a sua exibição',
                'acoes_permitidas.required' => 'Selecione ao menos uma ação que será permitida dentro deste Módulo'
            ]
        );

        $modulo = new ConfiguracaoModulo();
        $modulo->id_modulo = ($request->id_modulo) ?? 0;
        $modulo->titulo = $request->titulo;
        $modulo->url_amigavel = $request->url_amigavel;
        $modulo->posicao = $request->posicao;
        $modulo->icone = $request->icone;
        $modulo->tipo_de_acao = implode(",", $request->acoes_permitidas);
        $modulo->save();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | STORE MÓDULO: ' . $modulo->titulo .' | URL: ' .  $modulo->url_amigavel);

        return redirect()->route('modulo')->with('success', 'Um registro foi adicionado com sucesso!');
    }

    public function edit($id)
    {
        $store = ConfiguracaoModulo::find($id);

        $modulos = ConfiguracaoModulo::all();

        $acoes_permitidas = Configuracao::acoes_permitidas();

        if (!$id or !$store) {
            return redirect()->route('modulo')->with('fail', 'Esse registro não foi encontrado.');
        }

        return view('pages.configuracoes.modulo.form', compact('store', 'modulos', 'acoes_permitidas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'titulo' => 'required|min:5',
                'url_amigavel' => 'required|unique:modulos,url_amigavel,'.$id,
                'posicao' => 'required',
                'acoes_permitidas' => 'required'
            ],
            [
                'titulo.required' => 'É necessário preencher o título do Módulo',
                'url_amigavel.required' => 'É necessário configurar uma URL Amigável para acesso ao Módulo',
                'url_amigavel.unique' => 'Esta URL já consta em nosso banco de dados. Use outra!',
                'posicao.required' => 'É necessário posicionar o Módulo de acordo com a sua exibição',
                'acoes_permitidas.required' => 'Selecione ao menos uma ação que será permitida dentro deste Módulo'
            ]
        );

        $modulo = ConfiguracaoModulo::find($id);
        $modulo->id_modulo = ($request->id_modulo) ?? 0;
        $modulo->titulo = $request->titulo;
        $modulo->url_amigavel = $request->url_amigavel;
        $modulo->icone = $request->icone;
        $modulo->posicao = $request->posicao;
        $modulo->tipo_de_acao = implode(",", $request->acoes_permitidas);
        $modulo->save();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT MODULO: ' . $modulo->titulo .' | URL: ' .  $modulo->url_amigavel);

        return redirect()->route('modulo.editar', $id)->with('success', 'Registro modificado com sucesso.');
    }

}
