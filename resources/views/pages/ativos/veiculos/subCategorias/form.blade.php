@extends('dashboard')
@section('title', 'Obras')
@section('content')

<div class="page-header mt-5">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Cadastrar Sub Categoria
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
                $action = isset($editSubCategoria) ? route('ativo.veiculo.subCategoria.update', $editSubCategoria->id) : route('ativo.veiculo.subCategoria.store');
                @endphp
                <form method="post" enctype="multipart/form-data" action="{{ $action }}">
                    @csrf

                    <div class="d-flex">

                        <div class="col-md-3 mx-3">
                            <label class="form-label" for="nomeSubCategoria">Categoria</label>
                            <select class="form-select" id="id_categoria" name="id_categoria">

                                <option value="">Selecione uma Categoria</option>

                                @if(isset($editSubCategoria->id))


                                    @foreach ($categorias as $categoria)

<option value="{{ $categoria->id }}" {{ $editSubCategoria->id_categoria == $editSubCategoria->categorias->id ? 'selected' : '' }}>{{ $editSubCategoria->categorias->nomeCategoria }}</option>
                                    @endforeach

                                @else

                                    @foreach ($categorias as $categoria)

                                    <option value="{{ $categoria->id }}">{{ $categoria->nomeCategoria }}</option>
                                    @endforeach


                                @endif



                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="nomeSubCategoria">Nome da SubCategoria</label>
                            <input class="form-control" id="nomeSubCategoria" name="nomeSubCategoria" type="text" value="{{ old('nomeSubCategoria', @$editSubCategoria->nomeSubCategoria) }}">
                        </div>


                        <div class="col-md-3 mx-3">
                            <label class="form-label" for="statusSubCategoria">Status</label>
                            <select class="form-select" id="statusSubCategoria" name="statusSubCategoria">
                                <option value="Ativo" @php if(@$editSubCategoria->statusSubCategoria=="Ativo") echo 'selected' @endphp>Ativo</option>
                                <option value="Inativo" @php if(@$editSubCategoria->statusSubCategoria=="Inativo") echo 'selected' @endphp>Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mt-5 m-3">
                        <button class="btn btn-primary btn-lg font-weight-medium" type="submit">Salvar</button>

                        <a href="{{ url('admin/ativo/veiculo/subCategoria') }}">
                            <button class="btn btn-warning btn-lg font-weight-medium" type="button">Cancelar</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection