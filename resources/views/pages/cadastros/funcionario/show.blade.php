@extends('dashboard')
@section('title', 'Funcionários')
@section('content')

<style>
    /* Estilos do carregamento com pontos */
    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }
    .loading-text {
        font-size: 11px;
        font-weight: bold;
    }
    .dot {
        animation: blink 1.5s infinite;
    }
    .dot:nth-child(1) { animation-delay: 0s; font-size: 20px; color: red; }
    .dot:nth-child(2) { animation-delay: 0.3s; font-size: 20px; color: red; }
    .dot:nth-child(3) { animation-delay: 0.6s; font-size: 20px; color: red; }
    .dot:nth-child(4) { animation-delay: 0.9s; font-size: 11px; color: red; }
    .dot:nth-child(5) { animation-delay: 1.2s; font-size: 20px; color: red; }
</style>

<div class="profile-foreground position-relative mx-n4 mt-n4">
    <div class="profile-wid-bg"></div>
</div>
<div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
    <div class="row g-4">
        <div class="col-auto">
            <div class="avatar-lg">
                @if ($store->imagem)
                    <img src="{{ asset('imagens/usuarios') }}/{{ $store->id }}" class="img-thumbnail rounded-circle" />
                @else
                    <img src="{{ asset('imagens/usuarios/lista-de-usuarios.png') }}" class="img-thumbnail rounded-circle" />
                @endif
            </div>
        </div>
        <div class="col">
            <div class="p-2">
                <h3 class="text-white mb-1">{{ $store->nome }}</h3>
                <p class="text-white text-opacity-75">{{ $store->funcao->funcao ?? 'Sem reg.' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div>
            <div class="d-flex profile-wrapper">
                <!-- Nav tabs -->
                <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                            <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Visão Geral</span>
                        </a>
                    </li>
                    <li class="nav-item @if (session()->get('usuario_vinculo')->id_nivel <= 1) d-block @else d-none @endif">
                        <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                            <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Qualificações</span>
                        </a>
                    </li>
                    <li class="nav-item @if (session()->get('usuario_vinculo')->id_nivel <= 1) d-block @else d-none @endif">
                        <a class="nav-link fs-14" data-bs-toggle="tab" href="#documents" role="tab">
                            <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Documentos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-14" data-bs-toggle="tab" href="#dados_acesso" role="tab">
                            <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Dados de acesso ao Sistema</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-14" data-bs-toggle="tab" href="#retirada_estoque" role="tab">
                            <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Retiradas do estoque</span>
                        </a>
                    </li>
                </ul>
                <div class="flex-shrink-0">
                    <a href="{{ route('cadastro.funcionario.downloads_zip', $store->id) }}">
                        <button type="button" class="btn btn-warning btn-sm waves-effect waves-light material-shadow-none mx-3">
                            Baixar todos os arquivos <i class="mdi mdi-cloud-download mdi-24px"></i>
                        </button>
                    </a>
                    <a href="{{ route('cadastro.funcionario.editar', $store->id) }}">
                        <button type="button" class="btn btn-success btn-sm waves-effect waves-light material-shadow-none">
                            Editar Cadastro <i class="mdi mdi-pencil mdi-24px"></i>
                        </button>
                    </a>
                </div>
            </div>
            <!-- Tab panes -->
            <div class="tab-content pt-4 text-muted">
                <div class="tab-pane active" id="overview-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-xxl-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Informações</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="ps-0" scope="row">Matrícula :</th>
                                                    <td class="text-muted">{{ $store->matricula }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Nome completo :</th>
                                                    <td class="text-muted">{{ $store->nome }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">RG :</th>
                                                    <td class="text-muted">{{ $store->rg }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">CPF :</th>
                                                    <td class="text-muted">{{ $store->cpf }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Contato :</th>
                                                    <td class="text-muted">{{ $store->celular }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">E-mail :</th>
                                                    <td class="text-muted">{{ $store->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Endereço :</th>
                                                    <td class="text-muted">{{ $store->endereco }}, {{ $store->numero }}, <br>{{ $store->bairro }}, {{ $store->cidade }} - {{ $store->estado }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Data de cadastro : </th>
                                                    <td class="text-muted">{{ Tratamento::dateBr($store->created_at) }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Data de Nascimento : </th>
                                                    <td class="text-muted">{{ Tratamento::dateBr($store->data_nascimento) }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Status</th>
                                                    <td class="text-muted">{{ $store->status }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div>
                        <!--end col-->

                        <div class="col-xxl-9">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title m1-4">Qualificações</h5>
                                    <hr class="m-0 p-0 mb-5 text-warning">
                                    <div class="row">
                                        @foreach ($qualificacao_funcoes as $qualificacao)
                                            <div class="col-4">
                                                <div class="card w-100">
                                                    <h5 class="mx-3">{{ $qualificacao->qualificacoes->nome_qualificacao ?? 'Sem reg' }}</h5>
                                                    <hr class="m-0 p-0">
                                                    <div class="card-body pb-0">
                                                        @php
                                                            $situacao = $qualificacao->situacao_doc ?? $qualificacao->situacao;
                                                        @endphp

                                                        @if($situacao == 1)
                                                            <p class="mb-0">
                                                                <small><strong>Situação: </strong> 
                                                                    <button type="button" class="btn btn-warning btn-sm btn-label waves-effect waves-light">
                                                                        <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                                        Pendente
                                                                    </button>
                                                                </small>
                                                            </p>
                                                        @elseif($situacao == 2)
                                                            <p class="mb-0">
                                                                <small><strong>Situação: </strong> 
                                                                    <button type="button" class="btn btn-success btn-sm btn-label waves-effect waves-light">
                                                                        <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                                        Aprovado
                                                                    </button>
                                                                </small>
                                                            </p>
                                                        @elseif($situacao == 18)
                                                            <p class="mb-0">
                                                                <small><strong>Situação: </strong> 
                                                                    <button type="button" class="btn btn-danger btn-sm btn-label waves-effect waves-light">
                                                                        <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                                        Reprovado
                                                                    </button>
                                                                </small>
                                                            </p>
                                                        @endif

                                                        <p class="mb-0"><small><strong>Cad. por: </strong> {{ $qualificacao->usuario_cad ?? '-' }} </small></p>
                                                        <p class="mb-0"><small><strong>Apr. por:</strong> {{ $qualificacao->usuario_aprov ?? '-' }}</small></p>
                                                        <p class="mb-0">
                                                            <small><strong>Motivo:</strong>
                                                                @if (!$qualificacao->usuario_aprov && !$qualificacao->nome_arquivo)
                                                                    Faltam os documentos
                                                                @elseif(!$qualificacao->usuario_aprov && $qualificacao->nome_arquivo)
                                                                    {{ $qualificacao->observacoes }}
                                                                @endif
                                                            </small>
                                                        </p>
                                                    </div>
                                                    <div class="card-footer">
                                                        <small class="text-muted">Data da aprovação:
                                                            @if($qualificacao->data_aprovacao)
                                                                {{ Tratamento::datetimeBr($qualificacao->data_aprovacao) }}
                                                            @else
                                                                Sem registro
                                                            @endif    
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div><!-- end card body -->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>

                <div class="tab-pane fade" id="projects" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mt-4">
                                <div class="col-sm-12 col-xl-12">
                                    <div class="card border">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="card-title mb-0 flex-grow-1">Doc's Qualificação Obrigatória</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <div class="live-preview">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered align-middle table-nowrap mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" scope="col">ID</th>
                                                                <th scope="col">Documento</th>
                                                                <th class="text-center" scope="col">Data de Conclusão</th>
                                                                <th class="text-center" scope="col">Data de Validade</th>
                                                                <th class="text-center" scope="col">Situação</th>
                                                                <th class="text-center" scope="col">Aprovados?</th>
                                                                <th class="text-center" scope="col">Aprovado por?</th>
                                                                <th class="text-center" scope="col">Data da aprovação</th>
                                                                <th class="text-center" scope="col">Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($qualificacao_funcoes as $qualificacao)
                                                            {{--dd($qualificacao)--}}
                                                                <tr>
                                                                    <td class="text-center">{{ $qualificacao->id }}</td>
                                                                    <td>{{ $qualificacao->qualificacoes->nome_qualificacao ?? 'Sem reg' }}</td>
                                                                    <td class="text-center">
                                                                        @if($qualificacao->data_conclusao)
                                                                            {{ Tratamento::dateBr($qualificacao->data_conclusao) }}
                                                                        @else
                                                                            <div>Aguardando<span class="dot">.</span><span class="dot">.</span><span class="dot">.</span></div>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if($qualificacao->data_validade_doc)
                                                                            {{ Tratamento::dateBr($qualificacao->data_validade_doc) }}
                                                                        @else
                                                                            <div>Aguardando<span class="dot">.</span><span class="dot">.</span><span class="dot">.</span></div>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @php
                                                                            $situacao = $qualificacao->situacao_doc ?? $qualificacao->situacao;
                                                                        @endphp
                
                                                                        @if($situacao == 1)
                                                                            
                                                                            <button type="button" class="btn btn-warning btn-sm btn-label waves-effect waves-light">
                                                                                <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                                                Pendente
                                                                            </button>
                                                                               
                                                                        @elseif($situacao == 2)
                                                                             
                                                                            <button type="button" class="btn btn-success btn-sm btn-label waves-effect waves-light">
                                                                                <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                                                Aprovado
                                                                            </button>
                                                                               
                                                                           
                                                                        @elseif($situacao == 18)
                                                                            
                                                                            <button type="button" class="btn btn-danger btn-sm btn-label waves-effect waves-light">
                                                                                <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                                                Reprovado
                                                                            </button>
                                                                                
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center p-2">
                                                                        @if($qualificacao->nome_arquivo)
                                                                            
                                                                            <div class="@if (
                                                                                            session()->get('usuario_vinculo')->id_nivel == 15 ||
                                                                                            session()->get('usuario_vinculo')->id_nivel == 10) d-block @else d-none @endif">
                                                                                <div class="form-group">
                                                                                    <select class="form-select situacao-select" name="situacao_doc" id="situacao_doc{{ $qualificacao->id_anexos ?? $qualificacao->id }}" data-id="{{ $qualificacao->id_anexos ?? $qualificacao->id }}">
                                                                                        <option value="">Selecione</option>
                                                                                        <option value="1" @if($qualificacao->situacao_doc == 1) selected @endif>Pendente</option>
                                                                                        <option value="2" @if($qualificacao->situacao_doc == 2) selected @endif>Sim</option>
                                                                                        <option value="18" @if($qualificacao->situacao_doc == 18) selected @endif>Não</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                        @else
                                                                            <div>Aguardando documento<span class="dot">.</span><span class="dot">.</span><span class="dot">.</span></div>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($qualificacao->situacao_doc == 2)
                                                                            <span class="btn btn-outline-success waves-effect waves-light material-shadow-none">
                                                                                {{ $qualificacao->usuario_aprov }}
                                                                            </span>
                                                                        @else
                                                                            <span class="btn btn-outline-warning waves-effect waves-light material-shadow-none">
                                                                                Aguardando aprovação
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if($qualificacao->data_aprovacao)
                                                                            {{ Tratamento::datetimeBr($qualificacao->data_aprovacao) }}
                                                                        @else
                                                                            Não aprovado
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        
                                                                        @if($qualificacao->situacao_doc == 2)
                                                                        <a href="{{ route('cadastro.funcionario.download', $qualificacao->id_anexos) }}">
                                                                            <button type="button" class="btn btn-outline-success btn-sm waves-effect waves-light material-shadow-none">
                                                                                <i class="mdi mdi-cloud-download mdi-18px"></i>
                                                                            </button>
                                                                        </a>
                                                                        @else
                                                                        
                                                                            <button data-id="{{$qualificacao->id_anexos}}" type="button" class="sa-basic btn btn-outline-warning btn-sm waves-effect waves-light material-shadow-none">
                                                                                    <i class="mdi mdi-cloud-download mdi-18px"></i> 
                                                                            </button>
                                                                          
                                                                        
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div><!-- end card-body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                            </div><!--end row-->
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end tab-pane-->
                
                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="col-sm-12 col-xl-12 ">
                        <div class="card border">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Documentos Gerais</h4>
                                <div class="d-flex form-group">
                                    <label id="botoes">Adicionar</label>
                                    <a data-bs-toggle="modal" data-bs-target="#myModal">
                                        <span class="bg-primary text-white py-1 px-2 rounded mx-2"><i class="mdi mdi-plus"></i></span>
                                    </a>
                                    <a class="listar-ativos-remover" id="listar-ativos-remover">
                                        <span class="bg-warning text-white py-1 px-2 rounded"><i class="mdi mdi-minus"></i></span>
                                    </a>
                                </div>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle table-nowrap mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Documento</th>
                                                    <th class="text-center" scope="col">Data de cadastro</th>
                                                    <th class="text-center" scope="col">Cadastrado por?</th>
                                                    <th class="text-center" scope="col">Liberados por?</th>
                                                    <th class="text-center" scope="col">Liberados?</th>
                                                    <th class="text-center" scope="col">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($anexos_funcionarios as $anexos_funcionario)
                                                    <tr>
                                                        <td class="p-2">{{ $anexos_funcionario->nome_arquivo }}</td>
                                                        <td class="text-center p-2">{{ Tratamento::FormatarData($anexos_funcionario->created_at) }}</td>
                                                        <td class="text-center">
                                                            <span class="btn btn-outline-info waves-effect waves-light material-shadow-none">
                                                                {{ $anexos_funcionario->usuario_cad }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($anexos_funcionario->situacao_doc == 2)
                                                                <span class="btn btn-outline-success waves-effect waves-light material-shadow-none">
                                                                    {{ $anexos_funcionario->usuario_aprov }}
                                                                </span>
                                                            @else
                                                                <span class="btn btn-outline-warning waves-effect waves-light material-shadow-none">
                                                                    Aguardando aprovação da equipe de Segurança do Trabalho
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center p-2">
                                                            <select class="form-select" name="situacao_doc[]" id="checkbox" data-id="{{ $anexos_funcionario->id_funcionario }}">
                                                                @if ($anexos_funcionario->situacao_doc == 18)
                                                                    <option selected value="{{ $anexos_funcionario->situacao_doc }}">Não</option>
                                                                    <option value="2">Sim</option>
                                                                @else
                                                                    <option class="bg-success p-1 rounded shadow text-white" selected>Sim</option>
                                                                @endif
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('cadastro.funcionario.download', $anexos_funcionario->id) }}">
                                                                <button type="button" class="btn btn-outline-success btn-sm waves-effect waves-light material-shadow-none">
                                                                    <i class="mdi mdi-cloud-download mdi-18px"></i>
                                                                </button>
                                                            </a>
                                                            <a href="{{ route('cadastro.funcionario.excluir_anexos_funcionarios', $anexos_funcionario->id) }}?modulo=show_funcionario" id="deletar">
                                                                <span class="btn btn-outline-danger btn-sm waves-effect waves-light material-shadow-none" title="Excluir arquivo"><i class="mdi mdi-delete mdi-18px"></i></span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!--end tab-pane-->

                <div class="tab-pane fade" id="dados_acesso" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mt-4">
                                <div class="col-sm-12 col-xl-12">
                                    <div class="card border">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="card-title mb-0 flex-grow-1">Alterar dados de acesso</h4>
                                                    <span id="message"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-beetwen my-5">
                                            <div class="col-sm-12 col-md-8 col-lg-6 col-xl-5">
                                                <div class="card border mx-sm-1 mx-md-2 mx-lg-5 mx-xxl-5">
                                                    <div class="card-body p-4">
                                                        <div class="p-2">
                                                            <form class="form-horizontal" id="update_password" method="POST">
                                                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                                                <input type="hidden" name="updatePassword" id="updatePassword" value="true">
                                                                <div class="mb-3">
                                                                    <label for="useremail" class="form-label">Email</label>
                                                                    <input type="email" class="form-control bg-light" name="email" value="{{ Auth::user()->email }}" id="email" readonly title="Campo bloqueado para edição!!!">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="userpassword">Nova senha</label>
                                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="userpassword" placeholder="Enter password">
                                                                    @error('password')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="userpassword">Confirmar nova senha</label>
                                                                    <input id="password-confirm" type="password" name="password_confirmation" class="form-control">
                                                                </div>
                                                                <div class="text-end">
                                                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Reset</button>
                                                                </div>
                                                            </form><!-- end form -->
                                                        </div>
                                                    </div><!-- end card body -->
                                                </div><!-- end card -->
                                            </div>
                                            <div class="col-sm-12 col-lg-3 col-xl-5 mx-sm-1 mx-md-2 mx-lg-5 mx-xxl-5">
                                                <ul class="list-group">
                                                    <li class="list-group-item disabled text-center" aria-disabled="true">Requisitos para a senha</li>
                                                    <li class="list-group-item">Não pode ser NÚMEROS sequenciais. <span class="text-danger">Ex.: 123456789...</span> </li>
                                                    <li class="list-group-item">Não pode ser mais que dois NÚMEROS sequenciais. <span class="text-danger">Ex.: 123...</span> </li>
                                                    <li class="list-group-item">Não pode ser NÚMEROS REPETIDOS. <span class="text-danger">Ex.: 111111...</span> </li>
                                                    <li class="list-group-item">Não pode ser MAIS QUE DOIS NÚMEROS REPETIDOS. <span class="text-danger">Ex.: 111...</span> </li>
                                                    <li class="list-group-item">Não pode ser LETRAS sequenciais. <span class="text-danger">Ex.: abcdef... ou ABCDEF... ou AbCdef</span></li>
                                                    <li class="list-group-item">No minimo uma letra MAÍSCULA.</li>
                                                    <li class="list-group-item">No minimo uma letra MÍNUSCULA.</li>
                                                    <li class="list-group-item">No minimo 8 (oito caracteres). </li>
                                                    <li class="list-group-item">Senha e Confirmação da senha tem que ser iguais.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div><!-- end card -->
                                </div><!-- end col -->
                            </div><!--end row-->
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end tab-pane-->

                <div class="tab-pane fade" id="retirada_estoque" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mt-4">
                                <div class="col-sm-12 col-xl-12">
                                    <div class="card border">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="card-title mb-0 flex-grow-1">Senha para retirada de itens do Estoque</h4>
                                                    <span id="message"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-beetwen my-5">
                                            <div class="col-sm-12 col-md-8 col-lg-3 col-xl-3">
                                                <div class="card border mx-sm-1 mx-md-2 mx-lg-2 mx-xxl-2">
                                                    <div class="card-body p-4">
                                                        @if ($store->password == null)
                                                            <div class="text-center">
                                                                <button class="btn btn-secondary waves-effect waves-light" id="cad_edit_senha" type="button">Cadastrar senha</button>
                                                            </div>
                                                        @else
                                                            <div class="text-center">
                                                                <button class="btn btn-primary w-md waves-effect waves-light" id="cad_edit_senha" type="button">Alterar senha</button>
                                                            </div>
                                                        @endif
                                                    </div><!-- end card body -->
                                                </div><!-- end card -->
                                            </div>
                                            <div class="col-sm-12 col-md-8 col-lg-9 col-xl-8">
                                                <!-- Accordions Bordered -->
                                                <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-secondary" id="accordionBordered">
                                                    <div class="accordion-item material-shadow">
                                                        <h2 class="accordion-header" id="accordionborderedExample1">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse1" aria-expanded="false" aria-controls="accor_borderedExamplecollapse1">
                                                                Ferramentas
                                                            </button>
                                                        </h2>
                                                        <div id="accor_borderedExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionborderedExample1" data-bs-parent="#accordionBordered">
                                                            <div class="accordion-body">
                                                                <table class="table table-bordered table-sm align-middle table-nowrap mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center" scope="col">Patrimônio</th>
                                                                            <th class="text-center" scope="col">Ferramenta</th>
                                                                            <th class="text-center" scope="col">Data Retirada</th>
                                                                            <th class="text-center" scope="col">Data de Devolução</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($itensRetirados as $itemRetirado)
                                                                            <tr>
                                                                                <td class="text-center">{{ $itemRetirado->patrimonio }}</td>
                                                                                <td>{{ $itemRetirado->nome_ferramenta }}</td>
                                                                                <td class="text-center">{{ Tratamento::datetimeBr($itemRetirado->dataRetirada) ?? 'Sem reg.' }}</td>
                                                                                <td class="text-center">{{ Tratamento::datetimeBr($itemRetirado->data_devolucao) }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item mt-2 material-shadow">
                                                        <h2 class="accordion-header" id="accordionborderedExample2">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse2" aria-expanded="false" aria-controls="accor_borderedExamplecollapse2">
                                                                Estoque
                                                            </button>
                                                        </h2>
                                                        <div id="accor_borderedExamplecollapse2" class="accordion-collapse collapse" aria-labelledby="accordionborderedExample2" data-bs-parent="#accordionBordered">
                                                            <div class="accordion-body">
                                                                <table class="table table-nowrap">
                                                                    <thead>
                                                                        <tr>
                                                                            <th scope="col">ID</th>
                                                                            <th scope="col">Nome do item</th>
                                                                            <th scope="col">Data da retirada</th>
                                                                            <th scope="col">Data da devolução</th>
                                                                            <th scope="col">Ações</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <th scope="row"><a href="#" class="fw-semibold">#VZ2110</a></th>
                                                                            <td>Bobby Davis</td>
                                                                            <td>October 15, 2021</td>
                                                                            <td>$2,300</td>
                                                                            <td><a href="javascript:void(0);" class="link-success">View More <i class="ri-arrow-right-line align-middle"></i></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row"><a href="#" class="fw-semibold">#VZ2109</a></th>
                                                                            <td>Christopher Neal</td>
                                                                            <td>October 7, 2021</td>
                                                                            <td>$5,500</td>
                                                                            <td><a href="javascript:void(0);" class="link-success">View More <i class="ri-arrow-right-line align-middle"></i></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row"><a href="#" class="fw-semibold">#VZ2108</a></th>
                                                                            <td>Monkey Karry</td>
                                                                            <td>October 5, 2021</td>
                                                                            <td>$2,420</td>
                                                                            <td><a href="javascript:void(0);" class="link-success">View More <i class="ri-arrow-right-line align-middle"></i></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row"><a href="#" class="fw-semibold">#VZ2107</a></th>
                                                                            <td>James White</td>
                                                                            <td>October 2, 2021</td>
                                                                            <td>$7,452</td>
                                                                            <td><a href="javascript:void(0);" class="link-success">View More <i class="ri-arrow-right-line align-middle"></i></a></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item mt-2 material-shadow">
                                                        <h2 class="accordion-header" id="accordionborderedExample3">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse3" aria-expanded="false" aria-controls="accor_borderedExamplecollapse3">
                                                                EPI's
                                                            </button>
                                                        </h2>
                                                        <div id="accor_borderedExamplecollapse3" class="accordion-collapse collapse" aria-labelledby="accordionborderedExample3" data-bs-parent="#accordionBordered">
                                                            <div class="accordion-body">
                                                                <table class="table table-nowrap">
                                                                    <thead>
                                                                        <tr>
                                                                            <th scope="col">ID</th>
                                                                            <th scope="col">Nome do item</th>
                                                                            <th scope="col">Data da retirada</th>
                                                                            <th scope="col">Data da devolução</th>
                                                                            <th scope="col">Ações</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <th scope="row"><a href="#" class="fw-semibold">#VZ2110</a></th>
                                                                            <td>Bobby Davis</td>
                                                                            <td>October 15, 2021</td>
                                                                            <td>$2,300</td>
                                                                            <td><a href="javascript:void(0);" class="link-success">View More <i class="ri-arrow-right-line align-middle"></i></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row"><a href="#" class="fw-semibold">#VZ2109</a></th>
                                                                            <td>Christopher Neal</td>
                                                                            <td>October 7, 2021</td>
                                                                            <td>$5,500</td>
                                                                            <td><a href="javascript:void(0);" class="link-success">View More <i class="ri-arrow-right-line align-middle"></i></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row"><a href="#" class="fw-semibold">#VZ2108</a></th>
                                                                            <td>Monkey Karry</td>
                                                                            <td>October 5, 2021</td>
                                                                            <td>$2,420</td>
                                                                            <td><a href="javascript:void(0);" class="link-success">View More <i class="ri-arrow-right-line align-middle"></i></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row"><a href="#" class="fw-semibold">#VZ2107</a></th>
                                                                            <td>James White</td>
                                                                            <td>October 2, 2021</td>
                                                                            <td>$7,452</td>
                                                                            <td><a href="javascript:void(0);" class="link-success">View More <i class="ri-arrow-right-line align-middle"></i></a></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end row -->
                                        </div><!-- end card -->
                                    </div><!-- end col -->
                                </div><!--end row-->
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end tab-pane-->
                </div><!--end tab-content-->
            </div>
        </div><!--end col-->
    </div><!--end row-->

    <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Adicionar Documentos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="fs-15">
                        <div class="d-flex justify-content-end form-group">
                            <label id="botoes">Ações</label>
                            <a class="listar-ativos-adicionar" id="listar-ativos-adicionar">
                                <span class="bg-primary text-white py-1 px-2 rounded mx-2"><i class="mdi mdi-plus"></i></span>
                            </a>
                            <a class="listar-ativos-remover" id="listar-ativos-remover">
                                <span class="bg-warning text-white py-1 px-2 rounded"><i class="mdi mdi-minus"></i></span>
                            </a>
                        </div>
                    </h5>

                    <hr>
                    <form method="post" action="{{ route('cadastro.funcionario.funcoes.adicionar_anexos_funcionarios') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- id do funcionario usando id na que esta na url-->
                        <input type="hidden" name="id_funcionario" value="{{ basename(parse_url(url()->current(), PHP_URL_PATH)) }}">
                        <input type="hidden" name="modulo" value="show_funcionario">
                        <input type="hidden" name="id_funcao" value="{{ @$store->id_funcao }}">
                        <div id="listar-ativos-linha"></div>
                        <template id="listar-ativos-template">
                            <div class="row template-row mt-4">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="id_ativo_externo">Documento</label>
                                        <input type="text" class="form-control" name="nome_qualificacao[]">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="id_ativo_externo">Data do documento</label>
                                        <input type="date" class="form-control" name="data_conclusao[]" id="data_conclusao">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="quantidade">Arquivo(s)</label>
                                        <input class="form-control" type="file" id="file" name="file[]" multiple="">
                                    </div>
                                </div>
                                <div class="col-1 p-0">
                                    <div class="form-group">
                                        <label for="SwitchCheck11">Aprovado? </label>
                                        <select class="form-select" name="situacao_doc[]" id="situacao_doc">
                                            <option selected value="18">Não</option>
                                            <option value="2">Sim</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <hr>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>

    <div class="modal fade" id="motivoReprovacaoModal" tabindex="-1" aria-labelledby="motivoReprovacaoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="motivoReprovacaoModalLabel">Motivo da Reprovação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Histórico</h6>
                    <div id="motivoReprovacaoExistente"></div>
                    <textarea id="motivoReprovacao" class="form-control" rows="12" placeholder="Digite o motivo da reprovação aqui..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="enviarMotivo">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/jquery-migrate-3.4.0.min.js"></script>
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
    
        // Use a classe '.sa-basic' para associar o evento de clique a todos os botões
        $(document).on('click', '.sa-basic', function() {
            var id = $(this).data('id');
            
            // Chame o seu Swal.fire
            Swal.fire({
                title: 'O registro não possui documento anexado',
                customClass: {
                    confirmButton: 'btn btn-success w-xs mt-2'
                },
                icon: "warning",
                buttonsStyling: false,
                showCloseButton: true
            });
        });
    

        document.addEventListener('DOMContentLoaded', () => {
            const cadEditSenhaButton = document.getElementById('cad_edit_senha');

            if (cadEditSenhaButton) {
                cadEditSenhaButton.addEventListener('click', () => {
                    var id_funcionario_alterar_senha = "{{ @$store->id }}";
                    var password = "{{ @$store->password }}";
                    var nome_funcionario = "{{ @$store->funcionario }}";
                    var url_alterar_senha_termo = "{{ route('cadastro.funcionario.cad_edi_password_func', ['id' => ':id']) }}";
                    url_alterar_senha_termo = url_alterar_senha_termo.replace(':id', id_funcionario_alterar_senha);

                    var campo_cpf;
                    var mensagem;

                    if (password == "") {
                        campo_cpf = "<input type='hidden' id='cpf' class='swal2-input' >";
                        mensagem = "<p>Você está cadastrando uma senha para poder fazer <strong><u>uso de nossas ferramentas, materiais e EPI's</u></strong>, mediante a liberação pelo responsável do Almoxarifado. A sua senha não deve ser repassada a terceiros, pois ela será usada para a retirada ferramentas e materiais em seu nome.</p>";
                    } else {
                        campo_cpf = "<input type='text' id='cpf' class='swal2-input' placeholder='Insira o seu CPF'>";
                        mensagem = "<p><strong>É obrigatório inserir o seu CPF completo para alterar a senha.</strong></p><p>Você está alterando a sua senha. Ela será usada para a retirada de ferramentas e materiais, mediante a liberação pelo responsável do Almoxarifado. A sua senha não deve ser repassada a terceiros, pois ela será usada para a retirada ferramentas e materiais em seu nome.</p>";
                    }

                    Swal.fire({
                        title: "<p>Seja bem vindo </p> <p>" + nome_funcionario + "</p> <p> ao SGA-Engeativos</p>",
                        html: mensagem + `
                    <input type="password" id="password" class="swal2-input" placeholder="Insira a senha">
                    ` + campo_cpf,
                        icon: "success",
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: "Cadastrar",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            const passwordInput = document.getElementById('password').value;
                            const cpfInput = document.getElementById('cpf') ? document.getElementById('cpf').value : '';

                            if (!passwordInput) {
                                Swal.showValidationMessage('Por favor, insira a senha');
                                return false;
                            }

                            if (password !== "" && !cpfInput) {
                                Swal.showValidationMessage('Por favor, insira o CPF');
                                return false;
                            }

                            return {
                                password: passwordInput,
                                cpf: cpfInput
                            };
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            (async () => {
                                try {
                                    const response = await fetch(url_alterar_senha_termo, {
                                        method: "POST",
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify({
                                            password: result.value.password,
                                            cpf: result.value.cpf,
                                        })
                                    });
                                    if (!response.ok) {
                                        throw new Error('Request failed with status ' + response.status);
                                    }

                                    const data = await response.json();

                                    if (data.icon == "warning") {
                                        var tipo_alert = "warning"
                                    } else {
                                        var tipo_alert = "success"
                                    }

                                    console.log(data.icon)

                                    Swal.fire({
                                        title: 'Sucesso!',
                                        text: data.mensagem, // Exibe a mensagem retornada pelo servidor
                                        icon: tipo_alert
                                    });

                                } catch (error) {
                                    Swal.showValidationMessage(`Request failed: ${error.message}`);
                                }
                            })();
                        }
                    });
                });
            }
        });

        $(document).ready(function() {
            var motivoReprovacaoModal = new bootstrap.Modal(document.getElementById('motivoReprovacaoModal'), { keyboard: false });
            var currentId = null;
            var currentSelectValue = null;

            $('.situacao-select').change(function() {
                var id = $(this).data('id');
                var selectValue = $(this).val();

                if (selectValue == 18) {
                    // Armazenar o ID e o valor selecionado
                    currentId = id;
                    currentSelectValue = selectValue;

                    // Fazer uma solicitação AJAX para obter o motivo existente
                    $.ajax({
                        url: "{{ route('cadastro.funcionario.obter_motivo', ['id' => ':id']) }}".replace(':id', id),
                        method: 'get',
                        success: function(data) {
                            // Exibir o motivo existente na modal
                            $('#motivoReprovacaoExistente').html(data.motivoReprovacao);
                            // Abrir o modal para solicitar o motivo da reprovação
                            motivoReprovacaoModal.show();
                        },
                        error: function(xhr, status, error) {
                            var errorMsg = "Ocorreu um erro ao obter o motivo da reprovação. ";
                            if (xhr.status && xhr.responseText) {
                                errorMsg += " Código do erro: " + xhr.status + ". Mensagem: " + xhr.responseText;
                            } else {
                                errorMsg += " Status: " + status + ". Erro: " + error;
                            }
                            console.log(errorMsg);
                        }
                    });
                } else {
                    // Enviar a solicitação AJAX sem o motivo da reprovação
                    enviarSolicitacao(id, selectValue);
                }

                $(this).data('previous', selectValue); // Armazenar a seleção anterior
            });

            $('#enviarMotivo').click(function() {
                var motivoReprovacao = $('#motivoReprovacao').val();
                if (motivoReprovacao) {
                    // Enviar a solicitação AJAX com o motivo da reprovação
                    enviarSolicitacao(currentId, currentSelectValue, motivoReprovacao);
                    // Fechar o modal
                    motivoReprovacaoModal.hide();
                } else {
                    alert('Por favor, preencha o motivo da reprovação.');
                }
            });

            function enviarSolicitacao(id, selectValue, motivoReprovacao = '') {
                var url = "{{ route('cadastro.funcionario.aprovar_documentos', ['id' => ':id']) }}";
                url = url.replace(':id', id);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: url,
                    method: 'post',
                    data: {
                        id: id,
                        selectValue: selectValue,
                        motivoReprovacao: motivoReprovacao
                    },
                    success: function(data) {
                        var documento = data[0];
                        var notification = data[1];

                        toastr[notification.type](notification.message);

                        // Atualizar o select específico
                        setTimeout(function() {
                            $('#situacao-select_' + id).html(`
                                <select class="form-select situacao-select" style="opacity: 0.8; background-color: chartreuse;" disabled="disabled" readonly="true">
                                    <option value="${documento.situacao_doc}">${documento.situacao_doc == 2 ? 'Sim' : 'Não'}</option>
                                </select>
                            `);

                            // Recarregar a página após a atualização do select
                            setTimeout(function() {
                                window.location.hash = 'projects';
                                location.reload();
                            }, 2000);
                        }, 500);
                    },
                    error: function(xhr, status, error) {
                        var errorMsg = "Ocorreu um erro na solicitação. ";
                        if (xhr.status && xhr.responseText) {
                            errorMsg += " Código do erro: " + xhr.status + ". Mensagem: " + xhr.responseText;
                            console.log(errorMsg);
                        } else {
                            errorMsg += " Status: " + status + ". Erro: " + error;
                            console.log(errorMsg);
                        }
                        $('#message').empty().removeClass().text("Ocorreu um erro na solicitação.").addClass('text-danger');
                    }
                });
            }

            // Verificar se há um hash na URL ao carregar a página
            if (window.location.hash) {
                var hash = window.location.hash;
                if ($(hash).length) {
                    // Ativar a aba correspondente
                    $('a[href="' + hash + '"]').tab('show');

                    // Rolagem suave para a div com id especificado no hash
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 1000);
                }
            }
        });

        $('#update_password').off('submit').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário
            $('#message').val("");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('updatePassword', Auth::user()->id) }}", // URL para onde o formulário será enviado
                type: "POST", // Método HTTP
                data: $(this).serialize(), // Serializa os dados do formulário
                success: function(response) {
                    // Antes de configurar a nova mensagem, limpa o conteúdo anterior e remove todas as classes
                    $('#message').empty().removeClass();
                    // Manipula a resposta em caso de sucesso
                    if (response.type == "success") {
                        toastr[response.type](response.message)
                    } else {
                        $('#message').text(response.Message).removeClass('text-success').addClass('text-danger');
                    }
                },
                error: function(xhr, status, error) {
                    // Manipula erros de solicitação (ex.: problemas de conexão)
                    $('#message').text("As senhas não são iguais!!!").addClass('text-danger');
                }
            });
        });
    </script>
@endsection