<?php

namespace App\Http\Controllers;

use App\Models\CadastroEmpresa;
use App\Models\CadastroFornecedor;
use Illuminate\Http\Request;

use App\Models\Transferencia;
use App\Models\{AtivoConfiguracao, AtivoExterno, AtivoExernoStatus, AtivoExternoEstoque, CadastroFuncao, CadastroObra, CadastroFuncionario, Veiculo, VeiculoAbastecimento, VeiculoIpva, VeiculoManutencao, VeiculoSeguro};

use App\Models\VeiculoQuilometragem;

class TransferenciaController extends Controller
{
    //
    public function index()
    {
        return view('pages.transferencia.index');
    }

    /** Transferência de Obras */
    public function obra()
    {
        $obras = Transferencia::getObrasSGA();
        return view('pages.transferencia.obra', compact('obras'));
    }

    public function obra_store(Request $request)
    {

        $obras = Transferencia::getObrasSGA();
        $obras_gestao = CadastroObra::count();

        if ($obras->count() == $obras_gestao) {
            return redirect()->route('transferencia.obra')->with('fail', 'Estes dados já foram importados no sistema.');
        }

        try {

            foreach ($obras as $o) {
                $obra = new CadastroObra();
                $obra->id = $o->id_obra;
                $obra->id_empresa = $o->id_empresa;
                $obra->nome_fantasia = $o->codigo_obra;
                $obra->codigo_obra = $o->codigo_obra;
                $obra->razao_social = $o->obra_razaosocial;
                $obra->cnpj = $o->obra_cnpj;
                $obra->cep = $o->endereco_cep;
                $obra->endereco = $o->endereco;
                $obra->numero = $o->endereco_numero;
                $obra->bairro = $o->endereco_bairro;
                $obra->cidade = $o->endereco_cidade;
                $obra->estado = $o->endereco_estado;
                $obra->email = $o->responsavel_email;
                $obra->celular = $o->responsavel_celular;
                $obra->status = ($o->situacao == 0) ? 'Ativo' : 'Inativo';
                $obra->save();
            }

            return redirect()->route('transferencia.obra')->with('success', 'Dados importados com sucesso!');
        } catch (\Illuminate\Database\QueryException $exception) {

            return redirect()->route('transferencia.obra')->with('fail', 'Erro: ' . $exception->errorInfo[2]);
        }
    }


    /** Transferência de Empresas */
    public function empresa()
    {
        $empresas = Transferencia::getEmpresasSGA();
        return view('pages.transferencia.empresa', compact('empresas'));
    }

    public function empresa_store(Request $request)
    {

        $empresas = Transferencia::getEmpresasSGA();
        $empresas_gestao = CadastroEmpresa::count();

        if ($empresas->count() == $empresas_gestao) {
            return redirect()->route('transferencia.empresa')->with('fail', 'Estes dados já foram importados no sistema.');
        }

        try {

            foreach ($empresas as $o) {
                $empresa = new CadastroEmpresa();
                $empresa->id = $o->id_empresa;
                $empresa->nome_fantasia = $o->nome_fantasia;
                $empresa->razao_social = $o->razao_social;
                $empresa->cnpj = $o->cnpj;
                $empresa->cep = $o->endereco_cep;
                $empresa->endereco = $o->endereco;
                $empresa->numero = $o->endereco_numero;
                $empresa->bairro = $o->endereco_bairro;
                $empresa->cidade = $o->endereco_cidade;
                $empresa->estado = $o->endereco_estado;
                $empresa->email = $o->responsavel_email;
                $empresa->celular = $o->responsavel_celular;
                $empresa->status = ($o->situacao == 0) ? 'Ativo' : 'Inativo';
                $empresa->save();
            }

            return redirect()->route('transferencia.empresa')->with('success', 'Dados importados com sucesso!');
        } catch (\Illuminate\Database\QueryException $exception) {

            return redirect()->route('transferencia.empresa')->with('fail', 'Erro: ' . $exception->errorInfo[2]);
        }
    }


    /** Transferência de Fornecedores */
    public function fornecedor()
    {
        $fornecedores = Transferencia::getFornecedoresSGA();
        return view('pages.transferencia.fornecedor', compact('fornecedores'));
    }

    public function fornecedor_store(Request $request)
    {

        $fornecedores = Transferencia::getFornecedoresSGA();
        $fornecedores_gestao = CadastroFornecedor::count();

        if ($fornecedores->count() == $fornecedores_gestao) {
            return redirect()->route('transferencia.fornecedor')->with('fail', 'Estes dados já foram importados no sistema.');
        }

        try {

            foreach ($fornecedores as $o) {
                $fornecedor = new CadastroFornecedor();
                $fornecedor->id = $o->id_fornecedor;
                $fornecedor->nome_fantasia = $o->nome_fantasia;
                $fornecedor->razao_social = $o->razao_social;
                $fornecedor->cnpj = $o->cnpj;
                $fornecedor->cep = $o->endereco_cep;
                $fornecedor->endereco = $o->endereco;
                $fornecedor->numero = $o->endereco_numero;
                $fornecedor->bairro = $o->endereco_bairro;
                $fornecedor->cidade = $o->endereco_cidade;
                $fornecedor->estado = $o->endereco_estado;
                $fornecedor->email = $o->responsavel_email;
                $fornecedor->celular = $o->responsavel_celular;
                $fornecedor->status = ($o->situacao == 0) ? 'Ativo' : 'Inativo';
                $fornecedor->save();
            }

            return redirect()->route('transferencia.fornecedor')->with('success', 'Dados importados com sucesso!');
        } catch (\Illuminate\Database\QueryException $exception) {

            return redirect()->route('transferencia.fornecedor')->with('fail', 'Erro: ' . $exception->errorInfo[2]);
        }
    }


    /** Transferência de Fornecedores */
    public function funcionario()
    {
        $funcionarios = Transferencia::getFuncionariosSGA();
        return view('pages.transferencia.funcionario', compact('funcionarios'));
    }

    public function funcionario_store(Request $request)
    {

        $funcionarios = Transferencia::getFuncionariosSGA();
        $funcionarios_gestao = CadastroFuncionario::count();

        if ($funcionarios->count() == $funcionarios_gestao) {
            return redirect()->route('transferencia.funcionario')->with('fail', 'Estes dados já foram importados no sistema.');
        }

        try {

            /** Cadastra Funções */
            // $funcoes = Transferencia::getFuncionariosFuncoesSGA();

            // foreach ($funcoes as $func) {
            //     $funcao = new CadastroFuncao();
            //     $funcao->codigo_cbo = $func->codigo_interno;
            //     $funcao->titulo = $func->titulo;
            //     $funcao->save();
            // }

            /** 
             * Cadastra Funcionários 
             * Ocultado ID_FUNCAO
             * */
            foreach ($funcionarios as $func) {
                $funcionario = new CadastroFuncionario();
                $funcionario->id = $func->id_funcionario;
                $funcionario->matricula = $func->matricula;
                $funcionario->id_obra = $func->id_obra;
                $funcionario->nome = strtoupper($func->nome);
                $funcionario->data_nascimento = $func->data_nascimento;
                $funcionario->cpf = $func->cpf;
                $funcionario->rg = $func->rg;
                $funcionario->cep = $func->endereco_cep;
                $funcionario->endereco = $func->endereco;
                $funcionario->numero = $func->endereco_numero;
                $funcionario->bairro = $func->endereco_bairro;
                $funcionario->cidade = $func->endereco_cidade;
                $funcionario->estado = $func->endereco_estado;
                $funcionario->email = $func->email ?? null;
                $funcionario->celular = $func->celular;
                $funcionario->status = ($func->situacao == 0) ? 'Ativo' : 'Inativo';
                $funcionario->save();
            }

            return redirect()->route('transferencia.funcionario')->with('success', 'Dados importados com sucesso!');
        } catch (\Illuminate\Database\QueryException $exception) {

            return redirect()->route('transferencia.funcionario')->with('fail', 'Erro: ' . $exception->errorInfo[2]);
        }
    }

    /** Transferência de Configurações de Ativos */
    public function ativo_configuracao()
    {
        $configuracoes = Transferencia::getAtivoConfiguracaoSGA();
        return view('pages.transferencia.ativo_configuracao', compact('configuracoes'));
    }

    public function ativo_configuracao_store(Request $request)
    {
        $ativo_configuracao = Transferencia::getAtivoConfiguracaoSGA();
        $ativo_configuracao_gestao = AtivoConfiguracao::count();

        if ($ativo_configuracao->count() == $ativo_configuracao_gestao) {
            return redirect()->route('transferencia.ativo_configuracao')->with('fail', 'Estes dados já foram importados no sistema.');
        }

        try {

            /** 
             * Cadastra Funcionários 
             * Ocultado ID_FUNCAO
             * */
            foreach ($ativo_configuracao as $config) {
                $configuracao = new AtivoConfiguracao();
                $configuracao->id_relacionamento = $config->id_ativo_configuracao_vinculo;
                $configuracao->titulo = $config->titulo;
                $configuracao->status = "Ativo";
                $configuracao->save();
            }

            return redirect()->route('transferencia.ativo_configuracao')->with('success', 'Dados importados com sucesso!');
        } catch (\Illuminate\Database\QueryException $exception) {

            return redirect()->route('transferencia.ativo_configuracao')->with('fail', 'Erro: ' . $exception->errorInfo[2]);
        }
    }




    /** Transferência de Configurações de Ativos */
    public function ativo()
    {
        $ativos = Transferencia::getAtivoExternoSGA();
        return view('pages.transferencia.ativo', compact('ativos'));
    }

    public function ativo_store(Request $request)
    {
        $ativo = $ativo_grupo = Transferencia::getAtivoExternoSGA();
        $ativo_gestao = AtivoExterno::count();

        if ($ativo->count() == $ativo_gestao) {
            return redirect()->route('transferencia.ativo')->with('fail', 'Estes dados já foram importados no sistema.');
        }

        try {
            /** Retorno dos Ativos conforme o Titulo */
            foreach ($ativo_grupo as $grupo) {
                $group = new AtivoExterno();
                $group->id_ativo_configuracao = $grupo->id_ativo_externo_categoria;
                $group->titulo = $grupo->nome;
                $group->status = 'Ativo';
                $group->save();

                $id_ativo_externo = $group->id;
                $ativo_lista = Transferencia::getAtivoExternoByNomeSGA($grupo->nome);

                foreach ($ativo_lista as $i => $ativoObra) {
                    $ativo_estoque = new AtivoExternoEstoque();
                    $ativo_estoque->id_ativo_externo = $id_ativo_externo;
                    $ativo_estoque->id_obra  = $ativoObra->id_obra;
                    $ativo_estoque->patrimonio = $ativoObra->codigo;
                    $ativo_estoque->data_descarte = $ativoObra->data_descarte;
                    $ativo_estoque->valor = $ativoObra->valor;
                    $ativo_estoque->calibracao = $ativoObra->necessita_calibracao;
                    $ativo_estoque->status = Transferencia::setAtivoExternoSituacao($ativoObra->situacao) ?? null;
                    $ativo_estoque->created_at = $ativoObra->data_inclusao;
                    $ativo_estoque->save();

                    $situacao[$id_ativo_externo . $i]['situacao_atual'] = $ativoObra->situacao;
                    $situacao[$id_ativo_externo . $i]['situacao_nova'] = $ativo_estoque->status;
                    $situacao[$id_ativo_externo . $i]['titulo'] = AtivoExernoStatus::find(Transferencia::setAtivoExternoSituacao($ativoObra->situacao))->titulo;
                }

            }
            return redirect()->route('transferencia.ativo')->with('success', 'Dados importados com sucesso!');
        } catch (\Illuminate\Database\QueryException $exception) {
            return redirect()->route('transferencia.ativo')->with('fail', 'Erro: ' . $exception->errorInfo[2]);
        }
    }

        /** Transferência de Configurações de Ativos */
    public function veiculo()
    {
        $veiculos = Transferencia::getVeiculoSGA();
        return view('pages.transferencia.veiculo', compact('veiculos'));
    }

     public function veiculo_store(Request $request)
    {
        $veiculo =  Transferencia::getVeiculoSGA();
        $veiculo_gestao = Veiculo::count();


        if ($veiculo->count() == $veiculo_gestao) {
            return redirect()->route('transferencia.veiculo')->with('fail', 'Estes dados já foram importados no sistema.');
        }

        try {

            /** Veiculos */




            /** Importar Itens Internos do Veiculo */
            foreach ($veiculo as $expVeiculos) {

                $salvar_veiculo = new Veiculo();
                $salvar_veiculo->id = $expVeiculos->id_ativo_veiculo;
                $salvar_veiculo->periodo_inicial = $expVeiculos->periodo_inicial;
                $salvar_veiculo->periodo_final = $expVeiculos->periodo_final;
                $salvar_veiculo->tipo = $expVeiculos->tipo_veiculo;
                $salvar_veiculo->marca = $expVeiculos->marca;
                $salvar_veiculo->obra_id = $expVeiculos->id_obra;
                $salvar_veiculo->modelo = $expVeiculos->modelo;
                $salvar_veiculo->ano = $expVeiculos->ano;
                $salvar_veiculo->veiculo = $expVeiculos->veiculo;
                $salvar_veiculo->valor_fipe = $expVeiculos->valor_fipe;
                $salvar_veiculo->codigo_fipe = $expVeiculos->codigo_fipe;
                $salvar_veiculo->fipe_mes_referencia = $expVeiculos->fipe_mes_referencia;
                $salvar_veiculo->codigo_da_maquina = $expVeiculos->id_marca;
                $salvar_veiculo->placa = $expVeiculos->veiculo_placa;
                $salvar_veiculo->renavam = $expVeiculos->veiculo_renavam;
                $salvar_veiculo->horimetro_inicial = $expVeiculos->veiculo_horimetro;
                $salvar_veiculo->quilometragem_inicial = $expVeiculos->veiculo_km;
                $salvar_veiculo->observacao = $expVeiculos->veiculo_observacoes;
                $salvar_veiculo->situacao = $expVeiculos->situacao;
                $salvar_veiculo->save();

                /* echo "<pre>";
                var_dump($expVeiculos);
                die;
 */

                /** Seguro */
                $seguros = Transferencia::getVeiculoSGASeguro($expVeiculos->id_ativo_veiculo);

                foreach ($seguros as $seguro) {
                    $seguro_salvar = new VeiculoSeguro();
                    $seguro_salvar->id = $seguro->id_ativo_veiculo_seguro;
                    $seguro_salvar->veiculo_id = $expVeiculos->id_ativo_veiculo;
                    $seguro_salvar->carencia_inicial = $seguro->carencia_inicio;
                    $seguro_salvar->carencia_final = $seguro->carencia_fim;
                    $seguro_salvar->valor = $seguro->seguro_custo ?? '0.00';
                    $seguro_salvar->save();
                }
                
                /** IPVA */

                $ipvas = Transferencia::getVeiculoSGAIpva($expVeiculos->id_ativo_veiculo);

                foreach ($ipvas as $ipva) {

                    $ipva_salvar = new VeiculoIpva();
                    $ipva_salvar->id = $ipva->id_ativo_veiculo_ipva;
                    $ipva_salvar->veiculo_id = $expVeiculos->id_ativo_veiculo;
                    $ipva_salvar->referencia_ano = $ipva->ipva_ano;
                    $ipva_salvar->valor = $ipva->ipva_custo ?? '0.00';
                    $ipva_salvar->data_de_vencimento = $ipva->ipva_data_pagamento;
                    $ipva_salvar->data_de_pagamento = $ipva->ipva_data_vencimento;
                    $ipva_salvar->save();
                }


                /** Abastecimento */

                $abastecnimentos = Transferencia::getVeiculoSGAAbastecnimento($expVeiculos->id_ativo_veiculo);

                foreach ($abastecnimentos as $abastecnimento) 
                {
                    $abastecnimento_salvar = new VeiculoAbastecimento();
                    $abastecnimento_salvar->id = $abastecnimento->id_ativo_veiculo_abastecimento;
                    $abastecnimento_salvar->veiculo_id = $expVeiculos->id_ativo_veiculo;
                    $abastecnimento_salvar->fornecedor_id = $abastecnimento->id_fornecedor;
                    $abastecnimento_salvar->combustivel = $abastecnimento->combustivel;
                    $abastecnimento_salvar->quilometragem = $abastecnimento->veiculo_km;
                    $abastecnimento_salvar->valor_do_litro = $abastecnimento->combustivel_unidade_valor;
                    $abastecnimento_salvar->quantidade = $abastecnimento->combustivel_unidade_total;
                    $abastecnimento_salvar->valor_total = $abastecnimento->abastecimento_custo;

                   $abastecnimento_salvar->save();
                   
                }

                /** Quilometragem */
                $distanciasPerc = Transferencia::getVeiculoSGADistPerc($expVeiculos->id_ativo_veiculo);

                foreach ($distanciasPerc as $distancia_Perc) {
                    
                    $distancia_salvar = new VeiculoQuilometragem(); 
                                    
                    $distancia_salvar->veiculo_id = $expVeiculos->id_ativo_veiculo;
                    $distancia_salvar->id = $distancia_Perc->id_ativo_veiculo_quilometragem;
                    $distancia_salvar->quilometragem_atual = $distancia_Perc->veiculo_km;
                    $distancia_salvar->quilometragem_nova = $distancia_Perc->veiculo_km;
                    $distancia_salvar->save();
                    
                }



                /** Operação / Horímetro */

                $manutencoes = Transferencia::getVeiculoSGAManutencao($expVeiculos->id_ativo_veiculo);

                foreach ($manutencoes as $manutencao) {

                    $manutencao_salvar = new VeiculoManutencao();                   
                    $manutencao_salvar->veiculo_id = $expVeiculos->id_ativo_veiculo;
                    $manutencao_salvar->id = $manutencao->id_ativo_veiculo_manutencao;
                    $manutencao_salvar->fornecedor_id = $manutencao->id_fornecedor;
                    $manutencao_salvar->servico_id = $manutencao->id_ativo_configuracao;
                    $manutencao_salvar->tipo = $manutencao->id_ativo_veiculo;
                    $manutencao_salvar->quilometragem_atual = $manutencao->veiculo_km_atual;
                    $manutencao_salvar->quilometragem_nova = $manutencao->veiculo_km_proxima_revisao;

                    $manutencao_salvar->horimetro_atual = $manutencao->veiculo_horimetro_atual;
                    $manutencao_salvar->horimetro_proximo = $manutencao->veiculo_horimetro_proxima_revisao;
                    $manutencao_salvar->data_de_execucao = $manutencao->data_entrada;
                    $manutencao_salvar->data_de_vencimento = $manutencao->data_vencimento;
                    $manutencao_salvar->descricao = $manutencao->descricao;
                    $manutencao_salvar->valor_do_servico = $manutencao->veiculo_custo;
                    $manutencao_salvar->situacao = 0;
                    $manutencao_salvar->save();
                }

            }

            return redirect()->route('transferencia.veiculo')->with('success', 'Dados importados com sucesso!');
        } catch (\Illuminate\Database\QueryException $exception) {
            return redirect()->route('transferencia.veiculo')->with('fail', 'Erro: ' . $exception->errorInfo[2]);
        }
    }

    /** Executar todas as transferências */
    public function todas(Request $request)
    {

        /** Sincroniza dados com a tabela antiga */

        TransferenciaController::empresa_store($request);

        TransferenciaController::obra_store($request);

        TransferenciaController::fornecedor_store($request);

        TransferenciaController::funcionario_store($request);

        TransferenciaController::ativo_configuracao_store($request);

        TransferenciaController::ativo_store($request);

        TransferenciaController::veiculo_store($request);

        return redirect()->route('transferencia.ativo')->with('success', 'Dados importados com sucesso!');
    }
}
