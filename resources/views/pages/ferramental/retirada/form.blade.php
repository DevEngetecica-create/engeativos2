@extends('dashboard')
@section('title', 'Retirada de Ferramentas')
@section('content')

<div class="container mt-5">
    <form method="post" class="form" enctype="multipart/form-data" id="form">
        @csrf

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon3">Pesquisar</span>
            <input type="hidden" id="page" name="page" value="0">
            <input type="text" class="form-control" id="search" name="search">
        </div>
    </form>
</div>

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

@if (Auth::user()->user_level == 1)
<div class="row">
    <div class="col-12">
        @include('components.fields.id_obra')
    </div>
</div>
@endif

@if (Auth::user()->user_level >= 2)
<input id="id_obra" name="id_obra" type="hidden" value="{{ session('obra')->id_obra }}">
@endif

@php
$action = isset($store) ? route('ferramental.retirada.update', $store->id) : route('ferramental.retirada.store');
@endphp

<form method="post" action="{{ route('ferramental.retirada.store') }}">
    @csrf
    <div class="row mt-2">

        <div class="col-md-7">

            <div class="card">
                <div class="card-header bg-primary text-center  shadow-sm ">
                    <h3 class="card-title m-auto text-white ">Lista de Ferramentas <span class="loader"></span></h3>
                </div>

                <div id="conteudo">

                </div>
            </div>

        </div>

        <div class="col-md-5">

            <div class="card shadow-sm" style="height: 720px !important">
                <div class="card-header  bg-primary text-center  shadow-sm">
                    <h3 class="card-title m-auto text-white">Dados da retirada</h3>
                </div>

                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label" for="id_obra">Obra</label> <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-add" type="button"><i class="mdi mdi-plus"></i></button>
                            <select class="form-select select2" id="id_obra" name="id_obra" required>
                                <option value="">Selecione uma Obra</option>
                                @foreach ($obras as $obra)
                                <option value="{{ $obra->id }}" {{ old('id_obra') == $obra->id ? 'selected' : '' }}>
                                    {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label" id="helper" for="id_funcionario">Funcionário</label>

                            <select class="form-select select2" id="id_funcionario" name="id_funcionario">
                                <option value="">Selecione um Funcionário</option>
                                @foreach ($funcionarios as $funcionario)
                                <option value="{{ $funcionario->id }}" @php if(old('id_funcionario', @$store->id_funcionario) == $funcionario->id) echo "selected"; @endphp>
                                    {{ $funcionario->matricula }} - {{ $funcionario->nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">


                        <div class="col-6">
                            <label class="form-label" for="data_solicitacao">Data de Solicitação</label>
                            <input class="form-control" name="data_solicitacao" type="date" value="@php echo date('Y-m-d'); @endphp" disabled>
                        </div>

                        <div class="col-6">
                            <label class="form-label" for="devolucao_prevista">Devolução Prevista</label>
                            <input class="form-control" id="devolucao_prevista" name="devolucao_prevista" type="datetime-local" value="">
                        </div>

                    </div>

                    <div class="row mt-4">
                        <h5>Ferramentas a serem retiradas</h5>
                        <div class="col-12 mt-3 border border-1 rounded">

                            <div class="d-flex flex-wrap" id="ferramentasSeleciondas" style="max-height: 200px;overflow-y: auto;">


                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer clearfix">

                    <button class="btn btn-primary font-weight-medium" id="btn-submit" type="submit">Salvar</button>

                    <a href="{{ route('ferramental.retirada') }}">
                        <button class="btn btn-danger font-weight-medium" type="button">Cancelar</button>
                    </a>

                </div>
            </div>
        </div>
</form>

</div>



{{-- MODAL INCLUSAO RAPIDA DE OBRAS --}}
@include('pages.cadastros.obra.partials.inclusao-rapida')

@endsection

@section('script')

<script>
    $(document).ready(function() {
        
        $('#id_obra').select2();
        $('#id_funcionario').select2();
        
        $('#id_funcionario').change(function() {
            var usuario = $(this).val();
            var url = "{{ route('ferramental.retirada.bloqueio', ':usuario') }}".replace(':usuario', usuario);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var quantidade = response.quantidade;

                    if (quantidade >= 1) {
                        $('#helper').html('<span class="text-danger"><strong>FUNCIONÁRIO BLOQUEADO</strong></span>');
                        $('#devolucao_prevista').attr('disabled', 'disabled');
                        $('#observacoes').attr('disabled', 'disabled');
                        $('#btn-submit').attr('disabled', 'disabled');
                    } else {
                        $('#helper').html('<span class="text-primary">Nenhum bloqueio encontrado</span>');
                        $('#devolucao_prevista').removeAttr('disabled');
                        $('#observacoes').removeAttr('disabled');
                        $('#btn-submit').removeAttr('disabled');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });



        carregarTabela();

    });


    $(document).on('click', '.paginacao a', function(e) {
        e.preventDefault();
        var pagina = $(this).attr('href').split('page=')[1];
        carregarTabela(pagina);
    });
    $(document).on('keyup submit', '.form', function(e) {
        e.preventDefault();
        carregarTabela();
    });

    function carregarTabela(pagina) {
        //$('.loader').html('<div class="spinner-border m-0 p-0" role="status"><span class="sr-only"></span></div>');
        $('#page').val(pagina);
        var dados = $('#form').serialize();
        $.ajax({
            url: "/admin/ferramental/retirada/list",
            method: 'GET',
            data: dados
        }).done(function(data) {
            $('#conteudo').html(data);
        });
    }
</script>

@endsection