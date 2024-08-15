<?php

namespace App\Http\Controllers;

use App\Models\CadastroFornecedor;
use Illuminate\Http\Request;
use App\Models\Veiculo;
use App\Models\VeiculoAbastecimento;
use App\Models\Anexo;
use App\Models\CadastroFuncionario;
use App\Models\VeiculoQuilometragem;
use App\Models\VeiculoHorimetro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VeiculoAbastecimentoController extends Controller
{
    public function index(Veiculo $veiculo)
    {
        $fornecedores = CadastroFornecedor::select('id', 'razao_social')->get();
        $funcionarios = CadastroFuncionario::all();
        $abastecimentos = VeiculoAbastecimento::with('veiculo')
            ->where('veiculo_id', $veiculo->id)
            ->get();

        $media_quantidade = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->avg('quantidade');

        $media_valor_do_litro = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->avg('valor_do_litro');

        $media_valor_total = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->avg('valor_total');

        $last = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->orderByDesc('id')
            ->first();

        return view('pages.ativos.veiculos.abastecimento.index', compact('veiculo', 'abastecimentos', 'last', 'fornecedores', 'media_quantidade', 'media_valor_do_litro', 'media_valor_total', 'funcionarios'));
    }

    public function create(Veiculo $veiculo)
    {
        $fornecedores = CadastroFornecedor::select('id', 'razao_social')->get();
        $funcionarios = CadastroFuncionario::all();

        return view('pages.ativos.veiculos.abastecimento.create', compact('veiculo', 'fornecedores', 'funcionarios'));
    }

    public function store(Request $request, Veiculo $veiculo)
    {
        $request->validate([
            "veiculo_id" => 'required',
            "fornecedor" => 'required',
            "id_funcionario" => 'required',
            "data_cadastro" => 'required',
            "combustivel" => 'required',
            "quantidade" => 'required',
            "valor_do_litro" => 'required',
            "valor_total" => 'required',
            "nome_anexo" => 'required',
            "arquivo" => 'required',
        ]);

        $valor_do_litro = $this->formatCurrency($request->valor_do_litro);
        $valor_total = $this->formatCurrency($request->valor_total, true);

        if ($request->hasFile('arquivo')) {
            $arquivo = $request->file('arquivo');
            
            $nome_anexo = $this->uploadFile($arquivo, $request->nome_anexo);

            $quilometragem = $request->tipo == "maquinas" ? null : $request->quilometragem_nova;
            $horimetro = $request->tipo == "maquinas" ? $request->horimetro_novo : null;

            $saveAbastecimento = VeiculoAbastecimento::create([
                "veiculo_id" => $request->veiculo_id,
                "id_funcionario" => $request->id_funcionario,
                "usuario" => Auth::user()->email,
                "fornecedor" => $request->fornecedor,
                "data_cadastro" => $request->data_cadastro,
                "combustivel" => $request->combustivel,
                "quilometragem" => $quilometragem,
                "horimetro" => $horimetro,
                "quantidade" => $request->quantidade,
                "valor_total" => $valor_total,
                "valor_do_litro" => $valor_do_litro,
                "nome_anexo" => $nome_anexo,
            ]);

            //$this->updateVehicleMetrics($request, $saveAbastecimento);

            Log::channel('main')->info(Auth::user()->email . ' | STORE ABASTEC. DO VEICULO| ' . $request->veiculo_id . ' | COMBUSTÍVEL: ' . $request->combustivel . ' | QUILOMETRAGEM: ' . $request->quilometragem . ' | VALOR DO LITRO: ' . $request->valor_do_litro);

            return redirect()->route('ativo.veiculo.abastecimento.index', $request->veiculo_id)->with('success', 'O registro foi adicionado com sucesso.');
        }

        return redirect()->route('ativo.veiculo.abastecimento.index', $request->veiculo_id)->with('fail', 'Problemas para adicionar o registro.');
    }
    
    

    public function edit(Veiculo $veiculo, $id)
    {
        $abastecimento = VeiculoAbastecimento::with('veiculo', 'funcionario')->find($id);
        
        //dd($abastecimento);
        
        $fornecedores = CadastroFornecedor::select('id', 'razao_social')->get();
        $funcionarios = CadastroFuncionario::all();

        return view('pages.ativos.veiculos.abastecimento.edit', compact('veiculo', 'abastecimento', 'fornecedores', 'funcionarios'));
    }

    public function update(Request $request, $id)
    {
        $abastecimento = VeiculoAbastecimento::findOrFail($id);
    
        if ($request->hasFile('arquivo')) {
            $arquivo = $request->file('arquivo');
            $nome_anexo = $this->uploadFile($arquivo, $request->nome_anexo);
        }
    
        // Convertendo valores para o formato correto
        $valor_do_litro = $this->formatCurrency($request->valor_do_litro);
        $valor_total = $this->formatCurrency($request->valor_total, true);
        $quantidade = $this->formatCurrency($request->quantidade);
    
        $quilometragem = $request->tipo == "maquinas" ? null : $request->quilometragem;
        $horimetro = $request->tipo == "maquinas" ? $request->horimetro : null;
    
        $abastecimento->update([
            "veiculo_id" => $request->veiculo_id,
            "id_funcionario" => $request->id_funcionario,
            "usuario" => Auth::user()->email,
            "fornecedor" => $request->fornecedor,
            "data_cadastro" => $request->data_cadastro,
            "combustivel" => $request->combustivel,
            "quilometragem" => $quilometragem,
            "horimetro" => $horimetro,
            "quantidade" => $quantidade,
            "valor_total" => $valor_total,
            "valor_do_litro" => $valor_do_litro,
            "nome_anexo" => $nome_anexo ?? $abastecimento->nome_anexo,
        ]);
    
        Log::channel('main')->info(Auth::user()->email . ' | EDIT ABASTECIMENTO: ' . $abastecimento->id);
        return redirect()->route('ativo.veiculo.abastecimento.index', $request->veiculo_id)->with('success', 'O registro foi alterado com sucesso');
    }

    public function delete($id)
    {
        $veiculo = VeiculoAbastecimento::findOrFail($id);
        Log::channel('main')->info(Auth::user()->email . ' | DELETE ABASTECIMENTO: ' . $veiculo->id);

        if ($veiculo->delete()) {
            return redirect()->back()->with('success', 'O registro foi deletado com sucesso');
        } else {
            return redirect()->back()->with('fail', 'Problemas para deletar o registro.');
        }
    }

    public function anexo(Request $request, $id_ativo_externo = null)
    {
        if (!$id_ativo_externo) {
            return redirect()->url("admin/ativo/veiculo/abastecimento/{$request->id_veiculo}")->with('fail', 'Problemas para localizar o ativo.');
        }

        $anexo = Anexo::where('id_item', $id_ativo_externo)
            ->where('id_modulo', 29)
            ->get();

        if ($anexo->isEmpty()) {
            return redirect()->back()->with('fail', 'Nenhum anexo encontrado.');
        }

        return view('components.anexo.lista_anexo', compact('anexo'));
    }

    private function formatCurrency($value, $isTotal = false)
    {
        if ($isTotal) {
            $value = str_replace('.', '', $value);
        }
        return str_replace(',', '.', $value);
    }

    private function uploadFile($file, $fileName)
    {
        $ext = $file->getClientOriginalExtension();
        $data_criacao_arquivo = date("d.m.Y-h.m.s");
        $nameFile = $fileName . '.' . $ext;
        $nome_anexo = preg_replace('/[ -]+/', '-', $nameFile);
        $file->storeAs('public/uploads/abastecimento', $nome_anexo);
    
        return $nome_anexo;
    }

    

    /*private function updateHorimetro($request, $id)
    {
        $abastecimentoHr = VeiculoHorimetro::find($id);
        $verificarHorimetroUpdate = VeiculoHorimetro::where('id', $request->id)->value('horimetro_novo');

        if ($request->horimetro_novo !=  $verificarHorimetroUpdate) {
            VeiculoHorimetro::where('id', $request->id)->update([
                'horimetro_novo' => $request->horimetro_novo,
            ]);
        }

        $abastecimentoHr->update($request->all());
    }*/
}
