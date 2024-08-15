<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Models\VeiculoIpva;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    Storage,
    Log
};

class VeiculoIpvaController extends Controller
{

    public function index(Veiculo $veiculo)
    {
        $veiculos = Veiculo::with('quilometragens')->get();

        $ipvas = VeiculoIpva::where('veiculo_id', $veiculo->id)->orderByDesc('id')->get();

        return view('pages.ativos.veiculos.ipva.index', compact('veiculo', 'ipvas'));
    }

    public function create(Veiculo $veiculo)
    {
        return view('pages.ativos.veiculos.ipva.create', compact('veiculo'));
    }

    public function store(Request $request)
    {

        $request->validate([
            "veiculo_id"            => 'required',
            "referencia_ano"        => 'required',
            "valor"                 => 'required',
            "data_de_vencimento"    => 'required',
            "data_de_pagamento"     => 'required',
            "nome_anexo_ipva"       => 'required',
            "extensao"              => 'required',
        ]);


        if ($request->file('extensao')) {

            $ext = $request->extensao->getClientOriginalExtension();

            if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'pdf' or $ext == '') {

                $data_criacao_arquivo = date("d.m.Y-h.m.s");
                $nameFile = $data_criacao_arquivo . $request->nome_anexo_ipva . '.' . $ext;
                $nome_anexo = preg_replace('/[ -]+/', '-',  $nameFile);
                $request->extensao->storeAs('public/ipva',  $nome_anexo);
            } else {

                return  redirect()->to(url()->previous())->with('fail', 'A extensão  do arquivo não é permitida');
            }

            $saveIpva = new VeiculoIpva([
                "veiculo_id"            => $request->veiculo_id,
                "referencia_ano"        => $request->referencia_ano,
                "valor"                 => $request->valor,
                "data_de_vencimento"    => $request->data_de_vencimento,
                "data_de_pagamento"     => $request->data_de_pagamento,
                "nome_anexo_ipva"       => $request->nome_anexo_ipva,
                "extensao"              => $nome_anexo,
            ]);

            $saveIpva->save();

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | STORE IPVA: ' . $request->veiculo_id);

            return redirect()->route('ativo.veiculo.ipva.index', $request->veiculo_id)->with('success', 'Registro salvo com sucesso');
        } else {

            return redirect()->route('ativo.veiculo.ipva.index', $request->veiculo_id)->with('fail', 'Erro ao salvar registro');
        }
    }

    public function edit($id)
    {
        $ipva = VeiculoIpva::with('veiculo')->where('id', $id)->first();

        return view('pages.ativos.veiculos.ipva.edit', compact('ipva'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            "veiculo_id"            => 'required',
            "referencia_ano"        => 'required',
            "valor"                 => 'required',
            "data_de_vencimento"    => 'required',
            "data_de_pagamento"     => 'required',
            "nome_anexo_ipva"       => 'required',

        ]);

        $ipva = VeiculoIpva::find($id);

        if (!$ipva = VeiculoIpva::find($id)) {

            return redirect()->route('ativo.veiculo.ipva.editar', $id)->with('fail', 'Problemas para localizar o registro.');
        } else {

            if ($request->file('extensao')) {

                $ext = $request->extensao->getClientOriginalExtension();
                $data_criacao_arquivo = date("d.m.Y-h.m.s");
                $nameFile = $data_criacao_arquivo . $request->nome_anexo_ipva . '.' . $ext;
                $nome_anexo = preg_replace('/[ -]+/', '-',  $nameFile);

                if (Storage::exists("public/ipva/" . $request->extensao)) {

                    Storage::delete("public/ipva/" . $request->extensao);

                    $request->extensao->storeAs('public/ipva', $nome_anexo);

                    $ipva->update([
                        "veiculo_id"            => $request->veiculo_id,
                        "referencia_ano"        => $request->referencia_ano,
                        "valor"                 => $request->valor,
                        "data_de_vencimento"    => $request->data_de_vencimento,
                        "data_de_pagamento"     => $request->data_de_pagamento,
                        "nome_anexo_ipva"       => $request->nome_anexo_ipva,
                        "extensao"              => $nome_anexo,
                    ]);
                }
            } else {

                $ipva->update([
                    "veiculo_id"            => $request->veiculo_id,
                    "referencia_ano"        => $request->referencia_ano,
                    "valor"                 => $request->valor,
                    "data_de_vencimento"    => $request->data_de_vencimento,
                    "data_de_pagamento"     => $request->data_de_pagamento,
                    "nome_anexo_ipva"       => $request->nome_anexo_ipva,
                ]);
            }



            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | EDIT IPVA: ' . $id);

            return redirect()->route('ativo.veiculo.ipva.index',  $ipva->veiculo_id)->with('success', 'O registro foi alterado com sucesso');
        }
    }

    /** Download de Arquivos */
    public function download($id)

    {
        $ipvaDownload = VeiculoIpva::find($id);
        $anexonome = (VeiculoIpva::find($id)->nome_anexo_ipva) ?? null;
        $anexoextensao = (VeiculoIpva::find($id)->extensao) ?? null;

        $arquivo = $anexonome;

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | DOWNLOAD ANEXO: ' . $arquivo);


        if (Storage::exists('public/ipva/' .  $anexoextensao)) {

            return Storage::download('public/ipva/' .  $anexoextensao);

            return redirect()->route('ativo.veiculo.ipva.index',  $ipvaDownload->veiculo_id)->with('success', 'Download efetuado com sucesso!!!');
        } else {

            return redirect()->route('ativo.veiculo.ipva.index',  $ipvaDownload->veiculo_id)->with('fail', 'O IPVA não possui anexo');
        }
    }

    public function delete($id)
    {

        $ipva = VeiculoIpva::findOrFail($id);

        if ($ipva->delete()) {

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | DELETE MANUTENCAO: ' .  $ipva->id);

            return redirect()->route('ativo.veiculo.ipva.index',  $ipva->veiculo_id)->with('success', 'Registro excluído com sucesso');
        } else {

            return redirect()->route('ativo.veiculo.ipva.index',  $ipva->veiculo_id)->with('fail', 'Problema nas exclusão do registro');
        }
    }
}
