@extends('dashboard')
@section('title')
    @lang('translation.settings')
@endsection
@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img" style="height:220px">
            <img src="{{ URL::asset('build/images/icones/logo_engetecnica.svg') }}" class="profile-wid-img" alt="">
            <div class="overlay-content">
                <div class="text-end p-3">

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">

                            <img src="@if ($veiculo->imagem != '') {{ URL::asset('imagens/veiculos/' . $veiculo->id . '/' . $veiculo->imagem) }}@else{{ URL::asset('imagens/veiculos/nao-ha-fotos.png') }} @endif"
                                class="img-thumbnail">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-17 mb-1">{{ $veiculo->modelo }}</h5>

                    </div>
                </div>
            </div>
            <!--end card-->
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th>ID</th>
                            <td>{{ $veiculo->id }}</td>
                        </tr>
                        <tr>
                            <th>Tipo</th>
                            <td>{{ $veiculo->tipos->nome_tipo_veiculo }}</td>
                        </tr>
                        <tr>
                            <th>Marca</th>
                            <td>{{ $veiculo->marca }}</td>
                        </tr>
                        <tr>
                            <th>Modelo</th>
                            <td>{{ $veiculo->modelo }}</td>
                        </tr>
                        <tr>
                            <th>Ano</th>
                            <td>{{ $veiculo->ano }}</td>
                        </tr>
                        <tr>
                            <th>Placa</th>
                            <td>{{ $veiculo->placa }}</td>
                        </tr>
                        <tr>
                            <th>Valor FIPE</th>
                            <td>{{ $veiculo->valor_fipe }}</td>
                        </tr>
                        <tr>
                            <th>Situação</th>
                            <td>{{ $veiculo->situacao }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i>
                                Detalhes do veículo
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-bs-toggle="tab" href="#docs_tecnicos" role="tab">
                                <i class="fas fa-home"></i>
                               Doc's Técnicos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#manutencoes" role="tab">
                                <i class="far fa-user"></i>
                                Manutenções
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#checklist" role="tab">
                                <i class="far fa-envelope"></i>
                                Checklist
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#seguros" role="tab">
                                <i class="far fa-envelope"></i>
                                Seguros
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#ipvas" role="tab">
                                <i class="far fa-envelope"></i>
                                IPVA's
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#abastecimentos" role="tab">
                                <i class="far fa-envelope"></i>
                                Abastecimentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#privacy" role="tab">
                                <i class="far fa-envelope"></i>
                                Quilometragem
                            </a>
                        </li>                     
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#privacy" role="tab">
                                <i class="far fa-envelope"></i>
                                Acessórios
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">

                            <div class="d-flex card-header mb-3">
                                <div class="form-group col-12 p-1 m-0 mb-2">
                                    <form action="{{ route('veiculos.storeImage', $veiculo->id) }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex flex-row bd-highlight">
                                            <div class="bd-highlight col-3">
                                                <h4 class="card-title">GALERIA DE IMAGENS</h4>
                                            </div>
                                            <div class="bd-highlight col-6">
                                                <input type="hidden" name="veiculo_id" value="{{ $veiculo->id }}">
                                                <input class="form-control form-control-sm" id="formFileSm"
                                                    name="imagens[]" multiple type="file">
                                            </div>
                                            <div class="bd-highlight mx-2 col-3">
                                                <button type="submit" class="btn btn-primary">Cadastrar novas
                                                    imagens</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                @foreach ($imagens as $imagem)
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <form action="{{ route('veiculos.deleteImage', $imagem->id) }}"
                                                    method="POST" class="m-0 p-0">
                                                    @csrf
                                                    <input type="hidden" name="veiculo_id" value="{{ $veiculo->id }}">
                                                    <button type="submit" class="btn-close text-danger float-end fs-11"
                                                        aria-label="Close" data-toggle="tooltip" data-placement="top"
                                                        type="submit" title="Excluir"
                                                        onclick="return confirm('Tem certeza que deseja exluir a imagem?')"></button>
                                                </form>
                                                <h6 class="card-title mb-0">{{ $imagem->descricao ?? 'Descrição' }}</h6>
                                            </div>
                                            <div class="card-body p-4 text-center">
                                                <div class="mx-auto avatar mb-3">
                                                    <img src="{{ asset('imagens/veiculos/' . $imagem->veiculo_id . '/' . $imagem->imagens) }}"
                                                        alt="" class="img-fluid">
                                                </div>
                                            </div>
                                            <div class="card-footer text-center">
                                                <div class="row">
                                                    <div class="d-flex col-6">
                                                        <button type="button" id="btn_modal_img_veiculo"
                                                            class="btn btn-primary " data-id="{{ $imagem->id }}"
                                                            data-bs-toggle="modal" data-bs-target="#modal_img_veiculo">
                                                            <i class="mdi mdi-pencil mdi-18x"></i>Alterar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end col -->
                                @endforeach
                            </div>

                        </div>

                        <!--end tab-pane-->
                        <div class="tab-pane" id="docs_tecnicos" role="tabpanel">
                            <a href="{{ route('veiculo_docs_tecnico.create', $veiculo->id) }}" class="btn btn-success rounded">Cacadastrar Docs's Técnicos</a>
                            <div class="card-body">
                            <div class="card">
                               
                                    <table class="table table-grid">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome Documento</th>
                                                <th>Arquivo</th>
                                                <th>Data Documento</th>                                
                                                <th>Validade</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($docs_tecnicos as $doc_tec)
                                                <tr>
                                                    <td>{{ $doc_tec->id }}</td>
                                                    <td>{{ $doc_tec->tipo_doc_legal->nome_documento }}</td>
                                                    <td>{{ $doc_tec->arquivo }}</td>
                                                    <td>{{ $doc_tec->data_documento }}</td>
                                                    <td>{{ $doc_tec->data_validade }}</td>
                                                    <td>
                                                        <a class="btn btn-warning" href="{{ route('veiculo_docs_tecnico.edit', $doc_tec->id) }}">Editar</a>
                                                        <a class="btn btn-danger" href="{{ route('veiculo_docs_tecnico.delete', $doc_tec->id) }}">Excluir</a>
                                                        <a class="btn btn-success" href="{{ route('veiculo_docs_tecnico.download', $doc_tec->id) }}">Download</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="manutencoes" role="tabpanel">

                            <div class="card shadow">
                                <a href="{{ route('ativo.veiculo.manutencao.adicionar', $veiculo->id) }}">
                                    <button class="btn btn-success">Cadastrar Manutenção</button>
                                </a>
                                <div class="card-body">

                                    <table class="table table-sm table-hover table-bordered align-middle">
                                        <thead class="bg-light text-muted">
                                            <tr>
                                                <th>ID</th>
                                                <th>Tipo</th>
                                                <th>km Atual</th>
                                                <th>Data de Execução</th>
                                                <th>Data de Vencimento</th>
                                                <th>Descrição</th>
                                                <th>Valor do Serviço</th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($manutencoes as $manutencao)
                                                <tr>
                                                    <td>{{ $manutencao->id }}</td>
                                                    <td>{{ $manutencao->tipo }}</td>
                                                    <td>{{ $manutencao->quilometragem_atual }}</td>
                                                    <td>{{ Tratamento::dateBr($manutencao->data_de_execucao) }}</td>
                                                    <td>{{ Tratamento::dateBr($manutencao->data_de_vencimento) }}</td>
                                                    <td>{{ $manutencao->descricao }}</td>
                                                    <td>{{ $manutencao->valor_do_servico }}</td>

                                                    <td class="d-flex justify-content-center">
                                                        <button type="button" class="btn btn-primary btn-sm btn_modal_uploada_arq_manut " data-id="{{ $manutencao->id }}"></i>inserir/ Alterar</button>
                                                        <a href="{{ route('ativo.veiculo.manutencao.show', $manutencao->id) }}" class="btn btn-info btn-sm mx-2">Ver</i></a>

                                                        <a href="{{ route('ativo.veiculo.manutencao.edit', $manutencao->id) }}" class="btn btn-secondary btn-sm ">Editar</a>                                                       
                                                        
                                                        <a href="{{ route('ativo.veiculo.manutencao.download', $manutencao->id) }}" class="btn btn-outline-success btn-sm " title="Baixar anexo">Baixar anexo</a>
                                                        
                                                        <form action="{{ route('ativo.veiculo.manutencao.delete', $manutencao->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este registro?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm mx-2">Excluir</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="row mt-2">
                                        <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">
                                            <div class="paginacao">
                                                {{ $manutencoes->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="checklist" role="tabpanel">
                            <div class="card shadow">
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-3 col-xxl-12">
                                        
                                            @php
                                                $periodosArray = json_decode($preventiva->periodo, true);
                                                $periodos = [];
                                                foreach ($periodosArray as $p) {
                                                    $periodos = array_merge($periodos, array_map('trim', explode(',', $p)));
                                                }
                                                $periodos = array_unique($periodos);
                                                sort($periodos);
                                    
                                            @endphp
                                    
                                            <a href="{{ route('veiculo.manut_preventiva.show', $preventiva->id) }}" class="btn btn-warning  mb-3">Ver o Checklist Completo</a>
                                    
                                                <div class="btn-group ">
                                                    <a href="#" class="btn btn-primary active  mb-3" aria-current="page">Cadastrar Checklist</a>
                                                    @foreach ($periodos as $periodo)
                                                        <a href="{{ route('veiculo_preventivas_checklist.create', $preventiva->id.'?periodo='.$periodo.'&id_veiculo='.$veiculo->id)}}" class="btn btn-primary mb-3">De {{ $periodo }} horas |</a>  
                                                    @endforeach
                                                </div>
                                    
                                            <table class="table table-sm table-hover table-bordered align-middle">
                                                <thead class="bg-light text-muted">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>ID Veículo</th>
                                                        <th>Manutenção Preventiva</th>                    
                                                        <th>Período</th>                                   
                                                        <th class="text-center">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($checkLists as $checkList)
                                                        <tr>
                                                            <td>{{ $checkList->id }}</td>
                                                            <td>{{ $checkList->id_veiculo }}</td>
                                                            <td>{{ $preventiva->nome_preventiva }}</td>
                                                            <td>{{ $checkList->periodo }}</td>
                                    
                                                            <td class="d-flex justify-content-center">
                                                               
                                                                    <a href="{{ route('veiculo_preventivas_checklist.show', $checkList->id) }}" class="btn btn-info btn-sm">Ver</a>
                                                                    <a href="{{ route('veiculo_preventivas_checklist.edit', $checkList->id) }}" class="btn btn-warning btn-sm mx-2">Editar</a>
                                                                    <form action="{{ route('veiculo_preventivas_checklist.destroy', $checkList->id) }}"
                                                                        method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                                                    </form>
                                                             
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            {{ $checkLists->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="seguros" role="tabpanel">
                            <div class="card">
                                <div class="card-body pt-0">
                                    <div class="card-header">
                                        <h3 class="page-title">
                                            <a class="btn btn-success " href="{{ route('ativo.veiculo.seguro.adicionar', $veiculo->id) }}">
                                                Cadastrar seguro
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-bordered align-middle">

                                            <thead class="bg-light text-muted">
                                                <tr>
                                                    <th class="text-center" width="8%">ID</th>
                                                    <th>Seguradora</th>
                                                    <th>Custo</th>
                                                    <th>Carência Inicial</th>
                                                    <th>Carência Final</th>
                                                    <th class="text-center" width="10%">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($seguros as $seguro)
                        
                                                <tr>
                        
                                                    <td class="text-center"> {{ $seguro->id }}</td>
                        
                                                    <td>{{($seguro->nome_seguradora) }}</td>
                        
                                                    <td>R$ {{ Tratamento::currencyFormatBr($seguro->valor) }} </td>
                        
                                                    <td>{{ Tratamento::dateBr($seguro->carencia_inicial) }}</td>
                        
                                                    <td>{{ Tratamento::dateBr($seguro->carencia_final) }}</td>
                        
                                                    <td class="d-flex justify-content-center">
                                                        <a data-bs-toggle="modal" data-bs-target="#anexarArquivoAtivoSeguro" class="seguro" href="javascript:void(0)" data-id="{{$seguro->id}}">
                                                            <span class='btn btn-success  btn-sm ml-1'><i class="mdi mdi-upload"></i></span>
                                                        </a>
                        
                                                        <a href="{{ route('ativo.veiculo.seguro.editar', [$seguro->id, 'edit']) }}">
                                                            <button class="btn btn-info  btn-sm mx-2" data-toggle="tooltip" data-placement="top" title="Editar"><i class="mdi mdi-pencil"></i></button>
                                                        </a>
                        
                                                         <form action="{{ route('ativo.veiculo.seguro.delete', $seguro->id) }}" method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <a class="excluir-padrao" data-id="{{ $seguro->id }}" data-table="empresas" data-module="cadastro/empresa">
                                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                                                    <i class="mdi mdi-delete"></i></button>
                                                            </a>
                                                        </form>
                                                    </td>                 
                                                </tr>

                                                @endforeach
                        
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane" id="ipvas" role="tabpanel">
                            <div class="card">
                                <div class="card-body pt-0">
                                    <div class="card-header">
                                        <h3 class="page-title">
                                            <a class="btn btn-success " href="{{ route('ativo.veiculo.ipva.adicionar', $veiculo->id) }}">
                                                Cadastrar IPVA
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-bordered align-middle">
                                            <thead>
                                                <tr class="bg-light text-muted">
                                                    <th class="text-center">ID</th>
                                                    <th class="text-center">Ano</th>
                                                    <th whdth="20%" class="text-center">Custo</th>
                                                    <th class="text-center">Pagamento</th>
                                                    <th class="text-center">Vencimento</th>
                                                    <th class="text-center">Nome Doc.</th>
                                                    <th class="text-center ">Ações</th>
                                                </tr>
                                            </thead>                        
                                            <tbody>                        
                                                @foreach ($ipvas as $ipva)                        
                                                <tr>
                                                    <td class="text-center">{{ $ipva->id }}</td>
                                                    <td class="text-center">{{ $ipva->referencia_ano }}</td>
                                                    <td class="text-center">R$ {{ Tratamento::currencyFormatBr($ipva->valor) }}</td>  
                                                    <td class="text-center">{{ Tratamento::dateBr($ipva->data_de_pagamento) }}</td>
                                                    <td class="text-center">{{ Tratamento::dateBr($ipva->data_de_vencimento) }}</td>
                                                    <td class="text-center">{{ ($ipva->nome_anexo_ipva) }}</td>
                        
                                                    <td class="d-flex justify-content-center">
                                                        <a href="{{ route('ativo.veiculo.ipva.download', $ipva->id) }}">
                                                            <span class="btn btn-success btn-sm">Baixar anexo</span>
                                                        </a>
                        
                                                        <a href="{{ route('ativo.veiculo.ipva.editar', $ipva->id) }}">
                                                            <span class="btn btn-info btn-sm mx-2" title="Editar">Editar</span>
                                                        </a>
                        
                                                        <form action="{{ route('ativo.veiculo.ipva.delete', $ipva->id) }}" method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <a class="excluir-padrao" data-id="{{ $ipva->id }}" data-table="empresas" data-module="cadastro/empresa">
                                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                                                    Excluir
                                                                </button>
                                                            </a>
                                                        </form> 
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end tab-pane-->

                        <div class="tab-pane" id="abastecimentos" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header">
                                        <h3 class="page-title">
                                            <a class="btn btn-success " href="{{ route('ativo.veiculo.abastecimento.adicionar', $veiculo->id) }}">
                                                Cadastrar Abastecimento
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-bordered align-middle">

                                            <thead>
                                                <tr class="bg-light text-muted text-center">
                                                    <th>ID</th>
                                                    <th >km Inicial</th>
                                                    <th>km Final</th>
                                                    <th>km Percorrido</th>
                                                    <th>Qtde.</th>
                                                    <th>R$ Médio (km/l)</th>
                                                    <th>R$/ litro</th>
                                                    <th>R$/ km</th>
                                                    <th>Valor Total</th>
                                                    <th>Data do Abast.</th>
                                                    <th>Qtde. de Carbono</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($abastecimentos as $abastecimento)
                                                    <tr class="text-center">
                                                        <td>{{ $abastecimento->id }}</td>
                                                        <td>{{ $abastecimento->km_inicial }}</td>
                                                        <td>{{ $abastecimento->km_final }}</td>
                                                        <td>{{ $abastecimento->quilometragem_percorrida }}</td>
                                                        <td>{{ $abastecimento->quantidade }}</td>
                                                        <td>{{ number_format($abastecimento->consumo_medio, 2) }} km/l</td>
                                                        <td>R$ {{ number_format($abastecimento->custo_por_litro, 2) }}</td>
                                                        <td>R$ {{ number_format($abastecimento->custo_por_km, 2) }}</td>
                                                        <td>R$ {{ number_format($abastecimento->valor_total, 2) }}</td>
                                                        <td>{{ Tratamento::dateBr( $abastecimento->data_abastecimento) }}</td>
                                                        <td>{{$abastecimento->emissao_carbono}} kg de CO₂</td>
                                                        <td class="d-flex justify-content-center">
                                                            <a href="{{ route('ativo.veiculo.abastecimento.edit', $abastecimento->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                                            <form action="{{ route('ativo.veiculo.abastecimento.delete', $abastecimento->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm mx-2">Excluir</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end tab-pane-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->


    <!-- tooltips and popovers modal -->
    <div class="modal fade" id="modal_img_veiculo" tabindex="-1" aria-labelledby="exampleModalPopoversLabel"
        aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalPopoversLabel">Alterar dados da imagem </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('veiculos.updateImage', 0) }}" method="POST" class="m-0 p-0"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        <input type="hidden" name="veiculo_id" value="{{ $veiculo->id }}">
                        <input type="hidden" id="id_imagem" name="id_imagem">

                        <div class="col-12 my-2">
                            <label for="firstnameInput" class="form-label">Alterar imagem</label>
                            <input type="file" id="input-file-now-custom-3" class="form-control" name="imagem">
                        </div>

                        <div class="col-12 my-2">
                            <label for="firstnameInput" class="form-label">Descrição da imagem</label>
                            <input type="text" class="form-control" name="descricao">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    @endsection
    @section('script')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var buttons = document.querySelectorAll('.btn_modal_uploada_arq_manut');

                buttons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        var manutencaoId = this.getAttribute('data-id');
                        var url_update_img_man = "{{ route('ativo.veiculo.manutencao.upload', ['id' => ':id']) }}";
                        url_update_img_man = url_update_img_man.replace(':id', manutencaoId);

                        (async () => {
                            const {
                                value: formValues
                            } = await Swal.fire({
                                title: '<div class="icon active">' +
                                    '<lord-icon src="https://media.lordicon.com/icons/wired/outline/120-folder.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                                    '</div>',
                                html: ` 
                                        <form id="uploadForm" method="POST" class="m-0 p-0" enctype="multipart/form-data">
                                
                                            <div class="row">
                                                <input type="hidden" id="manutencao_id" name="manutencao_id" value="${manutencaoId}">
                                                <div class="col-12 text-start mb-3">
                                                    <label for="arquivo" class="form-label small">Inserir/ Alterar arquivo</label>
                                                    <input type="file" class="form-control" name="arquivo" id="arquivo">
                                                </div>
                                                
                                            </div>
                                        </form>
                                    `,

                                showCancelButton: true,
                                confirmButtonText: 'Salvar',
                                cancelButtonText: 'Cancelar',
                                preConfirm: () => {
                                    var form = document.getElementById('uploadForm');
                                    var formData = new FormData(form);

                                    return fetch(url_update_img_man, {
                                        method: 'POST', // Ou 'PUT' dependendo do que a sua rota espera
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector( 'meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: formData
                                    }).then(response => {
                                        if (!response.ok) {
                                            throw new Error(
                                                'Erro ao salvar o arquivo'
                                                );
                                        }
                                        return response
                                    .json(); // Certifique-se de que a resposta seja JSON válida
                                    }).catch(error => {
                                        Swal.showValidationMessage(
                                            `Request failed: ${error.message}`
                                            );
                                    });
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire({
                                        title: 'Sucesso!',
                                        text: 'O arquivo foi salvo com sucesso.',
                                        icon: 'success',
                                    });
                                }
                            });
                        })(); // Auto-executando a função async corretamente
                    });
                });
            });


            $(document).ready(function() {

                // Encontre todos os botões "btn_modal_img_veiculo"
                var detalhesButtons = document.querySelectorAll('#btn_modal_img_veiculo');

                //Fazer um loop para encontrar os botões
                detalhesButtons.forEach(function(button) {
                    button.addEventListener('click', function() {

                        $("#id_imagem").val('');

                        let id_imagem = $(this).attr('data-id');

                        $("#id_imagem").val(id_imagem);

                    });
                });

                // Seleciona todos os botões de upload de arquivo de manutenção
                $('.btn_modal_uploada_arq_manut').on('click', function() {
                    var id_manutencao = $(this).data('id');
                    $('#manutencao_id').val(id_manutencao);
                });

            });
        </script>
    @endsection
