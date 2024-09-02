@extends('dashboard')
@section('title', 'Empresas')
@section('content')


<div class="row">
    <div class="col-2 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-office-building-cog mdi-24px"></i>
            </span> Empresas
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Cadastros <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>

</div>

<hr>

<form action="{{ route('empresa') }}" method="GET" class="mb-4">
    @csrf
    <div class="row my-4">
        <div class="col-2">
            <h3 class="page-title text-left">
                @if (session()->get('usuario_vinculo')->id_nivel <= 2) 
                    <a href="{{ route('cadastro.empresa.adicionar') }}">
                        <button class="btn btn-sm btn-success">Novo Registro</button>
                    </a>
                @endif
            </h3>
        </div>

        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="text" class="form-control shadow" name="empresa" placeholder="Pesquisar categoria" value="{{ request()->empresa }}">
                </div>
                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                    <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i class="mdi mdi-file-search-outline mdi-24px"></i></button>

                    <a href="{{ route('empresa') }}" title="Limpar pesquisa">
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
                    <th>CNPJ</th>
                    <th>Razão Social</th>
                    <th>WhatsApp</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    @if (session()->get('usuario_vinculo')->id_nivel < 2) <th width="15%">Ações</th>
                        @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($lista as $empresa)
                <tr>
                    <td class="text-center">{{ $empresa->id }}</td>
                    <td>{{ $empresa->cnpj ?? '-' }}</td>
                    <td>{{ $empresa->razao_social }}</td>
                    <td>{{ $empresa->celular }}</td>
                    <td>{{ $empresa->email }}</td>
                    <td>{{ $empresa->status }} </td>
                    @if (session()->get('usuario_vinculo')->id_nivel < 2) <td class="d-flex justify-itens-between">
                        <a href="{{ route('cadastro.empresa.editar', $empresa->id) }}">
                            <button class="btn btn-info btn-sm mx-2" title="Editar"><i class="mdi mdi-pencil mdi-18x"></i></button>
                        </a>
                        <form action="{{ route('cadastro.empresa.destroy', $empresa->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                <i class="mdi mdi-delete mdi-18x"></i>
                            </button>
                        </form>
                        </td>
                        @endif
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