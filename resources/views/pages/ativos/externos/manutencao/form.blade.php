@extends('dashboard')
@section('title', 'Manutenção de Ativos')
@section('content')


<div class="page-header mt-5">
    <h3 class="page-title">
        <span class="page-title-icon bg-primary me-2 text-white">
            <i class="mdi mdi-settings 24-px"></i>
        </span> Manutenção de Ativos Externos
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Cadastros <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
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

@php
$action = isset($editAtivos) ? route('ativo.externo.manutencao.update', $editAtivos->id) : route('ativo.externo.manutencao.store');
@endphp
<form method="post" enctype="multipart/form-data" action="{{ $action }}">
    @csrf
    <div class="row">


        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-header  bg-primary text-center  shadow-sm">
                    <h3 class="card-title m-auto text-white">Dados da retirada</h3>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="row mb-3">
                            <label class="form-label" for="obra">Obra</label>
                            <select class="form-select form-control select2" id="id_obra" name="id_obra">

                                @foreach ($obras as $obra)
                                <option value="{{ $obra->id }}" {{ @$editLocacaoVeiculos->obra->id == $obra->id ? 'selected' : '' }}>
                                    {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label" for="obra">Patrimonio</label>
                                <select class="form-select form-control" id="patrimonio" name="patrimonio">
                                    <option value="" selected>Selecionar</option>
                                    @foreach ($ativosExternosEsqtoque as $ativosExternos)
                                    <option value="{{$ativosExternos->patrimonio }}" {{ @$editAtivos->id_ativo_externo_estoque == $ativosExternos->id ? 'selected' : '' }}>
                                        {{ $ativosExternos->patrimonio }}
                                    </option>



                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">ID:</label>
                                <input type="text" class="form-control" id="id_ativo_externo_estoque" name="id_ativo_externo_estoque" value="@if(@$editAtivos->id_ativo_externo_estoque)  {{@$editAtivos->id_ativo_externo_estoque}} @else @endif" readonly>

                                <input type="hidden" class="form-control" id="id_ativo_externo" name="id_ativo_externo" value="@if(@$ativosExternos->id == @$editAtivos->id_ativo_externo_estoque)  {{@$editAtivos->id_ativo_externo}} @else  @endif">

                            </div>

                            <div class="col-7">
                                <label class="form-label">Nome do Ativo</label>
                                <input type="text" class="form-control" id="nomeAtivo" name="nomeAtivo" value="@if(@$ativosExternos->id == @$editAtivos->id_ativo_externo_estoque)  {{@$ativosExternos->ativo_externo->titulo}} @endif" readonly>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label" for="obra">Fornecedor </label>
                                <select class="form-select form-control select2" id="id_fornecedor" name="id_fornecedor">
                                    <option value="" selected>Selecione um fornecedor</option>

                                    @foreach ($fornecedores as $fornecedor)
                                    <option value="{{ $fornecedor->id }}" {{ @$editAtivos->id_fornecedor == $fornecedor->id ? 'selected' : '' }}>
                                        {{ $fornecedor->nome_fantasia }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label class="form-label">Data de retirada</label>
                                <input class="form-control" id="data_retirada" name="data_retirada" type="datetime-local" value="{{ old('data_retirada',@$editAtivos->data_retirada) }}">

                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Prazo para devolulção</label>
                                <input class="form-control" id="data_prevista" name="data_prevista" type="datetime-local" value="{{ old('data_prevista', @$editAtivos->data_prevista) }}">
                            </div>

                            <div class="col-md-4" style="display: @if(@$editAtivos->id_status == null)  none @elseif(@$editAtivos->id_status == 2) block @else none @endif;" >@if(@$editAtivos->data_realizada == null) block none @endif
                                <label class="form-label" for="tipo">Data da Devolução</label>
                                <input  class="form-control" id="data_realizada" name="data_realizada" type="datetime-local" value="{{ old('data_realizada', @$editAtivos->data_realizada) }}"  >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Custo da manutenção</label>
                                <input class="form-control" id="valor" name="valor" type="text" value="{{ old('valor', @$editAtivos->valor) }}">
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header  bg-primary text-center  shadow-sm">
                    <h3 class="card-title m-auto text-white">Descrição</h3>
                </div>

                <div class="card-body py-3 px-4">
                    <label class="form-label">Descreva o defeito</label>

                    @if(@$editAtivos)

                    <textarea name="description" id="description" cols="30" rows="10">{{ $editAtivos->description }}</textarea>

                    @else
                    <textarea name="description" id="description" cols="30" rows="10"><table class="table table-bordered"><tbody><tr><td style="text-align: center;"><b>RELATORIO DE MANUTENCAO</b></td></tr><tr><td style="text-align: center; ">DESCRICAO</td></tr><tr><td style="text-align: center;"><p><br></p><p><br></p><p><br></p></td></tr><tr><td style="text-align: center; ">FOTO</td></tr></tbody></table><p><br></p></textarea>
                    @endif
                </div>

                <div class="card-footer">
                    <button class="btn btn-primary btn-lg font-weight-medium" type="submit">Salvar</button>

                    <a href="{{ url('admin/ativo/externo/manutencao') }}">
                        <button class="btn btn-warning btn-lg font-weight-medium" type="button">Cancelar</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>


<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha256-Kg2zTcFO9LXOc7IwcBx1YeUBJmekycsnTsq2RuFHSZU=" crossorigin="anonymous"></script>


<script>
    $(document).ready(function($) {
        $('#valor').mask('000.000.000.000.000,00', {
            reverse: true,
        });

    });
</script>

<script>
    $('#description').summernote({
        placeholder: 'DESCREVA O DEFEITO E INSIRA IMAGENS',
        tabsize: 2,
        height: 420,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
</script>

<script>
    $('#patrimonio').change(function() {
        var selecao = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'GET',
            url: "{{route('admin.ativo.externo.manutencao.preencher.campos')}}",
            data: {
                selecao: selecao
            }

        }).done(function(data) {
            
            console.log(data[0]['id_ativo_externo']);
            // Preencha o segundo campo com os dados recebidos

            $('#nomeAtivo').val(data[0]['ativo_externo'].titulo);
            $('#id_ativo_externo_estoque').val(data[0]['id']);
            $('#id_ativo_externo').val(data[0]['id_ativo_externo']);

            /*var spanPatrimonio = document.getElementById('segundoCampo');
             spanPatrimonio.textContent = data[0]['ativo_externo'].titulo;*/


        });
    });
</script>

@endsection