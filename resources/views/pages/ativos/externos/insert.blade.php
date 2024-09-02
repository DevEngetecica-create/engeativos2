@extends('dashboard')
@section('title', 'Ativos Externos')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Incluir novo Ativo Externo
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
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

                <form method="post" action="{{ route('ativo.externo.inserir.store') }}">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label" for="categoria">Categoria</label>
                            <input class="form-control" name="categoria" type="text" value="{{ $ativo->configuracao->titulo }}" readonly>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label" for="id_obra">Obra</label> <button class="badge badge-primary" data-toggle="modal" data-target="#modal-add" type="button"><i class="mdi mdi-plus"></i></button>
                            <select class="form-select select2" id="id_obra" name="id_obra" required>
                                <option value="">Selecione uma Obra</option>
                                @foreach ($obras as $obra)
                                <option value="{{ $obra->id }}">
                                    {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label" for="titulo">Título</label>
                            <input class="form-control" id="titulo" name="titulo" type="text" value="{{ $ativo->titulo ?? old('titulo') }}" readonly>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" for="status">Quantidade</label>
                            <input class="form-control" id="quantidade" name="quantidade" type="number" value="{{ old('quantidade') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" for="status">Valor</label>
                            <input class="form-control money" id="valor" name="valor" type="text" value="{{ old('valor') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" for="calibracao">Precisa Calibrar?</label>
                            <select class="form-select select2" id="calibracao" name="calibracao">
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select select2" id="status" name="status">
                                <option value="Ativo" selected>Em Estoque</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <input name="id_ativo_configuracao" type="hidden" value="{{ $ativo->id_ativo_configuracao }}">
                        <input name="id_ativo_externo" type="hidden" value="{{ $ativo->id }}">
                        <button class="btn btn-gradient-dark btn-lg font-weight-medium" type="submit">Gravar novos Ativos</button>
                        <a href="{{ route('ativo.externo') }}">
                            <button class="btn btn-gradient-danger btn-lg font-weight-medium" type="button">Cancelar</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL INCLUSAO RAPIDA DE OBRAS --}}
@include('pages.cadastros.obra.partials.inclusao-rapida')
@endsection