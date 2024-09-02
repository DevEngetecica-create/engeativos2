@extends('dashboard')
@section('title', 'Minha Conta')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Sistema
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Configurações <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>


<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

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

                <form method="post" enctype="multipart/form-data" action="{{ route('sistema.store') }}">
                    @csrf

                    <span class="badge badge-danger">Configurações Gerais</span>

                    <div class="col-6">
                        <label for="titulo" class="form-label">Título da Aplicação</label>
                        <input type="text" class="form-control" id="titulo" value="{{ isset($store) ? $store->titulo : '' }}" name="titulo">
                    </div>

                    <div class="col-3">
                        <label for="ti_responsavel_nome" class="form-label">Responsável TI</label>
                        <input type="text" class="form-control" id="ti_responsavel_nome" value="{{ isset($store) ? $store->ti_responsavel_nome : '' }}" name="ti_responsavel_nome">
                    </div>

                    <div class="col-3">
                        <label for="ti_responsavel_telefone" class="form-label">Responsável TI - Celular</label>
                        <input type="text" class="form-control" id="ti_responsavel_telefone" value="{{ isset($store) ? $store->ti_responsavel_telefone : '' }}" name="ti_responsavel_telefone">
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">E-mail de Origem Notificações</label>
                        <input type="email" class="form-control" id="email" value="{{ isset($store) ? $store->email : '' }}" name="email">
                    </div>

                    <div class="col-md-6">
                        <label for="ti_responsavel_email" class="form-label">Responsável TI - E-mail</label>
                        <input type="email" class="form-control" id="ti_responsavel_email" name="ti_responsavel_email" value="{{ isset($store) ? $store->ti_responsavel_email : '' }}">
                    </div>

                    <span class="badge badge-danger">Configurações de Pagamento </span>

                    <div class="col-6">
                        <label for="integrador" class="form-label">Integrador</label>
                        <select class="form-select" name="integrador" id="integrador">
                            <option value="">Nenhum</option>
                            @foreach($meiosdepagamento as $integrador)
                            <option value="{{ $integrador }}" @if($store && $store->integrador==$integrador) selected @endif >{{ $integrador }}</option>
                            @endforeach
                        </select>
                    </div>

                    @php
                    if(isset($store->keys)){
                    $keys = json_decode($store->keys);
                    }
                    @endphp

                    <div class="col-md-6">
                        <label for="access_token" class="form-label">Access token</label>
                        <input type="text" class="form-control" id="access_token" value="{{ ($keys) ? $keys->access_token : '' }}" name="access_token">
                    </div>

                    <div class="col-6">
                        <label for="client_id" class="form-label">Client ID</label>
                        <input type="text" class="form-control" id="client_id" value="{{ ($keys) ? $keys->client_id : '' }}" name="client_id">
                    </div>


                    <div class="col-md-6">
                        <label for="client_secret" class="form-label">Client Secret</label>
                        <input type="text" class="form-control" id="client_secret" name="client_secret" value="{{ ($keys) ? $keys->client_secret : '' }}">
                    </div>


                    <span class="badge badge-danger">Configurações de Pagamento - PIX</span>

                    <div class="col-6">
                        <label for="pix_nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="pix_nome" value="{{ isset($store) ? $store->pix_nome : '' }}" name="pix_nome">
                    </div>

                    <div class="col-3">
                        <label for="pix_tipo" class="form-label">Tipo de Chave</label>
                        <select class="form-select" name="pix_tipo" id="pix_tipo">
                            <option value="">Nenhum</option>
                            @foreach($tiposdepix as $chave)
                            <option value="{{ $chave }}" @if($store->pix_tipo==$chave) selected @endif>{{ $chave }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="pix_chave" class="form-label">Chave</label>
                        <input type="text" class="form-control" id="pix_chave" name="pix_chave" value="{{ isset($store) ? $store->pix_chave : '' }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-gradient-primary btn-lg font-weight-medium">Salvar</button>

                        <a href="{{ url('dashboard') }}">
                            <button type="button" class="btn btn-gradient-danger btn-lg font-weight-medium">Cancelar</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





@endsection