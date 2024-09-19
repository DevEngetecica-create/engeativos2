@extends('dashboard')
@section('title', 'Módulos')
@section('content')


<div class="row">
    <div class="col-2 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Módulos
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Configurações <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>

</div>

<hr>

<form action="{{ route('modulo') }}" method="GET" class="mb-4">
    @csrf
    <div class="row my-4">
        <div class="col-2">
            <h3 class="page-title text-left">
                <a href="{{ route('modulo.adicionar') }}">
                    <span class="btn btn-sm btn-success shadow p-2">Novo Registro</span>
                </a>
            </h3>
        </div>
        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="text" class="form-control shadow" name="modulo" placeholder="Pesquisar categoria" value="{{ request()->modulo }}">
                </div>
                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                    <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i class="mdi mdi-file-search-outline mdi-24px"></i></button>

                    <a href="{{ route('modulo') }}" title="Limpar pesquisa">
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
                            <th>Módulo</th>
                            <th>Título</th>
                            <th>Url</th>
                            <th class="text-center" width="14%">Ações Permitidas</th>
                            <th class="text-center" width="10%">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($lista as $v)


                        @php

                        $btn = array();
                        $btn[] = (isset($v->tipo_de_acao) && str_contains($v->tipo_de_acao, 'view'))
                        ? '<span class="bg-danger px-2 rounded text-white"><i class="mdi mdi-eye" title="Visualizar" data-toggle="tooltip" data-placement="top"></i></span>' : false;

                        $btn[] = (isset($v->tipo_de_acao) && str_contains($v->tipo_de_acao, 'add'))
                        ? '<span class="bg-primary px-2 rounded text-white mx-2"><i class="mdi mdi-plus" title="Adicionar" data-toggle="tooltip" data-placement="top"></i></span>' : false;

                        $btn[] = (isset($v->tipo_de_acao) && str_contains($v->tipo_de_acao, 'edit'))
                        ? '<span class="bg-success px-2 rounded text-white"><i class="mdi mdi-pencil" title="Editar" data-toggle="tooltip" data-placement="top"></i></span>' : false;

                        $btn[] = (isset($v->tipo_de_acao) && str_contains($v->tipo_de_acao, 'delete'))
                        ? '<span class="bg-info px-2 rounded text-white mx-2"><i class="mdi mdi-delete" title="Excluir" data-toggle="tooltip" data-placement="top"></i></span>' : false;

                        $btn[] = (isset($v->tipo_de_acao) && str_contains($v->tipo_de_acao, 'other'))
                        ? '<span class="bg-warning px-2 rounded text-white"><i class="mdi mdi-all-inclusive" title="Outros" data-toggle="tooltip" data-placement="top"></i></span>' : false;

                        @endphp


                        <tr>
                            <td class="text-center">{{ $v->id }}</td>
                            <td>{{ ($v->vinculo) ?? '-' }}</td>
                            <td>{{ $v->titulo }}</td>
                            <td>{{ $v->url_amigavel }}</td>
                            <td>
                                @php
                                if($btn){
                                foreach($btn as $button){
                                echo $button;
                                }
                                }
                                @endphp
                            </td > 
                            <td class="text-center">
                                <a href="{{ route('modulo.editar', $v->id) }}">
                                    <button class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Editar"><i class="mdi mdi-pencil mdi-18x"></i></button>
                                </a>

                                <a href="javascript:void(0)" class="excluir-padrao" data-id="{{ $v->id }}" data-table="users" data-module="configuracao/modulo" data-redirect="{{ route('modulo') }}">
                                    <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Excluir"><i class="mdi mdi-delete mdi-18x"></i> </button>
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