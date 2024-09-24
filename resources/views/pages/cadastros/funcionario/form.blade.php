@extends('dashboard')
@section('title', 'Funcionários')
@section('content')


    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ops!</strong><br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <style>
        #divPiscante {
            animation: piscar 1.5s infinite;
        }

        @keyframes piscar {
            0% {
                background-color: red;
                color: white;
                padding-top: 5px;
                padding-bottom: 5px;
            }

            50% {
                background-color: yellow;
                color: white;
                padding-top: 5px;
                padding-bottom: 5px;
            }

            100% {
                background-color: red;
                color: white;
                padding-top: 5px;
                padding-bottom: 5px;
            }
        }
    </style>

    @php
        $action = isset($store)
            ? route('cadastro.funcionario.update', $store->id)
            : route('cadastro.funcionario.store');
    @endphp


    <form method="post" enctype="multipart/form-data" action="{{ $action }}">
        @csrf

        <div class="position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg profile-setting-img">

                <div class="overlay-content">
                    <div class="text-end p-3">
                        <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                            <input id="profile-foreground-img-file-input" type="file"
                                class="profile-foreground-img-file-input">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="position:relative; top:-70px !important">
            <div class="col-xxl-2">
                <div class="card mt-n5">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <div class="profile-user position-relative d-inline-block mx-auto  mb-4">

                                @if (optional(@$store)->imagem_usuario)
                                    <img src="{{ asset('build/images/users') }}/{{ $store->id }}/{{ $store->imagem_usuario }}"
                                        id="target"
                                        class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow"
                                        alt="Imagem do usuário">
                                @else
                                    <img src="{{ asset('imagens/usuarios/lista-de-usuarios.png') }}" id="target"
                                        class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow"
                                        alt="Imagem do usuário">
                                @endif


                                <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                    <input id="profile-img-file-input" type="file" name="imagem_usuario"
                                        class="profile-img-file-input" onChange="carregarImg()">
                                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                        <span class="avatar-title rounded-circle bg-light text-body material-shadow">
                                            <i class="ri-camera-fill"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <h5 class="fs-16 mb-1">Foto do Perfil <span class="text-danger"
                                    title="Campo obrigatório">*</span></h5>
                        </div>
                    </div>
                </div>
                <!--end card-->

                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-xxl-10">
                <div class="card mt-xxl-n5">
                    <div class="card-header">
                        <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">

                                    <span class="bg-success p-2 px-4 text-white rounded" title="Campo obrigatório">Dados
                                        pessoais</span> <span class="text-danger" title="Campo obrigatório">*</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                    <span class="bg-warning p-2 text-white rounded" title="Campo obrigatório">Funções e
                                        Documentos </span> <span class="text-danger" title="Campo obrigatório">*</span>
                                </a>
                            </li>
                            <!--  <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#experience" role="tab">
                                                        <i class="far fa-envelope"></i> Documentos
                                                    </a>
                                                </li> -->

                        </ul>
                    </div>

                    {{-- dd(session()->get('usuario_vinculo')->id_nivel) --}}

                    <div class="card-body p-4">
                        <div class="tab-content">
                            <div class="tab-pane active" id="personalDetails" role="tabpanel">

                                <div class="row">


                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label" for="matricula">Matrícula</label><span
                                                class="text-danger" title="Campo obrigatório">*</span>
                                            <input class="form-control" id="matricula" name="matricula" type="text"
                                                value="{{ old('matricula', @$store->matricula) }}">

                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label" for="id_obra">Obra</label>
                                            <select class="form-select select2" id="id_obra" name="id_obra">

                                                @if (url()->current() == route('cadastro.funcionario.adicionar'))
                                                    <option value="">Selecione uma Obra</option>

                                                     @if (session()->get('usuario_vinculo')->id_nivel == 1 
                                                        or session()->get('usuario_vinculo')->id_nivel == 15
                                                        or session()->get('usuario_vinculo')->id_nivel == 10)
                                                        @foreach ($obras as $obra)
                                                            <option value="{{ $obra->id }}">
                                                                {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                                            </option>
                                                        @endforeach
                                                    @elseif (session()->get('usuario_vinculo')->id_nivel == 2)
                                                        <option value="{{ session()->get('obra')->id }}" readonly>
                                                            {{ session()->get('obra')->codigo_obra }} |
                                                            {{ session()->get('obra')->razao_social }}
                                                        </option>
                                                    @endif
                                                @else
                                                    @if (session()->get('usuario_vinculo')->id_nivel <= 2 or
                                                            session()->get('usuario_vinculo')->id_nivel == 10 or
                                                            session()->get('usuario_vinculo')->id_nivel == 15 or
                                                            session()->get('usuario_vinculo')->id_nivel == 14)


                                                        @foreach ($obras as $obra)
                                                            <option value="{{ $obra->id }}"
                                                                {{ @$store->id_obra == $obra->id ? 'selected' : '' }}>
                                                                {{ $obra->codigo_obra }} | {{ $obra->razao_social }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option value="{{ session()->get('obra')->id }}" readonly>
                                                            {{ session()->get('obra')->codigo_obra }} |
                                                            {{ session()->get('obra')->razao_social }}
                                                        </option>

                                                    @endif


                                                @endif


                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label" for="estado_civil">Setor</label>
                                            <select class="form-control" id="id_setor" name="id_setor">
                                                <option value="">Selecione um setor</option>
                                                    @foreach($setores as $setor)
                                                        <option value="{{ $setor->id }}" {{ (isset($store) && $store->id_setor == $setor->id ? 'selected' : '') }}>
                                                            {{ $setor->nome_setor }}
                                                        </option>
                                                    @endforeach
                                            </select>                                            
                                        </div>
                                    </div>


                                    <div class="row mt-3">

                                        <div class="col-md-12">
                                            <label class="form-label" for="nome">Nome Completo</label>
                                            <input class="form-control" name="nome" type="text"
                                                value="{{ old('nome', @$store->nome) }}">
                                        </div>

                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="form-label" for="rg">Registro Geral (RG)</label>
                                            <input class="form-control" id="rg" name="rg" type="text"
                                                value="{{ old('rg', @$store->rg) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="cpf">CPF</label>
                                            <input class="form-control cpf" id="cpf" name="cpf" type="text"
                                                value="{{ old('cpf', @$store->cpf) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="email">E-mail</label>
                                            <input class="form-control" id="email" name="email" type="email"
                                                value="{{ old('email', @$store->email) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="celular">Celular / WhatsApp</label>
                                            <input class="form-control celular" id="celular" name="celular"
                                                type="text" value="{{ old('celular', @$store->celular) }}">
                                        </div>
                                    </div>


                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="form-label" for="nome_mae">Nome da Mãe</label>
                                            <input class="form-control" id="nome_mae" name="nome_mae" type="text"
                                                value="{{ old('nome_mae', @$store->nome_mae) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="pis">Número de PIS</label>
                                            <input class="form-control " id="pis" name="pis" type="text"
                                                value="{{ old('pis', @$store->pis) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="estado_civil">Estado Civil</label>
                                            <select class="form-control" id="estado_civil" name="estado_civil">

                                                @if (@$store->estado_civil)

                                                    @if (@$store->estado_civil == 'casado')
                                                        <option value="{{ @$store->estado_civil }}" selected>Casado
                                                        </option>
                                                    @elseif(@$store->estado_civil == 'solteiro')
                                                        <option value="{{ @$store->estado_civil }}" selected>Solteiro
                                                        </option>
                                                    @elseif(@$store->estado_civil == 'divorciado')
                                                        <option value="{{ @$store->estado_civil }}" selected>Divorciado
                                                        </option>
                                                    @elseif(@$store->estado_civil == 'outros')
                                                        <option value="{{ @$store->estado_civil }}" selected>Outros
                                                        </option>
                                                    @endif
                                                @else
                                                    <option value="">Selecione uma opção</option>
                                                    <option value="casado">Casado</option>
                                                    <option value="solteiro">Solteiro</option>
                                                    <option value="divorciado">Divorciado</option>
                                                    <option value="outros">Outros</option>

                                                @endif

                                            </select>

                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="dependentes">Dependentes ( Filhos, Cônjuge,
                                                etc. );</label>
                                            <textarea class="form-control celular" id="dependentes" name="dependentes" rows="5"
                                                value="{{ old('dependentes', @$store->dependentes) }}">{{ @$store->dependentes }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mt-3">

                                        <div class="col-md-3">
                                            <label class="form-label" for="data_nascimento">Data de Nascimento</label>
                                            <input class="form-control" id="data_nascimento" name="data_nascimento"
                                                type="date"
                                                value="{{ old('data_nascimento', @$store->data_nascimento) }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="data_nascimento">Data de Admissão</label>
                                            <input class="form-control" id="data_adminssao" name="data_adminssao"
                                                type="date"
                                                value="{{ old('data_adminssao', @$store->data_adminssao) }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="data_nascimento">Data de Demissão</label>
                                            <input class="form-control" id="data_demissao" name="data_demissao"
                                                type="date"
                                                value="{{ old('data_demissao', @$store->data_demissao) }}">
                                        </div>

                                    </div>


                                    <div class="page-header mt-4 mb-1">
                                        <h5 class="page-title">
                                            </span> Endereço
                                        </h5>

                                    </div>

                                    <hr class="bg-warning mt-1 mb-3">


                                    <div class="row mt-5">
                                        <div class="col-md-2">
                                            <label class="form-label" for="cep">CEP</label>
                                            <input class="form-control cep" id="cep" name="cep" type="text"
                                                value="{{ old('cep', @$store->cep) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="endereco">Endereço</label>
                                            <input class="form-control" id="endereco" name="endereco" type="text"
                                                value="{{ old('endereco', @$store->endereco) }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label" for="numero">Número</label>
                                            <input class="form-control" id="numero" name="numero" type="text"
                                                value="{{ old('numero', @$store->numero) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="bairro">Bairro</label>
                                            <input class="form-control" id="bairro" name="bairro" type="text"
                                                value="{{ old('bairro', @$store->bairro) }}">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="form-label" for="cidade">Cidade</label>
                                            <input class="form-control" id="cidade" name="cidade" type="text"
                                                value="{{ old('cidade', @$store->cidade) }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="estado">Estado</label>
                                            <select class="form-select" id="estado" name="estado">
                                                <option value="">Selecione o Estado</option>
                                                @if (url()->current() == route('cadastro.funcionario.adicionar'))
                                                    @foreach ($estados as $sigla => $estado)
                                                        <option value="{{ $sigla }}"
                                                            {{ old('estado') == $sigla ? 'selected' : '' }}>
                                                            {{ $estado }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach ($estados as $sigla => $estado)
                                                        <option value="{{ $sigla }}"
                                                            @php if(@$store->estado==$sigla) echo 'selected' @endphp>
                                                            {{ $estado }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="status">Status</label>
                                            <select class="form-select" name="status">
                                                <option value="">Selecione os Status</option>
                                                <option value="Ativo"
                                                    @php if(@$store->status=="Ativo") echo 'selected' @endphp>Ativo
                                                </option>
                                                <option value="Inativo"
                                                    @php if(@$store->status=="Inativo") echo 'selected' @endphp>
                                                    Inativo</option>
                                            </select>
                                        </div>
                                    </div>



                                    <!--end col-->
                                </div>
                                <!--end row-->

                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" id="changePassword" role="tabpanel">

                                <div class="col-md-6">
                                    <label class="form-label" for="id_funcao">Função <span class="text-danger"
                                            title="Campo obrigatório">*</span></label>
                                    <select name="id_funcao" id="id_funcao" class="form-control" required>
                                        
                                         @if (url()->current() == route('cadastro.funcionario.adicionar'))
                                         
                                         <option value="">Selecionar</option>
                                         
                                          @foreach ($funcoes as $funcao)
                                            <option value="{{ $funcao->id }}" >
                                                {{ $funcao->funcao }}
                                            </option>
                                        @endforeach
                                        
                                        @else

                                        @foreach ($funcoes as $funcao)
                                            <option value="{{ $funcao->id }}"
                                                {{ isset($store) && $store->id_funcao == $funcao->id ? 'selected' : '' }}>
                                                {{ $funcao->funcao }}
                                            </option>
                                        @endforeach
                                        
                                        @endif
                                        
                                    </select>
                                </div>

                                @if (url()->current() == route('cadastro.funcionario.adicionar'))

                                    {{-- dd("estou ADCIONANDO aqui")- --}}

                                    <div class="row mt-3" id="qualificacao_nao_obrigatoria">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="id_ativo_externo">Documento</label>
                                                <input type="text" class="form-control" name="nome_qualificacao[]"
                                                    id="nome_qualificacao">
                                                <input type="hidden" class="form-control" value="0"
                                                    name="id_qualificacao[]">
                                                <input type="hidden" class="form-control" value="0"
                                                    name="tempo_validade[]">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label for="id_ativo_externo">Data do documento</label>
                                                <input type="date" class="form-control" name="data_conclusao[]"
                                                    id="data_conclusao">
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="quantidade">Arquivo(s)</label>
                                                <input class="form-control" type="file" id="file" name="file[]"
                                                    multiple="">
                                            </div>
                                        </div>

                                        <div class="col-1 p-0  @if (session()->get('usuario_vinculo')->id_nivel == 1 
                                                            or session()->get('usuario_vinculo')->id_nivel == 15
                                                            or session()->get('usuario_vinculo')->id_nivel == 10)
                                                                d-block
                                                        @else                                                    
                                                                d-none
                                                        @endif">

                                            <div class="form-group">
                                                <label for="SwitchCheck11">Aprovado? </label>
                                                <select class="form-select situacao-select" name="situacao_doc[]"
                                                    id="situacao_doc">

                                                    <option selected value="">Selececionar</option>
                                                    <option value="18" {{-- @if (session()->get('usuario_vinculo')->id_nivel <= 2 or session()->get('usuario_vinculo')->id_nivel == 14) @else style="color: red;" @endif --}}>Não</option>
                                                    <option value="2">Sim</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-2 ">
                                            <div class="form-group">
                                                <label id="botoes">Ações</label>
                                                <div id="botoes">
                                                    <a class="btn btn-warning listar-ativos-adicionar"
                                                        id="listar-ativos-adicionar"><i class="mdi mdi-plus"></i></a>
                                                    <a class="btn btn-primary listar-ativos-remover"
                                                        id="listar-ativos-remover"><i class="mdi mdi-minus"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="listar-ativos-linha"></div>

                                    <template id="listar-ativos-template">
                                        <div class="row template-row mt-4">
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label for="id_ativo_externo">Documento</label>
                                                    <input type="text" class="form-control"
                                                        name="nome_qualificacao[]">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="id_ativo_externo">Data do documento</label>
                                                    <input type="date" class="form-control" name="data_conclusao[]"
                                                        id="data_conclusao">
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="quantidade">Arquivo(s)</label>
                                                    <input class="form-control" type="file" id="file"
                                                        name="file[]" multiple="">
                                                </div>
                                            </div>

                                            <div class="col-1 p-0 @if (session()->get('usuario_vinculo')->id_nivel == 1 
                                                            or session()->get('usuario_vinculo')->id_nivel == 15
                                                            or session()->get('usuario_vinculo')->id_nivel == 10)
                                                                d-block
                                                        @else                                                    
                                                                d-none
                                                        @endif">
                                                <div class="form-group">
                                                    <label for="SwitchCheck11">Aprovado? </label>
                                                    <select class="form-select situacao-select" name="situacao_doc[]"
                                                        id="situacao_doc" 
                                                        @if (session()->get('usuario_vinculo')->id_nivel == 1 
                                                            or session()->get('usuario_vinculo')->id_nivel == 15
                                                            or session()->get('usuario_vinculo')->id_nivel == 10)
                                    
                                                        @else
                                                            disabled style="color: red;" 
                                                        @endif>

                                                        <option selected value="">Selececionar</option>

                                                        <option value="18"
                                                            @if (session()->get('usuario_vinculo')->id_nivel <= 2 or session()->get('usuario_vinculo')->id_nivel == 14) @else 
                                                            
                                                            style="color: red;" @endif>

                                                            Não</option>
                                                        <option value="2">Sim</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </template>


                                    <div id="qualificacoes">

                                    </div><!-- Lista de qualificações necessárias para a função -->
                                @else
                                    <!-- Default Modals -->
                                    {{-- dd("estou EDITANDO aqui")- --}}

                                    <div class="row mt-4">
                                       

                                        <div class="col-sm-12 col-xl-12">
                                            <div class="card border">
                                                <div class="card-header align-items-center d-flex">
                                                    <h4 class="card-title mb-0 flex-grow-1">Doc's Qualificação Obrigatória
                                                    </h4>
                                                </div><!-- end card header -->

                                                <div class="card-body">
                                                    <div class="live-preview">
                                                        <div class="table-responsive">
                                                            <table
                                                                class="table table-sm table-bordered align-middle table-nowrap mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center" scope="col">ID</th>
                                                                        <th scope="col">Documento</th>
                                                                        <th scope="col">Arquivo</th>
                                                                        <th class="text-center" scope="col">Data de Conclusão</th>
                                                                        <th class="text-center" scope="col">Data de Validade</th>
                                                                        <th class="text-center" scope="col">Situação</th>
                                                                        <th class="text-center" scope="col">Ações</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    
                                                                    {{--dd(session()->all())--}}

                                                                    @foreach ($qualificacao_funcoes as $qualificacao)
                                                                        <tr>
                                                                            <td class="text-center">{{ $qualificacao->id }}</td>
                                                                            <td>{{ $qualificacao->qualificacoes->nome_qualificacao ?? 'Sem reg.' }}</td>
                                                                            <td>
                                                                                {{ $qualificacao->nome_arquivo ?? 'Sem reg.' }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                {{ isset($qualificacao->data_conclusao) ? Tratamento::dateBr($qualificacao->data_conclusao) : 'Aguardando' }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                {{ isset($qualificacao->data_validade_doc) ? Tratamento::dateBr($qualificacao->data_validade_doc) : 'Aguardando' }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <div class="@if (session()->get('usuario_vinculo')->id_nivel == 1 ||
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
                                                                            </td>
                                                                            <td class="text-center">
                                                                                
                                                                                @if($qualificacao->data_validade_doc == "")
                                                                                
                                                                                    <span class="btn btn-warning btn-sm"
                                                                                      data-id="{{ $qualificacao->id}}"
                                                                                      data-name="cadastrar_qualificacao"
                                                                                      data-bs-toggle="modal"
                                                                                      data-bs-target="#alterar_qualificacao"
                                                                                      id="btn_editarqualificacao" title="Editar">
                                                                                    <i class="mdi mdi-pencil"></i>
                                                                                </span>
                                                                                
                                                                                @else
                                                                                    <span class="btn btn-warning btn-sm"
                                                                                          data-id="{{ $qualificacao->id_anexos}}"
                                                                                          data-name="editar_qualificacao"
                                                                                          data-bs-toggle="modal"
                                                                                          data-bs-target="#alterar_qualificacao"
                                                                                          id="btn_editarqualificacao" title="Editar">
                                                                                        <i class="mdi mdi-pencil"></i>
                                                                                    </span>
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
                                        
                                         <div class="col-sm-12 col-xl-12 ">
                                            <div class="card border">
                                                <div class="card-header align-items-center d-flex">
                                                    <h4 class="card-title mb-0 flex-grow-1">Documentos Gerais</h4>

                                                    <div class="d-flex form-group">
                                                        <label id="botoes">Adicionar</label>
                                                        <a data-bs-toggle="modal" data-bs-target="#myModal">
                                                            <span class="bg-primary text-white py-1 px-2 rounded mx-2"><i
                                                                    class="mdi mdi-plus"></i></span>
                                                        </a>
                                                    </div>

                                                    <!--  -->

                                                </div><!-- end card header -->

                                                <div class="card-body">
                                                    <div class="live-preview">
                                                        <div class="table-responsive">
                                                            <table
                                                                class="table table-bordered align-middle table-nowrap mb-0">
                                                                <thead>
                                                                    <tr>

                                                                        <th scope="col">Documento</th>
                                                                        <th class="text-center" scope="col">Data de
                                                                            cadastro</th>
                                                                        <th class="text-center" scope="col">Aprovado?
                                                                        </th>
                                                                        <th class="text-center" scope="col">Ações</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                    @foreach ($anexos_funcionarios as $anexos_funcionario)
                                                                        <tr>
                                                                            <td class="p-1">
                                                                                {{ $anexos_funcionario->nome_arquivo }}
                                                                            </td>
                                                                            <td class="text-center p-1">
                                                                                {{ Tratamento::FormatarData($anexos_funcionario->created_at) }}
                                                                            </td>

                                                                            <td class="text-center p-1">

                                                                                <div id="situacao-select_{{ $anexos_funcionario->id }}">

                                                                                    <select class="form-select situacao-select"
                                                                                        name="situacao_doc[]"
                                                                                        id="situacao_doc"
                                                                                        data-id="{{ $anexos_funcionario->id ?? 3 }}"

                                                                                        @if (session()->get('usuario_vinculo')->id_nivel == 1 
                                                                                            or session()->get('usuario_vinculo')->id_nivel == 15
                                                                                            or session()->get('usuario_vinculo')->id_nivel == 10)
                                                                        
                                                                                            @else
                                                                                                disabled style="color: red;" 
                                                                                            @endif>

                                                                                        @if($anexos_funcionario->situacao_doc == 1)
                                                                                            <option  value="">Selecionar</option>
                                                                                            <option selected value="1">Pendente</option>
                                                                                            <option value="18">Não</option>
                                                                                            <option value="2">Sim</option>
                                                                                        
                                                                                        @elseif($anexos_funcionario->situacao_doc == 18)

                                                                                        <option value="">Selecionar</option>
                                                                                        <option value="1">Pendente</option>
                                                                                        <option selected value="18">Não</option>
                                                                                        <option value="2">Sim</option>

                                                                                        @elseif($anexos_funcionario->situacao_doc == 2)

                                                                                        <option value="2" disabled style="opacity: 0.8; background-color: chartreuse;" disabled="disabled" readonly="true">Sim</option>

                                                                                        @else

                                                                                        @endif
                                                                                       
                                                                                    </select>
                                                                                </div>

                                                                            </td>

                                                                            <td class="text-center p-1">
                                                                                <!--   <a href="javascript:void(0);">
                                                                                                        <i class="mdi mdi-download mdi-18px text-success" title="Download"></i>
                                                                                                    </a> -->
                                                                                <!-- <a href="javascript:void(0);">
                                                                                                        <i class="mdi mdi-pencil mdi-18px text-warning" title="Editar"></i>
                                                                                                    </a> -->

                                                                                <a href="{{ route('cadastro.funcionario.excluir_anexos_funcionarios', $anexos_funcionario->id) }}"
                                                                                    id="deletar">
                                                                                    <span
                                                                                        class="btn btn-outline-danger btn-sm waves-effect waves-light material-shadow-none"
                                                                                        title="Excluir"><i
                                                                                            class="mdi mdi-delete mdi-18px"></i></span>
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

                                    <div id="qualificacoes">

                                    </div><!-- Lista de qualificações necessárias para a função -->



                                @endif
                            </div>
                        </div>

                        <div class="aside col-12 mt-5">
                            <button class="btn btn-primary btn-md font-weight-medium" type="submit">Salvar</button>

                            <a href="{{ url('admin/cadastro/funcionario') }}">
                                <span class="btn btn-warning btn-md font-weight-medium">Cancelar</span>
                            </a>
                        </div>
                    </div>
                    <!--end col-->

                </div>
                <!--end row-->

    </form>
    
    <!-- Modal -->
    <div class="modal fade" id="alterar_qualificacao" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar documento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="form_editar_qualificacao_modal" enctype="multipart/form-data">
                        @csrf

                        <div class="row mt-2" id="consultar_anexo">

                            <input type="hidden" name="id_anexo" id="id_anexo_editar">
                            <input type="hidden" class="form-control" name="id_qualificacao" id="id_qualificacao">
                            
                            <input type="hidden" class="form-control" name="tempo_validade" id="tempo_validade">
                            <input type="hidden" class="form-control" name="data_validade" id="data_validade">
                            <input type="hidden" class="form-control" name="id_funcionario_anexo" id="id_funcionario_anexo" value="{{@$store->id}}">
                            <input type="hidden" class="form-control" name="id_funcao_anexo" id="id_funcao_anexo" value="{{@$store->id_funcao}}">
                            <input type="hidden" class="form-control" name="acao_cadastrar_editar" id="acao_cadastrar_editar">

                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="id_ativo_externo">Documento</label>
                                    <input type="text" id="nome_qualificacao_editar" class="form-control bg-light"
                                        readonly>
                                    <p class="text-warning m-1" id="teste_mens"></p>
                                    <span class="text-danger m-0" id="mensagem_validade{{ @$qualificacao->id }}">
                                    </span>

                                </div>
                            </div>

                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="id_ativo_externo">Data
                                        de conclusão</label>
                                    <input type="date" class="form-control editar_data_conclusao"
                                        name="data_conclusao" id="data_conclusao_editar">
                                </div>
                            </div>
                            <div class="col-6 mt-2">
                                <div class="form-group">
                                    <label for="quantidade">Arquivo</label>
                                    <input class="form-control" type="file" id="file_editar" name="file">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="button" id="salvar_alteracoes" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Adicionar Documentos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <h5 class="fs-15">
                        <div class="d-flex justify-content-end form-group ">
                            <label id="botoes">Ações</label>

                            <a class="listar-ativos-adicionar" id="listar-ativos-adicionar">
                                <span class="bg-primary text-white py-1 px-2 rounded mx-2"><i
                                        class="mdi mdi-plus"></i></span>
                            </a>
                            <a class="listar-ativos-remover" id="listar-ativos-remover">
                                <span class="bg-warning text-white py-1 px-2 rounded"><i class="mdi mdi-minus"></i></span>
                            </a>
                        </div>


                    </h5>

                    <hr>
                    <form method="post"
                        action="{{ route('cadastro.funcionario.funcoes.adicionar_anexos_funcionarios') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- id do funcionario usando id na que esta na url-->
                        <input type="hidden" name="id_funcionario_anexo" id="id_funcionario_anexo"
                            value="{{ @$store->id }}">

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
                                        <input type="date" class="form-control" name="data_conclusao[]"
                                            id="data_conclusao">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="quantidade">Arquivo(s)</label>
                                        <input class="form-control" type="file" id="file" name="file[]"
                                            multiple="">
                                    </div>
                                </div>

                                <div class="col-2 p-0 @if (session()->get('usuario_vinculo')->id_nivel == 1 
                                                            or session()->get('usuario_vinculo')->id_nivel == 15
                                                            or session()->get('usuario_vinculo')->id_nivel == 10)
                                                                d-block
                                                        @else                                                    
                                                                d-none
                                                        @endif">
                                    <div class="form-group">
                                        <label for="SwitchCheck11">Aprovado? </label>
                                        <select class="form-select situacao-select" name="situacao_doc[]"
                                            id="situacao_doc"@if (session()->get('usuario_vinculo')->id_nivel == 1 
                                                                or session()->get('usuario_vinculo')->id_nivel == 15
                                                                or session()->get('usuario_vinculo')->id_nivel == 10)
                                            
                                                                @else
                                                                    disabled style="color: red;" 
                                                                @endif>

                                            <option selected value="">Selececionar</option>

                                            <option value="18"
                                                @if (session()->get('usuario_vinculo')->id_nivel <= 2 or session()->get('usuario_vinculo')->id_nivel == 14) @else style="color: red;" @endif>Não
                                            </option>
                                            <option value="2">Sim</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </template>

                        <hr>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary ">Salvar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>

    <!-- Modal -->
    <div class="modal fade" id="motivoReprovacaoModal" tabindex="-1" aria-labelledby="motivoReprovacaoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="motivoReprovacaoModalLabel">Motivo da Reprovação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Histórico</h6>
                    <div id="motivoReprovacaoExistente"></div>
                    <textarea id="motivoReprovacao" class="form-control" rows="12"
                        placeholder="Digite o motivo da reprovação aqui..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="enviarMotivo">Enviar</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <script>
        function carregarImg() {

            var target = document.getElementById('target');
            var file = document.querySelector("input[id='profile-img-file-input'][type='file']").files[0];
            var reader = new FileReader();

            //console.log('file');'input[id="tempo_validade' + item.id + '"]'

            reader.onloadend = function() {
                target.src = reader.result;
            };

            if (file) {
                reader.readAsDataURL(file);


            } else {
                target.src = "";
            }
        }


        
        $(document).ready(function() {
            
            
            $(document).on('click', '#btn_editarqualificacao', function() {

                var anexoId = $(this).data('id');
                var acao_cadastrar_editar = $(this).data('name');
                
               //alert(anexoId);
                
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: "{{ route('cadastro.funcionario.consultar_qualificacao') }}",
                    data: {
                        id_anexo: anexoId,
                        acao_cadastrar_editar: acao_cadastrar_editar

                    }
                }).done(function(data) {

                    console.log(data)
                    // Preencha os campos do modal com os dados retornados

                    $('#id_anexo_editar').val(data.id_anexo);
                    $('#nome_qualificacao_editar').val(data.nome_qualificacao);
                    $('#data_conclusao_editar').val(data.data_conclusao);
                    $('#data_validade').val(data.data_calculado);
                    $('#tempo_validade').val(data.tempo_validade);
                    $('#id_qualificacao').val(data.id_qualificacao);
                    $('#teste_mens').html(
                        `
                            Valido por: <strong  class="text-success">` + data.tempo_validade + ` meses</strong>
                            
                            `
                    );
                    $('#acao_cadastrar_editar').val(acao_cadastrar_editar);


                    $('#mensagem_validade').html(
                        `Valido por: <strong class="text-success">
                            <span class="text-danger m-0"></span>` + data.tempo_validade + ` meses</strong>`
                    );

                    // Exibir o modal
                    $('#alterar_qualificacao').modal('show');

                });
            });
        
        

        $('#salvar_alteracoes').on('click', function() {

        

            var url_editat_quali =  "{{ route('cadastro.funcionario.editar_anexos_funcionarios', ['id' => ':id']) }}".replace(':id', id_editat_quali);

            var fileInput = document.getElementById('file_editar');
            var file = fileInput.files[0];
            var id_editat_quali = $('#id_anexo_editar').val();

            console.log(file);

            var formData = new FormData($('#form_editar_qualificacao_modal')[0]);
            
            console.log(formData)


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: url_editat_quali,
                data: formData,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {

                   // console.log(response)

                    Swal.fire({
                        title: response.message.title,
                        text: response.message.message,
                        icon: response.message.type,
                        confirmButtonText: 'Ok'
                    }).then(function() {

                        var partesData = data.response.data[0].data_conclusao.split('/');
                        var dataCalculado = new Date(partesData[2], partesData[1] - 1,
                            partesData[0]);
                        
                        var partesDataValidade = data.response.data[0].data_validade_doc.split('/');
                        var dataCalculadoValidade = new Date(partesDataValidade[2], partesDataValidade[1] - 1,
                            partesDataValidade[0]);
                            
                            

                        $("#data_conclusao_consulta").val(dataCalculado)
                        $("#file_consulta").val(response.data[0].nome_arquivo)
                        $("#file_editar").val() = ""
                        $("#data_conclusao_cad").val(dataCalculado)
                        $("#data_validade_doc_cad").val(dataCalculadoValidade)

                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Ocorreu um erro ao tentar salvar os dados.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        });
        
        
            var currentUrl = window.location.href;
            var adicionarUrl = "{{ route('cadastro.funcionario.adicionar') }}";
            var consultar_campo_data_conclusao = currentUrl === adicionarUrl ? ".data_conclusao" :
                ".editar_data_conclusao";

            $("#id_funcao").change(function(e) {
                var id_funcao = $(this).val();
                var id_funcionario = "{{ @$store->id }}";

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: "{{ route('cadastro.funcionario.consultar_qualificacao') }}",
                    data: {
                        id_funcao: id_funcao,
                        id_funcionario: id_funcionario
                    }
                }).done(function(data) {
                    if (data.type === 'warning') {
                        Swal.fire({
                            title: 'Atenção!',
                            text: data.message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sim, excluir',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Realizar a exclusão via AJAX
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('cadastro.funcionario.excluir_qualificacao') }}",
                                    data: {
                                        id_funcionario: id_funcionario
                                    },
                                    success: function(response) {
                                        Swal.fire(
                                            'Excluído!',
                                            'Os registros foram excluídos com sucesso.',
                                            'success'
                                        );

                                        setTimeout(function() {
                                            window.location.hash =
                                                'changePassword';
                                            location.reload();
                                        }, 1000);

                                        // Atualizar a interface após a exclusão
                                        $('#qualificacoes').html('');
                                        // Reativar a inclusão de cursos não obrigatórios
                                        $("#nome_qualificacao").prop("disabled",
                                            false);
                                        $("#data_conclusao").prop("disabled",
                                            false);
                                        $("#file").prop("disabled", false);
                                        $("#data_conclusao").prop("disabled",
                                            false);
                                        $("#SwitchCheck11").prop("disabled",
                                            false);
                                        $("#listar-ativos-adicionar").prop(
                                            "disabled", false);
                                        $("#listar-ativos-remover").prop(
                                            "disabled", false);
                                    },
                                    error: function(xhr, status, error) {
                                        Swal.fire(
                                            'Erro!',
                                            'Ocorreu um erro ao tentar excluir os registros.',
                                            'error'
                                        );
                                    }
                                });
                            }
                        });
                    } else {
                        // Atualizar a interface com os dados retornados
                        processarResposta(data);
                    }
                });
            });

            function processarResposta(data) {
                if (data.message.length > 0) {
                    // Exibe a div das qualificações
                    $('#qualificacoes').css({
                        "display": "block"
                    });

                    // Processa a resposta e atualiza a interface
                    var html = "";

                    $.each(data.message, function(index, item) {
                        html += `
                        <div class='row adicionar mt-2 id${item.id}'>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="id_ativo_externo">Documento</label>
                                    <input type="text" class="form-control" placeholder="${item.nome_qualificacao}" readonly>
                                    <input type="hidden" class="form-control" value="${item.id}" name="id_qualificacao[]" id="id_qualificacao">
                                    <input type="hidden" class="form-control" value="${item.tempo_validade}" name="tempo_validade[]" id="tempo_validade">
                                    <input type="hidden" value="${item.tempo_validade}" class="form-control" name="data_validade[]" id="data_validade">
                                    <p class="text-warning m-1">Valido por: <strong class="text-success">${item.tempo_validade} meses</strong></p>
                                    <span class="text-danger m-0" id="mensagem_validade${item.id}"></span>
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="id_ativo_externo">Data de conclusão</label>
                                    <input type="date" class="form-control data_conclusao" name="data_conclusao[]" id="data_conclusao${item.id}">
                                </div>
                            </div>
                            <div class="col-4 mt-2">
                                <div class="form-group">
                                    <label for="quantidade">Arquivo</label>
                                    <input class="form-control" type="file" id="file" name="file[]">
                                </div>
                            </div>
                            <div class="col-2 mt-2 p-0 @if (session()->get('usuario_vinculo')->id_nivel == 1 ||
                                    session()->get('usuario_vinculo')->id_nivel == 15 ||
                                    session()->get('usuario_vinculo')->id_nivel == 10) d-block @else d-none @endif">
                                <div class="form-group">
                                    <label for="SwitchCheck11">Aprovado?</label>
                                    <select class="form-select situacao-select" name="situacao_doc[]" id="situacao_doc${item.id}">
                                        <option value=""selected>Selececionar</option>
                                        <option value="18">Não</option>
                                        <option value="2">Sim</option>
                                    </select>
                                </div>
                            </div>
                        </div>`;
                        });

                    $('#qualificacoes').html(
                        "<h5 class='card-title mb-0 flex-grow-1 text-white text-center mt-3' id='divPiscante'>Esta função possui qualificações obrigatórias</h5><hr>" +
                        html);

                    // Configurar evento de mudança para os novos inputs de data
                    $.each(data.message, function(index, item) {
                        $('#data_conclusao' + item.id).change(function() {
                            var data_conclusao = $(this).val();
                            var tempo_validade = $('#tempo_validade' + item.id).val();
                            var id_qualificacao = $('#id_qualificacao' + item.id).val();
                            var mensagem_validade = $('#mensagem_validade' + item.id);

                            processarDataConclusao(data_conclusao, tempo_validade,
                                mensagem_validade, id_qualificacao);
                        });
                    });
                } else {
                    // Reativar a inclusão de cursos não obrigatórios
                    $("#nome_qualificacao").prop("disabled", false);
                    $("#data_conclusao").prop("disabled", false);
                    $("#file").prop("disabled", false);
                    $("#data_conclusao").prop("disabled", false);
                    $("#SwitchCheck11").prop("disabled", false);
                    $("#listar-ativos-adicionar").prop("disabled", false);
                    $("#listar-ativos-remover").prop("disabled", false);
                    // Oculta a div das qualificações
                    $('#qualificacoes').css({
                        "display": "none"
                    });
                }
            }
            console.log(consultar_campo_data_conclusao)
            // Configurar evento de mudança para os inputs de data existentes
            $(document).on('change', consultar_campo_data_conclusao, function() {
                var data_conclusao, tempo_validade, id_qualificacao,
                    id_funcionario = "{{ @$store->id }}",
                    id_funcao = $("#id_funcao").val(),
                    mensagem_validade, id_anexo, editar_campo_data_conclusao;

                tempo_validade = $(this).closest('.row').find('[id^="tempo_validade"]').val();
                id_qualificacao = $(this).closest('.row').find('[id^="id_qualificacao"]').val();
                mensagem_validade = $(this).closest('.row').find('[id^="mensagem_validade"]');

                if (currentUrl === adicionarUrl) {
                    data_conclusao = $(this).val();
                    id_anexo = "";
                    editar_campo_data_conclusao = "";
                } else {
                    data_conclusao = "";
                    id_anexo = $(this).closest('.row').find('[id^="id_anexo_editar"]').val();
                    editar_campo_data_conclusao = $(this).val();
                }

                processarDataConclusao(data_conclusao, tempo_validade, id_qualificacao, id_funcionario,
                    id_funcao, mensagem_validade, id_anexo, editar_campo_data_conclusao);
            });

            function processarDataConclusao(data_conclusao, tempo_validade, id_qualificacao, id_funcionario,
                id_funcao, mensagem_validade, id_anexo, editar_campo_data_conclusao) {
                $(mensagem_validade).html(
                    '<span class="mensagem text-danger">' +
                    '<div class="spinner-border text-success m-0" role="status">' +
                    '<span class="visually-hidden">Loading...</span>' +
                    '</div>' +
                    'Aguarde, calculando o prazo de validade do certificado ...</span>'
                );

                $.ajax({
                    type: 'GET',
                    url: "{{ route('cadastro.funcionario.consultar_qualificacao') }}",
                    data: {
                        data_conclusao: data_conclusao,
                        tempo_validade: tempo_validade,
                        id_funcionario: id_funcionario,
                        id_qualificacao: id_qualificacao,
                        id_funcao: id_funcao,
                        editar_data_conclusao: editar_campo_data_conclusao,
                        id_anexo: id_anexo
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {
                    var dataAtual = new Date();
                    var dataDoc = new Date(data_conclusao);

                    var partesData = data.data_calculado.split('/');
                    var dataCalculado = new Date(partesData[2], partesData[1] - 1, partesData[0]);

                    if (dataDoc > dataAtual) {
                        setTimeout(function() {
                            $(mensagem_validade).html(
                                "<p class='text-danger'>Você está tentando inserir uma data maior que a data atual!!! <span style='font-size:20px;'>&#129300;</span> </p>"
                            );
                        }, 1000);
                    } else if (dataCalculado < dataAtual) {
                        setTimeout(function() {
                            $(mensagem_validade).html(
                                "<p class='text-danger'>Este documento já está vencido!!! <span style='font-size:20px;'>&#128552;</span> </p>"
                            );
                        }, 1000);
                    } else if (dataCalculado > dataAtual) {
                        setTimeout(function() {
                            $(mensagem_validade).html(
                                "<p class='text-success'>Este certificado é válido até " +
                                data.data_calculado +
                                " <span style='font-size:20px;'>&#128526;</span></p>"
                            );
                        }, 1000);
                    }
                });
            }


        });

    </script>

@endsection
