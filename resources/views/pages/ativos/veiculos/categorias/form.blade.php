@extends('dashboard')
@section('title', 'Obras')
@section('content')

    <div class="page-header mt-5">
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
                        $action = isset($editCategoria) ? route('cadastro.veiculo.categoria.update', $editCategoria->id) : route('cadastro.veiculo.categoria.store');
                    @endphp
                    <form method="post" enctype="multipart/form-data" action="{{ $action }}">
                        @csrf

                        <div class="d-flex">
                            

                            <div class="col-md-3">
                                <label class="form-label" for="nomeCategorIa">Nome da Categoria</label>
                                <input class="form-control" id="nomeCategoria" name="nomeCategoria" type="text" value="{{ old('nomeCategoria', @$editCategoria->nomeCategoria) }}">
                            </div>
                      
                        
                            <div class="col-md-3 mx-3">
                                <label class="form-label" for="statusCategoria">Status</label>
                                <select class="form-select" id="statusCategoria" name="statusCategoria">
                                    <option value="Ativo" @php if(@$editCategoria->statusCategoria=="Ativo") echo 'selected' @endphp>Ativo
                                    </option>
                                    <option value="Inativo" @php if(@$editCategoria->statusCategoria=="Inativo") echo 'selected' @endphp>
                                        Inativo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mt-5 m-3">
                            <button class="btn btn-primary btn-lg font-weight-medium" type="submit">Salvar</button>

                            <a href="{{ url('admin/ativo/veiculo/categoria') }}">
                                <button class="btn btn-warning btn-lg font-weight-medium" type="button">Cancelar</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
