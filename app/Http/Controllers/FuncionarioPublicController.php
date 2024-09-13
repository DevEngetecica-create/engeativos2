<?php

namespace App\Http\Controllers;

use App\Models\CadastroEmpresa;
use App\Models\CadastroFuncionario;
use App\Models\CadastroObra;
use App\Models\FuncaoFuncionario;
use App\Models\FuncionarioQualificacao;
use App\Models\AnexoFuncionario;
use App\Helpers\Tratamento;

use Illuminate\Support\Facades\Storage;

class FuncionarioPublicController extends Controller
{
    public function publicShow($id)
    {
        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();
        $store = CadastroFuncionario::with('funcao', 'obra')->where('id', $id)->first();
        $obras = CadastroObra::where('status_obra', 'Ativo')->get();
        $funcoes = FuncaoFuncionario::all();
        
        $qualificacao_funcoes = FuncionarioQualificacao::with('qualificacoes')
            ->leftJoin('anexos_funcionarios', function ($join) {
                $join->on('funcionarios_qualificacoes.id_funcionario', '=', 'anexos_funcionarios.id_funcionario')
                     ->on('funcionarios_qualificacoes.id_funcao', '=', 'anexos_funcionarios.id_funcao')
                     ->on('funcionarios_qualificacoes.id_qualificacao', '=', 'anexos_funcionarios.id_qualificacao');
            })
            ->where('funcionarios_qualificacoes.id_funcionario', $id)
            ->where('funcionarios_qualificacoes.id_qualificacao', '!=', 0)
            ->select('funcionarios_qualificacoes.*', 'anexos_funcionarios.id as id_anexos', 'anexos_funcionarios.nome_arquivo', 'anexos_funcionarios.data_conclusao', 'anexos_funcionarios.data_validade_doc', 'anexos_funcionarios.situacao_doc', 'anexos_funcionarios.usuario_cad', 'anexos_funcionarios.usuario_aprov', 'anexos_funcionarios.data_aprovacao', 'anexos_funcionarios.observacoes')
            ->get();

        if ($store) {
            return view('pages.cadastros.funcionario.detalhes_funcionario', [
                'store' => $store,
                'obras' => $obras,
                'funcoes' => $funcoes,
                'empresas' => $empresas,
                'qualificacao_funcoes' => $qualificacao_funcoes
            ]);
        }

        return redirect('public/funcionario')->with('fail', 'Esse registro nПлкo foi encontrado.');
    }
    
    public function download($id)
    {
       
        $anexo = AnexoFuncionario::find($id);
        
        if (!$anexo) {
            return redirect()->route('cadastro.funcionario.show', $id)->with('warning', 'Anexo não encontrado!');
        }
    
        $download_documento = $anexo->arquivo;
        $id_funcionario = $anexo->id_funcionario;
    
        if ($download_documento) {
            // Obter o IP do usuário
            $userIp = Tratamento::getRealIpAddr();
    
            // Registrar no arquivo de log
            \Log::channel('main')->info("Download do documento: ID Funcionário: {$id_funcionario}, IP: {$userIp}");
    
            // Fazer o download do arquivo localizado em storage/app/public/uploads/usuarios/{id}/{file}
            // Caminho deve ser relativo a 'storage/app'
            
            $path = 'public/uploads/usuarios/' . $id_funcionario . '/' . $download_documento;
            $path_sem_id = 'public/uploads/usuarios/' . $download_documento;

            if (Storage::exists($path)) {
                
                return Storage::download($path);
                
            } else {
                
                // Trate o caso em que o arquivo não existe
                return Storage::download($path_sem_id);
                
                return redirect()->back()->with('error', 'Download efetuado porem não foi possivel encontrar a pasta');
            }
            
        } else {
            return redirect()->route('cadastro.funcionario.show', $id_funcionario)->with('warning', 'Não foi possível efetuar o download do arquivo');
        }
    }


}
