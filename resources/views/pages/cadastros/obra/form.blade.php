@extends('dashboard')
@section('title', 'Obras')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Cadastro de Obras
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Cadastros <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
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

                @php
                $action = isset($store) ? route('cadastro.obra.update', $store->id) : route('cadastro.obra.store');
                @endphp
                <form method="post" enctype="multipart/form-data" action="{{ $action }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label" for="codigo_obra">Código da Obra</label>
                            <input class="form-control" id="codigo_obra" name="codigo_obra" type="text" value="{{ old('codigo_obra', @$store->codigo_obra)}}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            @include('components.fields.id_empresa')
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="cnpj">CNPJ</label>
                            <input class="form-control cnpj" id="cnpj" name="cnpj" type="text" value="{{ old('cnpj', @$store->cnpj) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="nome_fantasia">Nome fantasia</label>
                            <input class="form-control" id="nome_fantasia" name="nome_fantasia" type="text" value="{{ old('nome_fantasia', @$store->nome_fantasia) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="razao_social">Razão Social</label>
                            <input class="form-control" id="razao_social" name="razao_social" type="text" value="{{ old('razao_social', @$store->razao_social) }}">
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label" for="cep">CEP</label>
                            <input class="form-control cep" id="cep" name="cep" type="text" value="{{ old('cep', @$store->cep) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="endereco">Endereço</label>
                            <input class="form-control" id="endereco" name="endereco" type="text" value="{{ old('endereco', @$store->endereco) }}">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label" for="numero">Número</label>
                            <input class="form-control" id="numero" name="numero" type="text" value="{{ old('numero', @$store->numero) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="bairro">Bairro</label>
                            <input class="form-control" id="bairro" name="bairro" type="text" value="{{ old('bairro', @$store->bairro) }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label" for="cidade">Cidade</label>
                            <input class="form-control" id="cidade" name="cidade" type="text" value="{{ old('cidade', @$store->cidade) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="estado">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="">Selecione o Estado</option>
                                @if (url()->current() == route('cadastro.obra.adicionar'))
                                @foreach ($estados as $sigla => $estado)
                                <option value="{{ $sigla }}" {{ old('estado') == $sigla ? 'selected' : '' }}>{{ $estado }}</option>
                                @endforeach
                                @else
                                @foreach ($estados as $sigla => $estado)
                                <option value="{{ $sigla }}" @php if(@$store->estado==$sigla) echo 'selected' @endphp>{{ $estado }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="latitude">Latitude</label>
                            <input class="form-control" id="latitude" name="latitude" type="text" value="{{ old('latitude', @$store->latitude) }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="longitude">Longitude</label>
                            <input class="form-control" id="longitude" name="longitude" type="text" value="{{ old('longitude', @$store->longitude) }}">
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label" for="email">E-mail</label>
                            <input class="form-control" id="email" name="email" type="email" value="{{ old('email', @$store->email) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="celular">Celular / WhatsApp</label>
                            <input class="form-control celular" id="celular" name="celular" type="text" value="{{ old('celular', @$store->celular) }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label" for="status">Status</label>

                            <select class="form-select form-select-sm  mb-3" id="status_obra" name="status_obra" aria-label=".form-select-sm example">
                               
                                <option value="Ativo" @php if(@$store->status_obra == "Ativo") echo 'selected' @endphp>Ativo</option>
                                <option value="Inativo" @php if(@$store->status_obra == "Inativo") echo 'selected' @endphp>Inativo</option>
                            </select>

                        </div>

                        <div class="col-12 mt-5">
                            <button class="btn btn-primary btn-md font-weight-medium" type="submit">Salvar</button>

                            <a href="{{ route('obra') }}">
                                <button class="btn btn-danger btn-mf font-weight-medium" type="button">Cancelar</button>
                            </a>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection