@extends('dashboard')
@section('title', 'Configurações de Ativos')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Configurações de Ativos
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
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
                $action = isset($store) ? route('ativo.configuracao.update', $store->id) : route('ativo.configuracao.store');
                @endphp
                <form method="post" enctype="multipart/form-data" action="{{ $action }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <label for="id_relacionamento" class="form-label">Categoria</label>
                            <select class="form-control select2" id="id_relacionamento" name="id_relacionamento">
                                <option value="0">Categoria Principal</option>
                                <?php foreach ($ativo_configuracoes as $atv) { ?>
                                    <option value="<?php echo $atv->id; ?>" @php if(old('id_relacionamento', @$store->id_relacionamento) == $atv->id) echo "selected"; @endphp><?php echo $atv->titulo; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="titulo" class="form-label">Sub-categoria</label>
                            <input type="text" class="form-control" id="titulo" value="{{ old('titulo', @$store->titulo) }}" name="titulo">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="Ativo" @php if(@$store->status=="Ativo") echo 'selected' @endphp>Ativo</option>
                                <option value="Inativo" @php if(@$store->status=="Inativo") echo 'selected' @endphp>Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg font-weight-medium">Salvar</button>

                            <a href="{{ route('ativo.configuracao') }}">
                                <button type="button" class="btn btn-danger btn-lg font-weight-medium">Cancelar</button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection