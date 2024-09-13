<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AtivoConfiguracao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Traits\Configuracao;

class AtivoConfiguracaoController extends Controller
{

    use Configuracao;

    public function index(Request $request)
    {

        $categoria = $request->categoria;

        
        if ($categoria) {

            $lista = AtivoConfiguracao::select("ativos_configuracoes.*", "m2.titulo as vinculo")
                ->join("ativos_configuracoes as m2", "m2.id", "=", "ativos_configuracoes.id_relacionamento", "left")
                ->where('ativos_configuracoes.titulo', 'LIKE', "%$categoria%")
                ->orderBy('id', 'desc')
                ->paginate(8);

        } else {

            $lista = AtivoConfiguracao::select("ativos_configuracoes.*", "m2.titulo as vinculo")
                ->join("ativos_configuracoes as m2", "m2.id", "=", "ativos_configuracoes.id_relacionamento", "left")
                ->orderBy('id', 'desc')
                ->paginate(8);
        }

        return view('pages.ativos.configuracoes.index', compact('lista'));
    }

    public function create()
    {
        $ativo_configuracoes = AtivoConfiguracao::get_ativo_configuracoes();
        return view('pages.ativos.configuracoes.form', compact('ativo_configuracoes'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'id_relacionamento' => 'required',
                'titulo' => 'required',
                'status' => 'required'
            ],
            [
                'id_relacionamento.required' => 'É necessário selecionar o Relacionamento desta Categoria',
                'titulo.required' => 'Preencha o Título da sua Sub Categoria',
                'status.required' => 'Selecione o Status'
            ]
        );

        $configuracao = new AtivoConfiguracao();
        $configuracao->id_relacionamento = $request->id_relacionamento;
        $configuracao->titulo = $request->titulo;
        $configuracao->status = $request->status;
        $configuracao->save();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | ADD RELACIONAMENTO : ' . $configuracao->titulo);

        return redirect()->route('ativo.configuracao')->with('success', 'Um registro foi adicionado com sucesso!');
    }

    public function edit($id)
    {
        $store = AtivoConfiguracao::find($id);
        $ativo_configuracoes = AtivoConfiguracao::get_ativo_configuracoes();

        if (!$id or !$store) {
            return redirect()->route('ativo.configuracao')->with('fail', 'Esse registro não foi encontrado.');
        }

        return view('pages.ativos.configuracoes.form', compact('store', 'ativo_configuracoes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'id_relacionamento' => 'required',
                'titulo' => 'required',
                'status' => 'required'
            ],
            [
                'id_relacionamento.required' => 'É necessário selecionar o Relacionamento desta Categoria',
                'titulo.required' => 'Preencha o Título da sua Categoria',
                'status.required' => 'Selecione o Status'
            ]
        );

        $configuracao = AtivoConfiguracao::find($id);
        $configuracao->id_relacionamento = $request->id_relacionamento;
        $configuracao->titulo = $request->titulo;
        $configuracao->status = $request->status;
        $configuracao->save();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | EDIT RELACIONAMENTO : ' . $configuracao->titulo);

        return redirect()->route('ativo.configuracao.editar', $id)->with('success', 'Um registro foi modificado com sucesso!');
    }
}
