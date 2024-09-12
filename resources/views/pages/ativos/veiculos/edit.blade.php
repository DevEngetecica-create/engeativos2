@extends('dashboard')
@section('title', 'Cadastro de Veículo')
@section('content')

   
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="page-header pt-3">
                    <h3 class="page-title">
                        <span class="page-title-icon">
                            <i class="mdi mdi-fire-truck  mdi-36px"></i>
                        </span> Cadastro de Veículo
                    </h3>
                </div>
                <hr class="my-2 mb-4">

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

                <form method="post" action="{{ route('veiculo.store') }}" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-8">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="obra">Obra</label>
                                    <select class="form-select form-control select2" id="obra_id" name="obra_id">

                                        <option value="3"> Obra 1</option>
                                        <option value="4"> Obra 2</option>
                                        <option value="5"> Obra 3</option>
                                        <option value="6"> Obra 4</option>

                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Categorias</label>

                                    <select class="form-select form-control select2" id="idCategoria" name="idCategoria">
                                        <option> Selecione uma categoria</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" {{$categoria->id == $veiculo->idCategoria ? 'selected' : ''}}> {{ $categoria->nomeCategoria }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="tipo">Sub Categorias <span id="mensagem"
                                            class="mensagem text-success text-center"
                                            style="font-size:small"></span></label>
                                    <select class="form-select form-control select2" id="idSubCategoria" name="idSubCategoria">
                                        @if($veiculo->idCategoria)
                                            @foreach ($subCategorias as $subCategoria)
                                                <option value="{{ $subCategoria->id }}" {{$subCategoria->id == $veiculo->idSubCategoria ? 'selected' : ''}}> {{ $subCategoria->nomeSubCategoria }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>

                                <div class="col-md-12 mt-4 mb-2">
                                    <label class="form-label">Escolha um Plano de Manuteção Preventivas</label>
                                    <select class="form-select form-select-sm select2" id="id_preventiva"
                                        name="id_preventiva">
                                        <option value=""> Selecione uma categoria</option>
                                        @foreach ($preventivas as $preventiva)
                                            <option value="{{ $preventiva->id }}"> {{ $preventiva->nome_preventiva }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="tipos">Tipo</label>
                                    <select class="form-select form-select-sm" id="tipos" name="tipo" required>
                                        <option value="">Selecione</option>

                                        @foreach ($tipos_veiculos as $tipo_veiculo)
                                            <option value="{{ $tipo_veiculo->id }}" {{ ($tipo_veiculo->id == $veiculo->tipo ? 'selected' : '')}}>{{ $tipo_veiculo->nome_tipo_veiculo }} </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-4" id="marcaVeiculos">
                                    <label class="form-label" for="marcas">Marca</label>

                                    <span class="mensagem text-success text-center" id="mensagem_tipo"
                                        style="font-size:small"></span>

                                    <select class="form-select form-select-sm" id="marcas" name="marca" required>
                                        <option value="">Selecione o tipo de veículo</option>

                                        @if($veiculo->marca)
                                            
                                                <option value="{{ $veiculo->marca }}" selected> {{ $veiculo->marca }}</option>
                                           
                                        @endif

                                    </select>

                                    <input id="marcas_nome" name="marca_nome" type="hidden">

                                </div>

                                <div class="col-md-4" id="modeloVeiculos">
                                    <label class="form-label" for="modelo">Modelo</label>
                                    <span class="mensagem text-success text-center" id="mensagem_modelo"
                                        style="font-size:small"></span>

                                    <select class="form-select form-select-sm" id="modelos" name="modelo" required>

                                        <option value="">Selecione os campos anteriores</option>

                                        @if($veiculo->modelos)
                                            
                                                <option value="{{ $veiculo->modelos }}" selected> {{ $veiculo->modelos }}</option>
                                           
                                        @endif

                                    </select>

                                    <input id="modelos_nome" name="modelo_nome" type="hidden">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="anos">Ano</label>
                                    <span class="mensagem text-success text-center" id="mensagem_ano"></span>
                                    <select class="form-select form-select-sm" id="anos" name="ano">
                                        <option value="">Selecione os campos anteriores</option>                                        
                                        @if($veiculo->ano)                                            
                                                <option value="{{ $veiculo->ano }}" selected> {{ $veiculo->ano }}</option>                                           
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="veiculos">Veículo</label>
                                    <input class="form-control form-control-sm" id="veiculos" name="veiculo" type="veiculo" value="{{($veiculo->ano ? $veiculo->ano : '')}}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="placa">Placa</label>
                                    <input class="form-control form-control-sm text-uppercase" id="placa" name="placa" type="text" value="{{($veiculo->placa ? $veiculo->placa : '')}}">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-2">
                                    <label class="form-label" for="valor_fipe">FIPE</label>
                                    <div class="d-flex">
                                        <span class="pr-2"
                                            style="margin-top: 10px; font-size:14px; margin-right: 8px">R$
                                        </span>
                                        <input class="form-control form-control-sm" id="valor_fipe" name="valor_fipe" type="text" value="{{($veiculo->valor_fipe ? $veiculo->valor_fipe : '')}}"required>
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    <label class="form-label" for="valor_fipe">Aquisição</label>
                                    <div class="d-flex">
                                        <span class="pr-2"
                                            style="margin-top: 10px; font-size:14px; margin-right: 8px">R$
                                        </span>
                                        <input class="form-control form-control-sm" id="valor_aquisicao" name="valor_aquisicao" type="text" value="{{($veiculo->valor_aquisicao ? $veiculo->valor_aquisicao : '')}}" required>
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label" for="codigo_fipe">Código</label>
                                    <input class="form-control form-control-sm" id="codigo_fipe" name="codigo_fipe" type="text" value="{{($veiculo->codigo_fipe ? $veiculo->codigo_fipe : '')}}">

                                </div>

                                <div class="col-md-2">
                                    <label class="form-label" for="fipe_mes_referencia">Mês/ ano de referência</label>
                                    <input class="form-control form-control-sm" id="fipe_mes_referencia" name="fipe_mes_referencia" type="text" value="{{($veiculo->fipe_mes_referencia ? $veiculo->fipe_mes_referencia : '')}}">

                                </div>

                                <div class="col-md-2">
                                    <label class="form-label" for="mes_aquisicao">Mês/ ano de Aquisição</label>
                                    <input class="form-control form-control-sm" id="mes_aquisicao" name="mes_aquisicao"
                                        type="text" value="{{($veiculo->mes_aquisicao ? $veiculo->mes_aquisicao : '')}}" >
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-8">
                                    <label class="form-label" for="observacao">Observação</label>
                                    <textarea class="form-control" id="observacao" name="observacao" cols="30" rows="4">{{($veiculo->observacao ? $veiculo->observacao : '')}}</textarea>
                                </div>

                                <div class="col-4">
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <label class="form-label" for="quilometragem_inicial">Quilometragem
                                                Inicial</label>
                                            <input class="form-control form-control-sm" id="quilometragem_inicial" name="quilometragem_inicial" type="number" value="{{($veiculo->quilometragem_inicial ? $veiculo->quilometragem_inicial : '')}}">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label" for="situacao">Situação</label>
                                            <select class="form-select" id="situacao" name="situacao">
                                                <option value="Ativo">Ativo</option>
                                                <option value="Inativo">Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-2">

                        </div>
                        <div class="col-xxl-4 col-lg-6">

                            <div class="col-12">
                                <div class="card">

                                    @if($veiculo->imagem)
                                        <img src="{{ url('imagens/veiculos')}}/{{$veiculo->id}}/{{$veiculo->imagem}}" id="target" class="card-img-top img-fluid" id="target">
                                    @else
                                        <img src="{{ url('storage/imagem_ativo/nao-ha-fotos.png') }}" id="target" class="card-img-top img-fluid" id="target">
                                    @endif

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row mt-3">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label" for="placa">Imagem Principal</label>
                                        <input class="form-control form-control-sm" id="imagem" name="imagem"
                                            onChange="carregarImg()" type="file">
                                    </div>
                                    <div class="col-md-12">

                                        <button class="btn btn-primary btn-md font-weight-medium"
                                            type="submit">Salvar</button>

                                        <a href="{{ url('admin/ativo/veiculo') }}">
                                            <button class="btn btn-warning btn-md font-weight-medium"
                                                type="button">Cancelar</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha256-Kg2zTcFO9LXOc7IwcBx1YeUBJmekycsnTsq2RuFHSZU=" crossorigin="anonymous"></script>

        <!--SCRIPT PARA CARREGAR IMAGEM PRINCIPAL -->

        <script type="text/javascript">
            function carregarImg() {
                var target = document.getElementById('target');
                var file = document.querySelector("input[type=file]").files[0];
                var reader = new FileReader();
                reader.onloadend = function() {
                    target.src = reader.result;
                };
                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    target.src = "";
                }
            }
        </script>

        <script>
            $(document).ready(function($) {
                $('#idCategoria').change(function() {
                    $('#mensagem').html(
                        `<small> - Aguarde, carregando ...
                    <div class="spinner-grow text-warning" role="status" style="width:1.5rem !important; height:1.5rem !important">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </small> 
                `);
                    var selecao = $(this).val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: 'GET',
                        url: "{{ route('pesquisarSubcategorias') }}",
                        data: {
                            selecao: selecao
                        }
                    }).done(function(data) {

                        // Preencher o segundo campo com os dados recebidos da subcategoria
                        if (data.length > 0) {

                            var option = "";

                            var option = '<option> Selecione uma Subcategoria</option>';

                            $.each(data, function(i, obj) {
                                option += '<option value="' + obj.id + '">' + obj
                                    .nomeSubCategoria + '</option>';
                            });
                            $('#mensagem').html(
                                ' - Resultado: <span>' + data.length + ' </span> subcategoria(s) ');
                        } else {

                            $('#mensagem').html(' - Não foram encontradas nenhuma subcategoria!');
                        }
                        $('#idSubCategoria').html(option).show();
                    });
                });

                $('#valor_fipe').mask('000.000.000.000.000,00', {
                    reverse: true
                });

                $('#valor_aquisicao').mask('000.000.000.000.000,00', {
                    reverse: true
                });
            });
        </script>

        <script>
            //$(document).ready(function($) {
                $('#tipos').on('change', function() {
                    $('#mensagem_tipo').html(
                        `<small> - Aguarde, carregando ...
                    <div class="spinner-grow text-warning" role="status" style="width:1.5rem !important; height:1.5rem !important">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </small> 
                `);
                    //alert("ops");

                    var codigoTipoVeiculo = $(this).val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ route('consultar-marcas') }}",
                        method: 'get',
                        data: {
                            codigoTabelaReferencia: 311,
                            codigoTipoVeiculo: codigoTipoVeiculo
                        },
                        success: function(response) {

                            if (response.length > 0) {

                                $('#marcas').empty().append(
                                    '<option value="" selected>Selecione a marca</option>');
                                $.each(response, function(key, value) {
                                    $('#marcas').append('<option value="' + value.Value +
                                        '">' + value.Label + '</option>');
                                });

                                $('#mensagem_tipo').html(
                                    ' - Resultado: <span class="">' + response.length +
                                    ' </span> registros ');

                            } else {

                                $('#mensagem_tipo').html(' - Não foram encontrdo registros!');

                            }
                        },
                        error: function(xhr) {
                            $('#resultado').text('Erro ao consultar marcas');
                        }
                    });

                })


                //***************************************************************** */
                $('#marcas').on('change', function() {
                    var selectedOption = $(this).find('option:selected');

                    $('#marcas_nome').val(selectedOption.text());

                });

                $('#marcas').on('change', function() {

                    $('#mensagem_modelo').html(
                        `<small> - Aguarde, carregando ...
                    <div class="spinner-grow text-warning" role="status" style="width:1.5rem !important; height:1.5rem !important">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </small> 
                `);

                    var codigoTipoVeiculo = $('#tipos').val();
                    var codigoMarca = $(this).val();

                    // $('modelo_nome').val("");

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({

                        url: "{{ route('consultar-modelos') }}",
                        method: 'get',

                        data: {
                            codigoTabelaReferencia: 311,
                            codigoTipoVeiculo: codigoTipoVeiculo,
                            codigoMarca: codigoMarca
                        },
                        success: function(response) {

                            if (response.Modelos.length > 0) {

                                $('#modelos').empty().append(

                                    '<option value="" selected>Selecione a marca</option>');

                                $.each(response.Modelos, function(key, value) {

                                    $('#modelos').append('<option value="' + value.Value +
                                        '">' + value.Label + '</option>');
                                });

                                $('#mensagem_modelo').html(' - Resultado: <span class="">' +
                                    response.Modelos.length + ' </span> registros ');
                            } else {

                                $('#mensagem_modelo').html(' - Não foram encontrdo registros!');

                            }
                        },
                        error: function(xhr) {

                            $('#resultado').text('Erro ao consultar ');
                        }
                    });

                });

                $('#modelos').on('change', function() {

                    var selectedOptionModelos = $(this).find('option:selected');
                    var codigoTipoVeiculo = $('#tipos').val();
                    var codigoMarca = $('#marcas').val();
                    var codigoModelo = $(this).val();

                    $('#mensagem_ano').html(
                        `<small> - Aguarde, carregando ...
                    <div class="spinner-grow text-warning" role="status" style="width:1.5rem !important; height:1.5rem !important">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </small> 
                `);

                    $('#modelos_nome').val(selectedOptionModelos.text());

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({

                        url: "{{ route('consultar-ano_modelos') }}",
                        method: 'get',

                        data: {
                            codigoTabelaReferencia: 311,
                            codigoTipoVeiculo: codigoTipoVeiculo,
                            codigoMarca: codigoMarca,
                            codigoModelo: codigoModelo
                        },
                        success: function(response) {

                            if (response.length > 0) {
                                $('#anos').empty().append(
                                    '<option value="" selected>Selecione o ano </option>');

                                $.each(response, function(key, value) {
                                    $('#anos').append('<option value="' + value.Value +
                                        '">' + value.Label + '</option>');
                                });

                                $('#mensagem_ano').html(
                                    ' - Resultado: <span class="">' + response.length +
                                    ' </span> registros ');
                            } else {

                                $('#mensagem_ano').html(' - Não foram encontrdo registros!');

                            }
                        },
                        error: function(xhr) {

                            $('#resultado').text('Erro ao consultar ');
                        }
                    });
                });

                $('#anos').on('change', function() {
                    var codigoTipoVeiculo = $('#tipos').val();
                    var codigoMarca = $('#marcas').val();
                    var codigoModelo = $('#modelos').val();
                    var ano = $(this).val();
                    var tipoConsulta = "tradicional"

                    if (codigoTipoVeiculo == 1) {

                        var tipo = 'carro';

                    } else if (codigoTipoVeiculo == 2) {

                        var tipo = 'moto';

                    } else if (codigoTipoVeiculo == 3) {

                        var tipo = 'caminhao';
                    }

                    //Extrais o último digito da variavel ano, porque, o último dígito da variavel ano identifica o tipo de combustível
                    var codigoTipoCombustivel = ano.slice(-1);

                    //Extra o ano do modelo
                    var anoModelo = ano.slice(0, 4)

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({

                        url: "{{ route('consultar-todos_parametros') }}",
                        method: 'get',

                        data: {
                            codigoTabelaReferencia: 311,
                            codigoTipoVeiculo: codigoTipoVeiculo,
                            codigoMarca: codigoMarca,
                            codigoModelo: codigoModelo,
                            ano: ano,
                            codigoTipoCombustivel: codigoTipoCombustivel,
                            anoModelo: anoModelo,
                            tipoConsulta: tipoConsulta,
                            tipo: tipo
                        },
                        success: function(response) {

                            console.log(response.Modelo)

                            $('#veiculos').val(response.Modelo);

                            // console.log(response.Valor)

                            $('#valor_fipe').val(response.Valor.replace("R$ ", ''));
                            //$('#valor_fipe').val(data.Valor);

                            $('#codigo_fipe').val(response.CodigoFipe);

                            $('#fipe_mes_referencia').val(response.MesReferencia);

                        },
                        error: function(xhr) {

                            $('#resultado').text('Erro ao consultar ');
                        }
                    });

                });
           // });
        </script>

    @endsection
