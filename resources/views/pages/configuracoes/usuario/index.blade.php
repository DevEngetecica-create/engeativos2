@extends('dashboard')
@section('title', 'Usuários')
@section('content')


<div class="row">
    <div class="col-2 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Usuários
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Configurações <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>

</div>

<hr>
  
<form action="{{ route('usuario') }}" method="GET" class="mb-4">
    @csrf
    <div class="row my-4">
        <div class="col-2">
            <h3 class="page-title text-left">
                <a href="{{ route('usuario.adicionar') }}">
                    <span class="btn btn-sm btn-success shadow p-2">Novo Registro</span>
                </a>
            </h3>
        </div>
        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="text" class="form-control shadow" name="usuario" placeholder="Pesquisar categoria" value="{{ request()->usuario }}">
                </div>
                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                    <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i class="mdi mdi-file-search-outline mdi-24px"></i></button>

                    <a href="{{ route('usuario') }}" title="Limpar pesquisa">
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
                <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle table-nowrap mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="8%">ID</th>
                            <th>Tipo de Usuário</th>
                             <th>Nome</th>
                             <th>Funcionário</th>
                            <th>E-mail</th>
                            <th>Obra</th>

                            @if (Session::get('usuario_vinculo')['id_nivel'] < 2)

                                <th>Status</th>
                                <th class="text-center" width="10%">Ações</th>
                            
                            @endif


                           
                           
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($lista as $user)
                        
                            <tr>
                                <td class="text-center m-0 "><span>{{ $user->id }}</span></td>
                                <td class=" m-0">{{ $user->nivel }}</td>
                                <td class="m-0">{{ $user->vinculo->vinculo_funcionario->nome ?? '-' }}</td>
                                <td class="m-0">{{ $user->name }}</td>
                                <td class=" m-0">{{ $user->email }}</td>
                                <td class="m-0">{{ $user->vinculo->vinculo_obra->codigo_obra ?? '-' }}</td>
    
                                @if (Session::get('usuario_vinculo')['id_nivel'] < 2)
                                
                                <td class="m-0">
                                    
                                    @php
                                        $action = $user->bloqueado ? route('usuario.desbloquear', $user->id) : route('usuario.bloquear', $user->id);
                                    @endphp
                                    
                                    <form action="{{ $action }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-{{$user->bloqueado ? 'danger' : 'success'}}" type="submit">
                                            {{$user->bloqueado ? 'Inativo' : 'Ativo'}}
                                        </button>
                                    </form>
                                    
                                </td>

                                    <td class="d-flex justify-content-center m-0">
                                        @if (session()->get('usuario_vinculo')->id_nivel < 2) 
                                            <a href="{{ route('usuario.editar', $user->id) }}" title="Editar">
                                                <button type="button" class="btn btn-warning mx-2 btn-sm "><i class="mdi mdi-pencil"></i></button>
                                            </a>
        
                                        @endif
        
                                        @if (session()->get('usuario_vinculo')->id_nivel < 2)
                                            <form action="{{ route('usuario.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
        
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>                                       
                                            </form>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                </div>
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