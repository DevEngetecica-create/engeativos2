@extends('dashboard')
@section('title', 'Tipos de Usuário')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Tipos de Usuário
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

                @php $action = isset($store) ? route('usuario_tipo.update', $store->id) : route('usuario_tipo.store'); @endphp
                <form method="post" enctype="multipart/form-data" action="{{ $action }}">
                    @csrf

                    @if(isset($store))
                    <input type="hidden" name="id" value="{{ $store->id }}">
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <label for="nome" class="form-label">Título</label>
                            <input type="text" class="form-control" id="nome" value="{{ isset($store) ? $store->titulo : '' }}" name="nome" placeholder="Tipo de Usuário" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <label for="nivel" class="form-label">Módulos Permitidos</label>
                            <table class="table table-bordered table-hover align-middle table-nowrap mb-0table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center"> ID </th>
                                        <th class="text-center"> Módulos </th>
                                        <th class="text-center"> Permissões </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modulos as $modulo)

                                    <tr>
                                        <td class="text-center">{{ $modulo->id }} </td>
                                        <td >{{ $modulo->titulo }}</td>
                                        <td>

                                            <table class="table table-striped table-sm">

                                                <?php

                                                $visualizar = false;
                                                $adicionar = false;
                                                $editar = false;
                                                $excluir = false;
                                                $outros = false;


                                                foreach ($modulo->submodulos as $submodulo) {

                                                    $outros = false;
                                                    $tipo_de_acao = $submodulo->tipo_de_acao;

                                                    $visualizar = (isset($tipo_de_acao) && str_contains($tipo_de_acao, 'view')) ? true : false;
                                                    $adicionar = (isset($tipo_de_acao) && str_contains($tipo_de_acao, 'add')) ? true : false;
                                                    $editar = (isset($tipo_de_acao) && str_contains($tipo_de_acao, 'edit')) ? true : false;
                                                    $excluir = (isset($tipo_de_acao) && str_contains($tipo_de_acao, 'delete')) ? true : false;
                                                    $outros = (isset($tipo_de_acao) && str_contains($tipo_de_acao, 'other')) ? true : false;

                                                    $permissoes = (isset($store)) ? json_decode($store->permissoes) : [];




                                                    /* Verificação se existe no banco de dados para marcar */
                                                    $view_db = false;
                                                    $add_db = false;
                                                    $edit_db = false;
                                                    $delete_db = false;
                                                    $other_db = false;

                                                    if (isset($permissoes)) {

                                                        foreach ($permissoes as $key => $val) {

                                                            /* Foreach de verificação simples */
                                                            foreach ($val as $key_val => $item) {

                                                                $item = (array) $item;

                                                                foreach ($item as $its => $value) {

                                                                    if ($its == "view" && $value == "on" && $key_val == $submodulo->id) {
                                                                        $view_db = true;
                                                                    }

                                                                    if ($its == "add" && $value == "on" && $key_val == $submodulo->id) {
                                                                        $add_db = true;
                                                                    }

                                                                    if ($its == "edit" && $value == "on" && $key_val == $submodulo->id) {
                                                                        $edit_db = true;
                                                                    }

                                                                    if ($its == "delete" && $value == "on"  && $key_val == $submodulo->id) {
                                                                        $delete_db = true;
                                                                    }

                                                                    if ($its == "other" && $value == "on" && $key_val == $submodulo->id) {
                                                                        $other_db = true;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>

                                                    <tr>
                                                        <td width="30%">{{ $submodulo->titulo }}</td>
                                                        <td width="14%">
                                                            @php if($visualizar){ @endphp
                                                            <div class="form-check form-check-warning">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" name="permission[{{$modulo->id}}][{{$submodulo->id}}][view]" @if($view_db==true) checked @endif> Visualizar <i class="input-helper"></i></label>
                                                            </div>
                                                            @php } else{ @endphp
                                                            <div class="form-check form-check-warning disabled-text">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" disabled class="form-check-input"> Visualizar <i class="input-helper"></i></label>
                                                            </div>
                                                            @php } @endphp
                                                        </td>
                                                        <td width="14%">
                                                            @php if($adicionar==true){ @endphp
                                                            <div class="form-check form-check-warning">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" name="permission[{{$modulo->id}}][{{$submodulo->id}}][add]" @if($add_db==true) checked @endif> Adicionar <i class="input-helper"></i></label>
                                                            </div>
                                                            @php } else{ @endphp
                                                            <div class="form-check form-check-warning disabled-text">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" disabled class="form-check-input"> Adicionar <i class="input-helper"></i></label>
                                                            </div>
                                                            @php } @endphp
                                                        </td>
                                                        <td width="14%">
                                                            @php if($editar==true){ @endphp
                                                            <div class="form-check form-check-warning">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" name="permission[{{$modulo->id}}][{{$submodulo->id}}][edit]" @if($edit_db==true) checked @endif> Editar <i class="input-helper"></i></label>
                                                            </div>
                                                            @php } else{ @endphp
                                                            <div class="form-check form-check-warning disabled-text">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" disabled class="form-check-input"> Editar <i class="input-helper"></i></label>
                                                            </div>
                                                            @php } @endphp
                                                        </td>
                                                        <td width="14%">
                                                            @php if($excluir==true){ @endphp
                                                            <div class="form-check form-check-warning">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" name="permission[{{$modulo->id}}][{{$submodulo->id}}][delete]" @if($delete_db==true) checked @endif> Excluir <i class="input-helper"></i></label>
                                                            </div>
                                                            @php } else{ @endphp
                                                            <div class="form-check form-check-warning disabled-text">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" disabled class="form-check-input"> Excluir <i class="input-helper"></i></label>
                                                            </div>
                                                            @php } @endphp
                                                        </td>
                                                        <td width="14%">
                                                            @php if($outros==true){ @endphp
                                                            <div class="form-check form-check-warning">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" name="permission[{{$modulo->id}}][{{$submodulo->id}}][other]" @if($other_db==true) checked @endif> Outros <i class="input-helper"></i></label>
                                                            </div>
                                                            @php } else{ @endphp
                                                            <div class="form-check form-check-warning disabled-text">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" disabled class="form-check-input"> Outros <i class="input-helper"></i></label>
                                                            </div>
                                                            @php } @endphp
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="btn btn-primary btn-md font-weight-medium">Salvar</button>

                        <a href="{{ route('dashboard') }}">
                            <button type="button" class="btn btn-danger btn-md font-weight-medium">Cancelar</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




@endsection