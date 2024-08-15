<?php

namespace App\Http\Controllers;

use App\Models\Anexo;
use App\Models\{
    FerramentalRetirada,
    FerramentalRetiradaItem,
    AtivoExternoEstoque,
    VeiculoAbastecimento,
    VeiculoManutencao,
    VeiculoSeguro,
    Veiculo
};
use Database\Seeders\VeiculoSeguroSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    Storage,
    Log
};

class AnexoController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function upload(Request $request, $input = "arquivo")
    {
        $request->validate([
            'file' => 'mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf|max:2048'
        ], [
            'file.mimes' => 'O tipo de arquivo que você está tentando enviar não é válido.'
        ]);



        $anexo = new Anexo;

        if ($request->file($input)) {
            $diretorio = $request->diretorio ?? 'uploads';
            $nome_arquivo = time() . '_' . $request->file($input)->getClientOriginalName();



            $arrayDiretorio = array(
                "ativo_externo",
                "externo",
                "termos_retirada",
                "veiculo",
                "manutencao",
                "abastecimento",
                "seguro",
                "doc_ativo_externo"
            );

            $valorDiretorio = $request->diretorio; // O valor que você deseja verificar

            if (in_array($valorDiretorio, $arrayDiretorio)) {

                $anexo->id_usuario = Auth::user()->id ?? 1;

                if ($request->diretorio == 'externo') {
                    $anexo->id_method = 3;
                } else {
                    $anexo->id_method = 4;
                }

                $anexo->id_anexo = 0;
                $anexo->id_modulo = $request->id_modulo;
                $anexo->id_item = $request->id_item;
                $anexo->titulo = $request->titulo ?? null;
                $anexo->data_vencimento = $request->data_vencimento ?? null;
                $anexo->data_cadastro = $request->data_cadastro ?? null;
                $anexo->tipo = $request->file($input)->getClientOriginalExtension();
                $anexo->arquivo = $nome_arquivo;
                $anexo->descricao = $request->detalhes ?? null;
                $anexo->nome_modulo = $diretorio;

                $userLog = Auth::user()->email;

                Log::channel('main')->info($userLog . ' | UPDATE ANEXO: ' . $nome_arquivo);



                if ($anexo->save()) {

                    /** Termos de Retirada */
                    if ($request->diretorio == 'termos_retirada') {
                        $detalhes = FerramentalRetirada::getRetiradaItems($request->id_item);
                        if ($detalhes->itens) {

                            // Atualiza Retirada
                            $retirada = FerramentalRetirada::find($request->id_item);
                            $retirada->status = 2;
                            $retirada->save();

                            // Atualiza Item da Retirada
                            foreach ($detalhes->itens as $ret) {
                                $retirada_item = FerramentalRetiradaItem::find($ret->id_retirada);
                                $retirada_item->status = 2; // Entregue
                                $retirada_item->save();
                            }

                            // Atualiza Estoque
                            foreach ($detalhes->itens as $ent) {
                                $estoque = AtivoExternoEstoque::find($ent->id_ativo_externo);
                                $estoque->status = 6; // Em Operação
                                $estoque->save();
                            }
                        }

                        if ($request->file($input)) {
                            $diretorio = $request->diretorio ?? 'uploads';
                            $nome_arquivo = time() . '_' . $request->file($input)->getClientOriginalName();
                            $request->file($input)->storeAs('uploads/' . $diretorio, $nome_arquivo, 'public');
                        }
                        
                        return redirect()->route('ferramental.retirada.detalhes', $request->id_item)->with('success', 'Arquivo enviado com sucesso.');
                    }

                    /** Ativo Externo */
                    if ($valorDiretorio == 'ativo_externo') {

                        $request->file($input)->storeAs('uploads/' . $valorDiretorio, $nome_arquivo, 'public');

                        //Registra o log da inclusão
                        $userLog = Auth::user()->email;
                        Log::channel('main')->info($userLog . ' | CALIBRAÇÃO - INCLUSÃO DO ANEXO: ' . $anexo->titulo);

                        return redirect()->route('ativo.externo')->with('success', 'Arquivo enviado com sucesso.');
                    }

                    // Anexo das Ferramentas
                    elseif ($valorDiretorio == 'doc_ativo_externo') {

                        $request->file($input)->storeAs('uploads/' . $valorDiretorio, $nome_arquivo, 'public');

                        //Registra o log da inclusão
                        $userLog = Auth::user()->email;
                        Log::channel('main')->info($userLog . ' | DESC. DA FERRAMENTA - INCLUSÃO DO ANEXO: ' . $anexo->titulo);


                        return redirect()->route('ativo.externo.detalhes', $request->id_item)->with('success', 'Arquivo enviado com sucesso.');
                    }

                    // anexo para as ferramentas descartadas 
                    elseif ($valorDiretorio == 'externo') {
                        
                        //dd($anexo->id_item);
                        
                        $alterarStatus = AtivoExternoEstoque::where('id', $anexo->id_item);
                        
                        $statusForaOpe['status'] = 9;
                        
                        $alterarStatus->update($statusForaOpe);

                        $request->file($input)->storeAs('uploads/' . $valorDiretorio, $nome_arquivo, 'public');
                        

                        //Registra o log da inclusão
                        $userLog = Auth::user()->email;
                        Log::channel('main')->info($userLog . ' | DESC. DA FERRAMENTA - INCLUSÃO DO ANEXO: ' . $anexo->titulo);
                        
                        return redirect()->back()->with('successo', 'A ferramenta está fora de opereação. Relarório criado com sucesso.');
                        session()->forget('success');

                        //return redirect()->route('ativo.externo.editar', $request->id_item)->with('success', 'Arquivo enviado com sucesso.');
                    }

                    //Termo de retirada
                    elseif ($valorDiretorio == 'termos_retirada') {

                        $request->file($input)->storeAs('uploads/' . $valorDiretorio, $nome_arquivo, 'public');

                        //Registra o log da inclusão
                        $userLog = Auth::user()->email;
                        Log::channel('main')->info($userLog . ' | TERMO DE RETIRADA - INCLUSÃO DO ANEXO: ' . $anexo->titulo);

                        return redirect()->route('ferramental.retirada.detalhes', $request->id_item)->with('success', 'Arquivo enviado com sucesso.');
                    }
                    //Veiculos 
                    elseif ($valorDiretorio == 'veiculo') {

                        $request->file($input)->storeAs('uploads/' . $valorDiretorio, $nome_arquivo, 'public');

                        //Registra o log da inclusão
                        $userLog = Auth::user()->email;
                        Log::channel('main')->info($userLog . ' | VEICULO - INCLUSÃO DO ANEXO: ' . $anexo->titulo);

                        return redirect('admin/ativo/veiculo')->with('success', 'Arquivo enviado com sucesso.');
                        
                    } else {

                        $request->file($input)->storeAs('uploads/' . $valorDiretorio, $nome_arquivo, 'public');

                        //Registra o log da inclusão
                        $userLog = Auth::user()->email;
                        Log::channel('main')->info($userLog . ' | ' . $valorDiretorio . ' - INCLUSÃO DO ANEXO: ' . $anexo->titulo);

                       return redirect()->route('ativo.veiculo.' . $valorDiretorio . '.index', $request->id_veiculo)->with('success', 'Arquivo salvo com sucesso.');
                    }
                }
            } else {

                return redirect()->back()->with('mensagem', 'Não foi possível processar sua solicitação de envio. Fale com seu supervisor.');
                session()->forget('mensagem');
            }
        }
    }


    /** Download de Arquivos */
    public function download($id)
    {
        $anexo = (Anexo::find($id)->arquivo) ?? 0;

        $moduloDownload = (Anexo::find($id)->nome_modulo ?? 0);
        // dd($moduloDownload);
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | DOWNLOAD ANEXO: ' . $anexo);

        if ($anexo === null) {
            Alert::error('Atenção', 'Não foi possível localizar o arquivo solicitado.');
            return redirect(route('ativo.' . $moduloDownload));
        }
        return Storage::download('public/uploads/' . $moduloDownload . '/' . $anexo);
    }

    /** Excluir Anexo */
    public function destroy(Veiculo $veiculo, Request $request, $id, $modulo = null)
    {


        $anexo = Anexo::find($id);
        $modulo = $anexo->nome_modulo;
        $redirect = str_replace("_", ".", $modulo);

        $arrayDiretorio = array(
            "ativo_externo",
            "externo",
            "termos_retirada",
            "veiculo",
            "manutencao",
            "abastecimento",
            "seguro",
            "doc_ativo_externo"
        );

        $valorDiretorio = $modulo; // O nome do módulo

        if (in_array($valorDiretorio, $arrayDiretorio)) {

            $manutencoes = VeiculoManutencao::find($anexo->id_item) ?? null;
            $abastecimento = VeiculoAbastecimento::find($anexo->id_item) ?? null;
            $seguro = VeiculoSeguro::find($anexo->id_item) ?? null;
            $veiculo = Veiculo::find($anexo->id_item) ?? null;

            if ($valorDiretorio == 'ativo_externo') {

                //Registra o log da exclusão
                $userLog = Auth::user()->email;
                Log::channel('main')->info($userLog . ' | DELETE ANEXO: ' . $anexo->titulo);

                $anexo->delete();

                return redirect('admin/ativo/externo')->with('success', 'Arquivo enviado com sucesso.');
            }

            // anexo para as ferramentas descartadas 
            elseif ($valorDiretorio == 'doc_ativo_externo') {

                //Registra o log da exclusão
                $userLog = Auth::user()->email;
                Log::channel('main')->info($userLog . ' | DELETE ANEXO: ' . $anexo->titulo);

                $anexo->delete();

                return redirect()->back()->with('mensagem', 'Arquivo exluido com sucesso.');
                session()->forget('mensagem');

                //return redirect()->route('ativo.externo.detalhes', $anexo->id)->with('success', 'Arquivo exluido com sucesso.');
            }

            // anexo para as ferramentas descartadas 
            elseif ($valorDiretorio == 'externo') {

                //Registra o log da exclusão
                $userLog = Auth::user()->email;
                Log::channel('main')->info($userLog . ' | DELETE ANEXO: ' . $anexo->titulo);

                $anexo->delete();

                return redirect('admin/ativo/externo')->with('success', 'Arquivo enviado com sucesso.');
            }

            //Termo de retirada
            elseif ($valorDiretorio == 'termos_retirada') {

                //Registra o log da exclusão
                $userLog = Auth::user()->email;

                Log::channel('main')->info($userLog . ' | DELETE ANEXO: ' . $anexo->titulo);

                $anexo->delete();

                return redirect()->route('ferramental.retirada.detalhes', $request->id_item)->with('success', 'Arquivo enviado com sucesso.');
            }
            
            //Veiculos 
            elseif ($valorDiretorio == 'veiculo') {

                //Registra o log da exclusão
                $userLog = Auth::user()->email;

                Log::channel('main')->info($userLog . ' | DELETE ANEXO: ' . $anexo->titulo);

                $anexo->delete();

                return redirect('admin/ativo/veiculo')->with('success', 'Arquivo deletado com sucesso.');
            } else {

                if ($redirect == "manutencao") {
                    $idVeiculo = $manutencoes->veiculo_id;
                } elseif ($redirect == "abastecimento") {
                    $idVeiculo = $abastecimento->veiculo_id;
                } elseif ($redirect == "seguro") {
                    $idVeiculo = $seguro->veiculo_id;
                } elseif ($redirect == "veiculo") {
                    $idVeiculo = $veiculo->id;
                }

                //Registra o log da exclusão
                $userLog = Auth::user()->email;
                Log::channel('main')->info($userLog . ' | DELETE ANEXO: ' . $anexo->titulo);

                $anexo->delete();

                return redirect()->route('ativo.veiculo.' . $valorDiretorio . '.index', $idVeiculo)->with('success', 'Arquivo salvo com sucesso.');
            }
        } else {

            //Registra o log da exclusão
            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | Error ao deletar o anexo: ' . $anexo->titulo);

            return redirect()->back()->with('mensagemFail', 'Não foi possível processar sua solicitação de envio. Fale com o responsável pelo sistesma.');
            session()->forget('mensagemFail');
        }
    }
}
