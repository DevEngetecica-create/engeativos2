@extends('dashboard')
@section('title', 'Módulo')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Configuração de Módulo
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

                @php $action = isset($store) ? route('modulo.update', $store->id) : route('modulo.store'); @endphp
                <form method="post" enctype="multipart/form-data" action="{{ $action }}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <label for="nivel" class="form-label">Módulo</label>
                            <select class="form-control select2" id="id_modulo" name="id_modulo">
                                <option value="">Módulo Principal</option>
                                @foreach($modulos as $modulo)
                                <?php ?>
                                <option value="{{ $modulo->id }}" @php if(old('id_modulo', @$store->id_modulo) == $modulo->id)
                                    echo "selected";
                                    @endphp>
                                    {{ $modulo->titulo }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" value="{{ old('titulo', @$store->titulo) }}" name="titulo">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="url_amigavel" class="form-label">URL Amigável</label>
                            <input type="text" class="form-control" id="url_amigavel" value="{{ old('url_amigavel', @$store->url_amigavel) }}" name="url_amigavel">
                        </div>
                        <div class="col-md-4">
                            <label for="icone" class="form-label">Ícone</label>
                            <input type="text" class="form-control" id="icone" value="{{ old('icone', @$store->icone) }}" name="icone">
                        </div>
                        <div class="col-md-4">
                            <label for="posicao" class="form-label">Posição</label>
                            <input type="number" class="form-control" id="posicao" value="{{ old('posicao', @$store->posicao) }}" name="posicao">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label for="acoes_permitidas" class="form-label">Ações Permitidas</label>
                            <select class="form-select select2-multiple" id="acoes_permitidas[]" name="acoes_permitidas[]" multiple="multiple" data-placeholder="Selecione as ações do Módulo">
                                @foreach($acoes_permitidas as $key=>$permitido)
                                @php
                                $selected = false;
                                if(isset($store) && $store->tipo_de_acao){
                                $selected = (isset($store->tipo_de_acao)
                                && str_contains($store->tipo_de_acao, $permitido))
                                ? "selected" : false;
                                }
                                @endphp
                                <option value="{{ $permitido }}" {{ $selected ?? '' }}>{{ $key }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="btn btn-primary font-weight-medium">Salvar</button>

                        <a href="{{ route('modulo') }}">
                            <button type="button" class="btn btn-danger font-weight-medium">Cancelar</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection