@extends('dashboard')
@section('title', 'Locações de Veículos')
@section('content')

<div class="page-header mt-2 mb-5">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Locação de Veículos e Máquinas
    </h3>
    
</div>

<div class="row">
    <div class="grid-margin stretch-card">
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

                @php
                $action = isset($editLocacaoVeiculos) ? route('ativo.veiculo.locacaoVeiculos.update', @$editLocacaoVeiculos->id) : route('ativo.veiculo.locacaoVeiculos.store');
                @endphp
                
                 <div class="card-header mb-3 pb-2"><h5 >{{@$editLocacaoVeiculos ? 'Edição' : 'Cadastros'}}</h5></div>
                 
                <form method="post" enctype="multipart/form-data" action="{{ $action }}">
                    @csrf

                    <div class="row">

                        <div class="row mb-3">
                            <div class="col-sm-12 col-xl-6">
                                <label class="form-label" for="obra">Tipo de Veículo</label>

                                <span id="mensagem">
                                   
                                </span>

                                <select class="form-select form-control" id="tipo_veiculo" name="tipo_veiculo" >

                                   
                                    @if(@$editLocacaoVeiculos->veiculo_id)
                                    
                                        <option  class="text-uppercase" value="{{@$editLocacaoVeiculos->veiculo->tipo}}" selected >{{@$editLocacaoVeiculos->veiculo->tipo}}</option>
                                        
                                        <option  class="text-uppercase" value="caminhoes">Caminhoes</option>
                                        <option  class="text-uppercase" value="maquinas">Maquinas</option>
                                        <option  class="text-uppercase" value="veiculos">Veiculos</option>
                                     
                                    @else
                                        
                                        <option value="" selected>Seleceione um tipo de veículo</option>
                                        <option  class="text-uppercase" value="caminhoes">Caminhoes</option>
                                        <option  class="text-uppercase" value="maquinas">Maquinas</option>
                                        <option  class="text-uppercase" value="veiculos">Veiculos</option>
                                        
                                    @endif

                                </select>
                            </div>

                            <div class="col-sm-12 col-xl-6">
                                <label class="form-label" for="obra">Placa/ Modelo </label>

                                <select class="form-select form-control select2" id="veiculo_id" name="veiculo_id" required>
                                   
                                     @if(@$editLocacaoVeiculos->veiculo_id)
                                     
                                        <option value="{{@$editLocacaoVeiculos->veiculo_id}}" selected> @if(@$editLocacaoVeiculos->veiculo->codigo_da_maquina) {{@$editLocacaoVeiculos->veiculo->codigo_da_maquina}} @else {{@$editLocacaoVeiculos->veiculo->placa}} @endif</option>
                                    @else
                                         <option value="" selected>Selecione um tipo de veículo</option>
                                    @endif
                                   
                                </select>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12 col-xl-6">
                                <label class="form-label" for="obra">Obra de Origem</label>
                                <select class="form-select form-control select2" id="id_obra" name="id_obra" required>

                                    @foreach ($obras as $obra)
                                    <option value="{{ $obra->id }}" {{ @$editLocacaoVeiculos->obra->id == $obra->id ? 'selected' : '' }}>
                                        {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                    </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-sm-12 col-xl-6">
                                <label class="form-label" for="obra">Obra de Destino</label>
                                <select class="form-select form-control select2" id="id_obraDestino" name="id_obraDestino" required>
                                    <option value="" selected>Selecione uma obra</option>
                                    @foreach ($obras as $obra)
                                    <option value="{{ $obra->id }}" {{ @$editLocacaoVeiculos->obraDestino->id == $obra->id ? 'selected' : '' }}>
                                        {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Funcionário</label>
                                <select class="form-select form-control select2" id="id_funcionario" name="id_funcionario" required>
                                    <option value="" selected>Selecione um Funcionário</option>

                                    @foreach($funcionarios as $funcionario)
                                    <option value="{{ $funcionario->id }}" {{ @$editLocacaoVeiculos->funcionarios->id == $funcionario->id ? 'selected' : '' }}>
                                        {{ $funcionario->nome }}
                                    </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>


                        <div class="row mb-3">

                            <div class="col-md-2">
                                <label class="form-label">Data de Iníncio</label>
                                <input class="form-control" id="data_inicio" name="data_inicio" type="date" value="{{ old('data_inicio', @$editLocacaoVeiculos->data_inicio) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Data Prevista de término alocação</label>
                                <input class="form-control" id="data_prevista" name="data_prevista" type="date" value="{{ old('data_prevista', @$editLocacaoVeiculos->data_prevista) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="tipo">Data término alocação</label>
                                <input class="form-control" id="data_fim" name="data_fim" type="date" value="{{ old('data_fim', @$editLocacaoVeiculos->data_fim) }}">
                            </div>

                        </div>

                    </div>

                    <div class="col-12 mt-5 m-3">
                        
                       
                       <input id="tipo_veiculo_selecionado" name="tipo_veiculo_selecionado" type="hidden" >
                       
                        <button class="btn btn-primary btn-lg font-weight-medium" type="submit">Salvar</button>
                        <a href="{{ url('admin/ativo/veiculo/locacaoVeiculos') }}">
                            <button class="btn btn-warning btn-lg font-weight-medium" type="button">Cancelar</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<script>
//Pesquisar as placas ou os numeros de series  de acordo com os tipo dos veiculos

$(document).ready(function() {
    
    var url_consultav = '/ativo/veiculo/locacaoVeiculos/pesquisar_placa_modelo'
    $('#id_obraDestino').select2();
    $('#placa_modelo').select2();
    
$('#tipo_veiculo').change(function() {

        $('#mensagem').html('<span class="mensagem">Aguarde, carregando' +
            ' <div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">' +
            '<span class="visually-hidden">Loading...</span> ' +
            '</div></span>');

        var selecao_tipo = $(this).val();
        
        $('#tipo_veiculo_selecionado').val(selecao_tipo)
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'GET',
            url: "{{route('ativo.veiculo.locacaoVeiculos.pesquisar_placa_modelo')}}",
            data: {
                selecao_tipo: selecao_tipo
            }
        }).done(function(data) {

            //console.log(data)
            // Preencha o segundo campo com os dados recebidos
            if (data.length > 0) {

              //  console.log(data);

                var option = "";
                
                if (selecao_tipo == "maquinas") {

                var option = '<option> Selecione o código da máquinaa</option>';
                
                }else{
                    
                    var option = '<option> Selecione a placa do veículo</option>';
                    
                    
                }

                $.each(data, function(i, obj) {

                    if (obj.tipo == "maquinas") {

                        option += '<option value="' + obj.id + '">' + obj.codigo_da_maquina + '</option>';

                    } else {

                        option += '<option value="' + obj.id + '">' + obj.placa + '</option>';

                    }

                });
                $('#mensagem').html(' - Resultado: <span class="fs-6 badge text-bg-secondary">' + data.length + ' </span> registros ');
            } else {

                $('#mensagem').html(' - Não foram encontrdo registros!');
            }
            $('#placa_modelo').html(option).show();
        });
    });
    
   /*$('#placa_modelo').change(function() {
       
       url_consultav = "";
       
       url_consultav = '/ativo/veiculo/locacaoVeiculos/pesquisar_placa_modelo'
        
    var placa_modelo = $(this).val();
    var placa = "placa"; 
    var tipo_veiculo = $('#tipo_veiculo_selecionado').val();
    
    console.log(placa_modelo)

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'GET',
        url: '/ativo/veiculo/locacaoVeiculos/pesquisar_placa_modelo', // URL direta definida
        data: {
            placa_modelo: placa_modelo,
            placa: placa,
            tipo_veiculo: tipo_veiculo
        }
    }).done(function(data) {
        console.log(data.id)
        $('#veiculo_id').val(data.id);
    });
});*/
})


</script>
@endsection