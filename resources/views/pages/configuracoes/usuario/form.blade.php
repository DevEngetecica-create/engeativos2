@extends('dashboard')
@section('title', 'Usuário')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Usuário
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

                @php $action = isset($store) ? route('usuario.update', $store->id) : route('usuario.store'); @endphp
                <form method="post" enctype="multipart/form-data" action="{{ $action }}">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-6">
                            <label class="form-label" for="id_funcionario">Funcionário</label>
                            <select class="form-select select2" name="id_funcionario" id="id_funcionario">
                                <option value="">Selecione um Funcionário</option>
                                @foreach ($funcionarios as $funcionario)
                                <option value="{{ $funcionario->id }}" @php if(@$store->vinculo->id_funcionario==$funcionario->id) echo 'selected' @endphp>
                                    {{ $funcionario->matricula }} - {{ $funcionario->nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        @if (Session::get('usuario_vinculo')['id_nivel'] < 2)
                        <div class="col-6">
                            <label class="form-label" for="nivel">Tipo de Usuário</label>
                            <select class="form-select" id="nivel" name="nivel">
                                <option value="">Escolha o Tipo de Usuário</option>
                                @foreach ($usuario_niveis as $nivel)
                                <option value="{{ $nivel->id }}" @php if(@$store->vinculo->id_nivel==$nivel->id) echo 'selected' @endphp>{{ $nivel->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                     
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label" for="password">Senha</label>
                            <input class="form-control" id="password" name="password" type="password">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="password_confirm">Confirmar Senha</label>
                            <input class="form-control" id="password_confirm" name="password_confirm" type="password">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="Ativo" @php if(@$store->status=="Ativo") echo 'selected' @endphp>Ativo
                                </option>
                                <option value="Inativo" @php if(@$store->status=="Inativo") echo 'selected' @endphp>
                                    Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-5">
                        <button class="btn btn-primary btn-lg font-weight-medium" type="submit">Salvar</button>

                        <a href="{{ route('usuario') }}">
                            <button class="btn btn-danger btn-lg font-weight-medium" type="button">Cancelar</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection