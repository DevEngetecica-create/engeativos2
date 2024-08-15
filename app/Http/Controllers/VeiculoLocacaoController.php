<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Veiculo;
use App\Models\Preventiva;
use App\Models\Servico;

use App\Models\VeiculoLocacao;
use App\Models\CadastroFornecedor;
use App\Models\CadastroObra;
use App\Models\CadastroFuncionario;

use App\Models\Anexo;



class VeiculoLocacaoController extends Controller
{

    public function index(Veiculo $veiculo)
    {
        
        $obras = CadastroObra::orderByDesc('id')->get();
       
        
        $locacoes = VeiculoLocacao::with('veiculo', 'obra', 'manutencaos', 'obraDestino')
                        ->orderBy('id')->get();
        return view('pages.ativos.veiculos.locacao.index', compact('veiculo','locacoes', 'obras'));
        
    }
    
    public function list(Request $request, Veiculo $veiculo)
    {
       
        $listPage = request('lista') ?? 7;
        
        $data_inicio = $request->data_inicio ?? null;
        
        $data_fim = $request->data_fim ?? null;
        
        $obraDestino = $request->id_obraDestino;
        
        $search = $request->search;

        
        if ($request->id_obraDestino != null OR $request->search != null) {
                $locacoes = VeiculoLocacao::where(function ($query) use ($obraDestino) {
                            $query->where('id_obraDestino', 'like', "%$obraDestino%");
                    })
                    
                            ->when($search, function ($query) use ($search) {
                                    $query->whereHas('veiculo', function ($query) use ($search) {
                                    $query->where('veiculo', 'like', "%$search%")
                                    ->orWhere('marca', 'LIKE', '%' . request('search') . '%')
                                    ->orWhere('modelo', 'LIKE', '%' . request('search') . '%')
                                    ->orWhere('codigo_da_maquina', 'LIKE', '%' . request('search') . '%')
                                    ->orWhere('tipo', 'LIKE', '%' . request('search') . '%')
                                    ->orWhere('placa', 'LIKE', '%' . request('search') . '%');
                                });
                            })                            
                            ->with('veiculo', 'obra', 'manutencaos', 'obraDestino')
                            
                ->get();
        }else{
            
            $locacoes = VeiculoLocacao::with('veiculo', 'obra', 'manutencaos', 'obraDestino')
                        ->orderBy('id')->get();
        }
        
       
        // Retorna as duas views
        return  view('pages.ativos.veiculos.locacao.partials.list', compact('veiculo', 'locacoes'));
        
    }

    public function create(Veiculo $veiculo, Request $request)
    {
        $obras = CadastroObra::select('id', 'codigo_obra', 'razao_social')->orderByDesc('id')->get();
        
        $funcionarios = CadastroFuncionario::orderByDesc('id')->get();
        
        $veiculos = Veiculo::orderByDesc('id')->get();
        
        $servicos = Servico::select('id', 'nomeServico')->get();
        
        return view('pages.ativos.veiculos.locacao.form', compact('veiculo', 'funcionarios', 'obras', 'veiculos'));
    }

    public function store(Request $request)
    {
        //dd($request->id_funcionario);
        $locacoesCreate = new VeiculoLocacao([
            "id_obra"           => $request->id_obra,
            "veiculo_id"        => $request->placa_modelo,
            "tipo_veiculo"      => $request->tipo_veiculo,
            "id_obraDestino"    => $request->id_obraDestino,
            "id_funcionario"    => $request->id_funcionario,
            "data_inicio"       => $request->data_inicio,
            "data_prevista"       => $request->data_prevista,
            "data_fim"          => $request->data_fim ?? null
          
        ]);
        
        $locacoesCreate->save();

        if($locacoesCreate->save()){

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | CADASTRO DA LOCAÇAO DO VEICULO: ' . $request->veiculo_id);

            return redirect('admin/ativo/veiculo/locacaoVeiculos')->with('success', 'Registro salvo com sucesso');
        } else {
            return redirect()->route('ativo.veiculo.locacaoVeiculos.adicionar')->with('fail', 'Erro ao salvar registro');
        }
    }

    public function edit($id)
    {
        $editLocacaoVeiculos = VeiculoLocacao::with('funcionarios', 'obra', 'obraDestino', 'veiculo')->find($id);
        
        $obras = CadastroObra::select('id', 'codigo_obra', 'razao_social')->orderByDesc('id')->get();
        
        $funcionarios = CadastroFuncionario::orderByDesc('id')->get();
        
        $veiculos = Veiculo::orderByDesc('id')->get();


        if(!$id or !$editLocacaoVeiculos) {
            return redirect('admin/ativo/veiculo/locacaoVeiculos')->with('fail', 'Esse registro não foi encontrado.');
        }

        return view('pages.ativos.veiculos.locacao.form', compact('veiculos', 'editLocacaoVeiculos', 'obras', 'funcionarios'));
    }

    public function update(Request $request, $id)
    {
        $updateLocacaoVeiculos = VeiculoLocacao::find($id);
        
         $updateLocacaoVeiculos->update([
                    "id_obra"           => $request->id_obra,
                    "veiculo_id"        => $request->veiculo_id,
                    "tipo_veiculo"      => $request->tipo_veiculo,
                    "id_obraDestino"    => $request->id_obraDestino,
                    "id_funcionario"    => $request->id_funcionario,
                    "data_inicio"       => $request->data_inicio,
                    "data_prevista"     => $request->data_prevista,
                    "data_fim"          => $request->data_fim
                    ]);
        
        
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT A LOCACAO: ' . $request->id);

        return redirect('admin/ativo/veiculo/locacaoVeiculos')->with('success', 'Registro atualizado com sucesso!');
         
         
    }

    public function delete($id)
    {
        $destroyLocacaoVeiculo = VeiculoLocacao::find($id);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE LOCACAO: ' . $destroyLocacaoVeiculo->id);

        if ($destroyLocacaoVeiculo->delete()) {
            
            return redirect('admin/ativo/veiculo/locacaoVeiculos')->with('success', 'Registro excluído com sucesso.');
            
        } else {
            return redirect('admin/ativo/veiculo/locacaoVeiculos')->with('fail', 'Um erro ocorreu na tentativa de exclusão');
            
        }
    }

   public function anexo($id_ativo_externo = null)
    {
        if (!$id_ativo_externo) {
            return redirect()->route('ativo.veiculo.index')->with('fail', 'Problemas para localizar o ativo.');
        }

        $anexo = Anexo::where('id_item', $id_ativo_externo)
        ->where('id_modulo', 28 )
        ->get();

        if (!$anexo) {
            return [];
        }

        if ($anexo) {
            return view('components.anexo.lista_anexo', compact('anexo'));
        }
    }


    public function pesquisar_placa_modelo(Request $request)
    {

        $tipo = $request->selecao_tipo;

        //dd($tipo);
       
        $placa_modelo = Veiculo::where('tipo', $tipo)->get();     

        return response()->json($placa_modelo);
    }
}
