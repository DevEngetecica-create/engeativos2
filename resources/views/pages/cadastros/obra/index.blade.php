@extends('dashboard')
@section('title', 'Obras')
@section('content')


<div class="row">
    <div class="col-2 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-office-building-cog mdi-24px"></i>
            </span> Obras
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Cadastros <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>

</div>

<hr>
<div class="row my-4">
    <div class="col-2">
        <h3 class="page-title text-left">
            @if (session()->get('usuario_vinculo')->id_nivel <= 2) 
            <a href="{{ route('cadastro.obra.adicionar') }}">
                <span class="btn btn-sm btn-success">Novo Registro</span>
            </a>

                <span class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-add">Inclusão rápida</span>
                @endif
        </h3>
    </div>
    
    <div class="col-10">
        <form action="{{ route('obra') }}" method="GET" class="mb-4">
            @csrf
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="text" class="form-control shadow" name="obra" placeholder="Pesquisar Obra" value="{{ request()->obra }}">
                </div>
                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                    <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i class="mdi mdi-file-search-outline mdi-24px"></i></button>

                    <a href="{{ route('ferramental.retirada') }}" title="Limpar pesquisa">
                        <span class="btn btn-warning btn-sm py-0 shadow"><i class="mdi mdi-delete-outline mdi-24px"></i></span>
                    </a>
                </div>
                <div class="col-1 text-left m-0">

                </div>
            </div>
        </form>
    </div>
</div>


<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-hover align-middle table-nowrap mb-0">
            <tr>
                <th class="text-center" width="8%">ID</th>
                <th>Código da Obra</th>
                <th>CNPJ</th>
                <th>WhatsApp</th>
                <th>E-mail</th>
                <th>Status</th>
                @if (session()->get('usuario_vinculo')->id_nivel < 2) 
                
                    <th class="text-center" width="10%">Ações</th>

                @endif
            </tr>
            </thead>
            <tbody>
                @foreach ($lista as $obra)
                <tr>
                    <td class="text-center" >{{ $obra->id }}</td>
                    <td>{{ $obra->codigo_obra }}</td>
                    <td>{{ $obra->cnpj ?? '-' }}</td>
                    <td>{{ $obra->celular }}</td>
                    <td>{{ $obra->email }}</td>
                    <td>{{ $obra->status_obra }} </td>
                    @if (session()->get('usuario_vinculo')->id_nivel < 2) 
                    
                        <td class="d-flex justify-content-center">

                        <a class="btn btn-warning btn-sm" href="{{ route('cadastro.obra.editar', $obra->id) }}" title=" Editar">
                            <i class="mdi mdi-pencil"></i>
                        </a>

                        <form action="{{ route('cadastro.obra.destroy', $obra->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm mx-2"  data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                <i class="mdi mdi-delete"></i>
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

@include('pages.cadastros.obra.partials.inclusao-rapida')

@endsection