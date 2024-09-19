@extends('dashboard')
@section('title', 'Configurações de Ativos')
@section('content')


<div class="row">
    <div class="col-2 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-cog-counterclockwise mdi-24px"></i>
            </span> Categorias
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>

</div>

<hr>

<form action="{{ route('ativo.configuracao') }}" method="GET" class="mb-4">
    @csrf
    <div class="row my-4">
        <div class="col-2">
            <h3 class="page-title text-left">
                @if (session()->get('usuario_vinculo')->id_nivel <= 1) <a href="{{ route('ativo.configuracao.adicionar') }}">
                    <span class="btn btn-sm btn-success">Novo Registro</span>
                    </a>
                    @endif
            </h3>
        </div>

        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="text" class="form-control shadow" name="categoria" placeholder="Pesquisar categoria" value="{{ request()->categoria }}">
                </div>
                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                    <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i class="mdi mdi-file-search-outline mdi-24px"></i></button>

                    <a href="{{ route('ativo.configuracao') }}" title="Limpar pesquisa">
                        <span class="btn btn-warning btn-sm py-0 shadow"><i class="mdi mdi-delete-outline mdi-24px"></i></span>
                    </a>
                </div>
                <div class="col-1 text-left m-0">

                </div>
            </div>
        </div>
    </div>
</form>


<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-hover align-middle table-nowrap mb-0">
            <thead>
                <tr>
                    <th class="text-center" width="8%">ID</th>
                    <th>Categoria</th>
                    <th>Sub-categoria</th>
                    <th class="text-center">Status</th>
                    <th class="text-center {{ session()->get('usuario_vinculo')->id_nivel <= 1 ? 'd-block' : 'd-none' }}" width="10%">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lista as $categoria)
                <tr>
                    <td class="text-center">{{ $categoria->id }}</td>
                    <td>{{ ($categoria->vinculo) ?? 'Categoria Principal' }}</td>
                    <td>{{ $categoria->titulo }}</td>
                    <td class="text-center">{{ $categoria->status }}</td>
                    <td class="d-flex justify-content-center {{ session()->get('usuario_vinculo')->id_nivel <= 1 ? 'd-block' : 'd-none' }}"> 
                        <a href="{{ url('admin/ativo/configuracao/editar/'.$categoria->id) }}" title="Editar">
                            <button class="btn btn-info btn-sm mx-2" data-toggle="tooltip" data-placement="top" title="Editar"><i class="mdi mdi-pencil"></i></button>
                        </a>

                        <a href="javascript:void(0)" class="excluir-padrao" data-id="{{ $categoria->id }}" data-table="users" data-module="ativo/configuracao" data-redirect="{{ route('modulo') }}">
                            <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Excluir"><i class="mdi mdi-delete"></i></button>
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


@endsection