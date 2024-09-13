@extends('dashboard')
@section('title', 'Tipos de Usuário')
@section('content')

<div class="row">
    <div class="col-2 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Tipos de Usuário
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Configurações <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>

</div>

<hr>

<form action="{{ route('usuario_tipo') }}" method="GET" class="mb-4">
    @csrf
    <div class="row my-4">
        <div class="col-2">
            <h3 class="page-title text-left">
                <a href="{{ route('usuario_tipo.adicionar') }}">
                    <span class="btn btn-sm btn-success shadow p-2">Novo Registro</span>
                </a>
            </h3>
        </div>
        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="text" class="form-control shadow" name="titulo" placeholder="Pesquisar" value="{{ request()->titulo }}">
                </div>
                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                    <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i class="mdi mdi-file-search-outline mdi-24px"></i></button>

                    <a href="{{ route('usuario_tipo') }}" title="Limpar pesquisa">
                        <span class="btn btn-warning btn-sm py-0 shadow"><i class="mdi mdi-delete-outline mdi-24px"></i></span>
                    </a>
                </div>
                <div class="col-1 text-left m-0">

                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle table-nowrap mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">ID</th>
                            <th>Tipo de Usuário</th>
                            <th class="text-center" width="10%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lista as $v)
                        <tr>
                            <td class="text-center">{{ $v->id }}</td>
                            <td>{{ $v->titulo }}</td>
                            <td class="text-center">
                                <a href="{{ route('usuario_tipo.editar', $v->id) }}">
                                    <button class="btn btn-info btn-sm"><i class="mdi mdi-pencil mdi-18x"></i></button>
                                </a>

                                <a href="javascript:void(0)" data-id="{{ $v->id }}" data-tabela="usuarios_niveis">
                                    <button class="btn btn-danger btn-sm" @if($permite_excluir==0) disabled @endif><i class="mdi mdi-delete mdi-18x"></i></button>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row mt-3">
                    <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">
                        <div class="paginacao">
                            {{$lista->render()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection