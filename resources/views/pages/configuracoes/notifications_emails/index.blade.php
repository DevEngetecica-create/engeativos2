@extends('dashboard')
@section('title', 'Configurações de Ativos')
@section('content')


<div class="row">
    <div class="col-6 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-left">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-cog-counterclockwise mdi-24px"></i>
            </span> Grupos de notificações por email
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Configurações <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>
</div>

<hr>

<form action="{{ route('ativo.configuracao') }}" method="GET" class="mb-4">
    @csrf
    <div class="row my-4">
        <div class="col-2">
            <h3 class="page-title text-left">
                <a href="{{ route('notificatio_email.adicionar') }}">
                    <span class="btn btn-sm btn-success">Novo Registro</span>
                </a>
            </h3>
        </div>

        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="text" class="form-control shadow" name="grupo" id="grupo" placeholder="Pesquisar" value="{{ request()->categoria }}">
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
        <div class="table-responsive">

            <table class="table table-bordered table-hover table-sm align-middle table-nowrap mb-0" whidth="100%">
                <thead>
                    <tr>
                        <th class="text-center" whidth="25%">ID</th>
                        <th>Obras</th>
                        <th>Grupo</th>
                        <th  class="text-center" >Emails</th>
    
                        <th class="text-center">Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notificacoes_emails as $lista)
                    <tr>
                        <td class="text-center">{{ $lista->id }}</td>
                        <td>{{$lista->obra->nome_fantasia}}</td>
                        <td>{{ $lista->nome_grupo }}</td>
                        <td class="d-flex flex-wrap" whidth="25%">
                             @foreach ($lista->id_usuario as $id_mail)
    
                                <span class="btn btn-outline-primary btn-sm position-relative mx-2 mb-2">
                                    {{ $emails[$id_mail] ?? 'Email não encontrado' }}
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill border bg-white ">
                                        <i class="mdi mdi-account-check-outline text-success mdi-18px"></i>                                        
                                    </span>
                                </span>
    
                                @endforeach
                           
                        </td>
    
                        <td class="text-center">
                            <span class="btn btn-outline-{{$lista->status->classe}} btn-sm position-relative mx-2">{{$lista->status->titulo}}</span>
                        </td>
    
                        <td class="d-flex  justify-content-center gap-2 align-middle">
                            <a  href="{{ route('notificatio_email.editar', $lista->id) }}">
                                <button class="btn btn-warning btn-sm">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                            </a>
    
                            <a class="  mb-2 mx-2"  href="{{ route('notificatio_email.show', $lista->id) }}">
                                <button class="btn btn-info btn-sm">
                                    <i class="mdi mdi-eye"></i>
                                </button>
                            </a>
    
                            <form action="{{ route('notificatio_email.destroy', $lista->id) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger btn-sm" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
    
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row mt-3">
            <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">
                <div class="paginacao">
                    {{$notificacoes_emails->render()}}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection