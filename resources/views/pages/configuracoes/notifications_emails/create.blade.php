@extends('dashboard')
@section('title', 'Notificações por email')
@section('content')

<div class="card p-5">
    <div class="card-body ">

        <div class="row ">
            <div class="col-6 breadcrumb-item active" aria-current="page">
                <h3 class="page-title text-left">
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-cog-counterclockwise mdi-24px"></i>
                    </span> Grupos de notificações por email
                </h3>
            </div>

            <div class="col-4 active m-2">
                <h5 class="page-title text-left m-0">
                    <span>Cadastro <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
                </h5>
            </div>
        </div>

        <hr>

        <div class="row mt-5">
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

                        <form method="post" enctype="multipart/form-data" action="{{ route('notificatio_email.store') }}">
                            @csrf

                            <div class="row my-4">
                                <div class="col-md-4">
                                    <label class="form-label" for="codigo_obra">Onde será notificado?</label>
                                    <select id="form-control form-select" name="id_modulo" id="id_modulo">
                                        <option value="" selected>
                                           Selecione um módulo para notificar
                                        </option>
                                        
                                    
                                        @foreach ($modulos_permitidos as $module)
                                         
                                            @if (count($module['submodulos']) > 0)
                                                <optgroup label="{{ $module['titulo'] }}">
                                                    @foreach ($module['submodulos'] as $sub)
                                                        <option value="{{ $sub['id'] }}">{{ $sub['titulo'] }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option value="{{ $module['id'] }}">
                                                    <i class="{{ $module['icone'] }}"></i> {{ $module['titulo'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Qual método?</label>
                                    
                                </div>
                                
                                <div class="col-md-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="add" id="add" name="metodo[]" multiple="multiple">
                                        <label class="form-check-label" for="flexCheckDefault">
                                        Cadastrar
                                      </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="edit" id="edit" name="metodo[]" multiple="multiple">
                                        <label class="form-check-label" for="flexCheckDefault">
                                        Editar
                                      </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"  value="delete" id="delete" name="metodo[]" multiple="multiple">
                                        <label class="form-check-label" for="edit">
                                        Excluir
                                      </label>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row my-4">
                                <div class="col-md-3">
                                    <label class="form-label" for="codigo_obra">Nome do Grupo</label>
                                    <input class="form-control" id="nome_grupo" name="nome_grupo" type="text" value="{{ old('nome_grupo')}}">
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label" for="codigo_obra">Usuarios</label>

                                    <select class="form-select select2-multiple" id="id_usuario[]" name="id_usuario[]" multiple="multiple" data-placeholder="Selecionar usuários">

                                        @foreach($email_usuarios as $email_usuario)
                                        <option value="{{ $email_usuario->id }}">{{ $email_usuario->email }}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="obra_id">Obra </label>
                                    <select class="form-select select2" id="id_obra" name="id_obra">
                                        <option value="">Selecione uma obra</option>

                                        @foreach ($obras as $obra)
                                        <option value="{{ $obra->id ?? old('id_obra')}}">{{ $obra->codigo_obra }} | {{ $obra->razao_social }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="id_status">Situação</label>

                                    <select class="form-select" id="iid_status" name="id_status">
                                        <option selected>Selecionar</option>
                                        <option value="6">Em Operação</option>
                                        <option value="9">Fora de Operação</option>

                                    </select>

                                </div>
                            </div>

                            <div class="row mt-3">

                                <div class="col-12 mt-5">
                                    <button class="btn btn-primary btn-md font-weight-medium" type="submit">Salvar</button>

                                    <a href="{{ route('obra') }}">
                                        <button class="btn btn-danger btn-mf font-weight-medium" type="button">Cancelar</button>
                                    </a>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection