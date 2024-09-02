@extends('dashboard')
@section('title', 'Cadastro de Veículo')
@section('content')

    <div class="page-header pt-3">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Cadastro de Veículo
        </h3>
    </div>
    <div class="container">
        <div class="row">
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

                        <div id="resultado"></div>
                        <form method="post" action="{{ route('veiculo.update', $veiculo->id) }}" enctype="multipart/form-data">
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
                                        <option > Selecione uma categoria</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" {{$categoria->id == $veiculo->idCategoria ? 'selected' : ''}}> {{ $categoria->nomeCategoria }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="col-md-4">
                                    
                                    <label class="form-label" for="tipo">Sub Categorias <span id="mensagem" class="mensagem text-success text-center" style="font-size:small"></span></label>
                                    <select class="form-select form-control select2" id="idSubCategoria" name="idSubCategoria">

                                    </select>

                                </div>

                                <div class="col-md-12 mt-4 mb-2">
                                    <label class="form-label">Escolha um Plano de Manuteção Preventivas</label>  
                                    <select class="form-select form-control select2" id="id_preventiva" name="id_preventiva">
                                        <option value=""> Selecione uma categoria</option>
                                        @foreach ($preventivas as $preventiva)
                                            <option value="{{ $preventiva->id }}" {{$preventiva->id == $veiculo->id_preventiva ? 'selected' : ''}}> {{ $preventiva->nome_preventiva }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">uu
                                <div class="col-md-4">
                                    <label class="form-label" for="tipo"></label>
                                    <select class="form-select" id="tipo" name="tipo" required> 
                                        <option value="">Selecione</option>

                                        @foreach($tipos_veiculos as $tipo_veiculo)

                                        <option value="{{$tipo_veiculo->id}}" {{$tipo_veiculo->id == $veiculo->tipo ? 'selected' : ''}}>{{$tipo_veiculo->nome_tipo_veiculo}}</option>

                                        @endforeach
                                        
                                    </select>
                                </div>

                                <div class="col-md-4" id="marcaVeiculos">
                                    <label class="form-label" for="marca">Marca</label>

                                    <span class="mensagem text-success text-center" id="mensagem_tipo" style="font-size:small"></span>

                                    <select class="form-select" id="marca_nome" name="marca_nome" required>
                                        <option value="">Selecione o tipo de veículo</option>

                                        @if($veiculo->marca != "")
                                            <option value="{{$veiculo->marca}}" selected>{{$veiculo->marca}}</option>
                                        @else
                                        @endif

                                    </select>

                                    <input id="marca_nomea" name="marca_nome" type="hidden">

                                </div>

                                <div class="col-md-4" id="modeloVeiculos">
                                    <label class="form-label" for="modelo">Modelo</label>
                                    <span class="mensagem text-success text-center" id="mensagem_modelo"
                                        style="font-size:small"></span>

                                    <select class="form-select" id="modelo" name="modelo" required>

                                        <option value="">Selecione os campos anteriores</option>
                                        @if($veiculo->modelo != "")
                                            <option value="{{$veiculo->modelo}}" selected>{{$veiculo->modelo}}</option>
                                        @else
                                        @endif

                                    </select>

                                    <input id="modelo_nome" name="modelo_nome" type="hidden">

                                </div>


                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="ano">Ano</label>

                                        <span class="mensagem text-success text-center" id="mensagem_ano"></span>

                                        <select class="form-select" id="ano" name="ano">

                                            <option value="">Selecione os campos anteriores</option>
                                            @if($veiculo->ano != "")
                                                <option value="{{$veiculo->ano}}" selected>{{$veiculo->ano}}</option>
                                            @else
                                            @endif

                                        </select>
                                    </div>




                                    <div class="col-md-4">

                                        <label class="form-label" for="veiculo">Veículo</label>

                                        <input class="form-control" id="veiculo" name="veiculo" type="veiculo"  value="{{ $veiculo->veiculo ?? old('veiculo')}}" placeholder="Preenchimento Automático">

                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label" for="placa">Placa</label>

                                        <input class="form-control text-uppercase" id="placa" name="placa" type="text" value="{{ $veiculo->placa ?? old('placa') }}">

                                    </div>

                                </div>



                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="valor_fipe">Valor</label>
                                        <div class="d-flex">
                                            <span class="pr-2"
                                                style="margin-top: 10px; font-size:18px; margin-right: 8px">R$
                                            </span>
                                            <input class="form-control" id="valor_fipe" name="valor_fipe" type="text" value="{{$veiculo->valor_fipe ?? old('valor_fipe')}}">
                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label" for="codigo_fipe">Código</label>

                                        <input class="form-control" id="codigo_fipe" name="codigo_fipe" type="text"
                                            value="{{ $veiculo->codigo_fipe ?? old('codigo_fipe') }}">

                                    </div>

                                    <div class="col-md-2">

                                        <label class="form-label" for="fipe_mes_referencia">Mês/ ano de referência</label>

                                        <input class="form-control" id="fipe_mes_referencia" name="fipe_mes_referencia" type="text" value="{{ $veiculo->fipe_mes_referencia ?? old('fipe_mes_referencia') }}">

                                    </div>

                                    <div class="col-md-2">

                                        <label class="form-label" for="mes_aquisicao">Mês/ ano de Aquisição</label>

                                        <input class="form-control" id="mes_aquisicao" name="mes_aquisicao" type="text" value="{{ $veiculo->mes_aquisicao ?? old('mes_aquisicao') }}">

                                    </div>

                                </div>



                                <div class="row mt-3" id="divHorimetro" style="display:none;">
                                    <div class="col-md-2">
                                        <label class="form-label" for="horimetro_inicial">Horímetro inicial</label>
                                        <input class="form-control" id="horimetro_inicial" name="horimetro_inicial"
                                            type="text" value="{{ old('horimetro_inicial') }}" step="60">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label" for="codigo_da_maquina">Código da Máquina</label>
                                        <input class="form-control" id="codigo_da_maquina" name="codigo_da_maquina"
                                            type="text" value="{{ old('codigo_da_maquina') }}">
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-8">
                                        <label class="form-label" for="observacao">Observação</label>
                                        <textarea class="form-control" id="observacao" name="observacao" cols="30" rows="4">{{ $veiculo->observacao ?? old('observacao') }}</textarea>
                                    </div>

                                    <div class="col-4">

                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <label class="form-label" for="quilometragem_inicial">Quilometragem Inicial</label>
                                                <input class="form-control" id="quilometragem_inicial" name="quilometragem_inicial" type="number" value="{{ $veiculo->quilometragem_inicial ?? old('quilometragem_inicial') }}">
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


                                <hr class="my-3">

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="placa">Imagem Principal</label>
                                        <input class="form-control" id="imagem" name="imagem" type="file">
                                    </div>                                 
                                    <div class="col-md-6">
                                        
                                        <button class="btn btn-primary btn-sm font-weight-medium" type="submit">Salvar</button>

                                        <a href="{{ url('admin/ativo/veiculo') }}">
                                            <button class="btn btn-warning btn-sm font-weight-medium" type="button">Cancelar</button>
                                        </a>
                                    </div>                                 
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
        integrity="sha256-Kg2zTcFO9LXOc7IwcBx1YeUBJmekycsnTsq2RuFHSZU=" crossorigin="anonymous"></script>



    <script>
        $(document).ready(function($) {
            $('#valor_fipe').mask('000.000.000.000.000,00', {
                reverse: true
            });
        });

        $('#tipo').on('change', function() {
            $('#mensagem_tipo').html(
                `<small> - Aguarde, carregando ...
                    <div class="spinner-grow text-warning" role="status" style="width:1.5rem !important; height:1.5rem !important">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </small> 
                `);

            var codigoTipoVeiculo = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/consultar-marcas',
                method: 'get',
                data: {
                    codigoTabelaReferencia: 311,
                    codigoTipoVeiculo: codigoTipoVeiculo
                },
                success: function(response) {

                    if (response.length > 0) {

                        $('#marca').empty().append(
                            '<option value="" selected>Selecione a marca</option>');
                        $.each(response, function(key, value) {
                            $('#marca').append('<option value="' + value.Value +
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
            //});
        })
    </script>

    <script>
        $(document).ready(function() {

            $('#marca').on('change', function() {
                var selectedOption = $(this).find('option:selected');

                $('#marca_nome').val(selectedOption.text());

            });

            $('#idCategoria').change(function() {
                $('#mensagem').html( 
                `<small> - Aguarde, carregando ...
                    <div class="spinner-grow text-warning" role="status" style="width:1.5rem !important; height:1.5rem !important">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </small> 
                `);
                var selecao = $(this).val();

                //console.log(selecao)

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

                   // console.log(data)
                    // Preencha o segundo campo com os dados recebidos
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

            //***************************************************************** */

            $('#marca').on('change', function() {

                $('#mensagem_modelo').html(
                    `<small> - Aguarde, carregando ...
                    <div class="spinner-grow text-warning" role="status" style="width:1.5rem !important; height:1.5rem !important">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </small> 
                `);

                var codigoTipoVeiculo = $('#tipo').val();
                var codigoMarca = $(this).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({

                    url: '/consultar-modelos',
                    method: 'get',

                    data: {
                        codigoTabelaReferencia: 311,
                        codigoTipoVeiculo: codigoTipoVeiculo,
                        codigoMarca: codigoMarca
                    },
                    success: function(response) {

                        if (response.Modelos.length > 0) {

                            $('#modelo').empty().append(

                                '<option value="" selected>Selecione a marca</option>');

                            $.each(response.Modelos, function(key, value) {

                                $('#modelo').append('<option value="' + value.Value +
                                    '">' + value.Label + '</option>');
                            });

                            $('#mensagem_modelo').html(' - Resultado: <span class="">' + response.Modelos.length +' </span> registros ');
                        } else {

                            $('#mensagem_modelo').html(' - Não foram encontrdo registros!');

                        }
                    },
                    error: function(xhr) {

                        $('#resultado').text('Erro ao consultar ');
                    }
                });

            });

            $('#modelo').on('change', function() {

                $('#mensagem_ano').html(
                    `<small> - Aguarde, carregando ...
                    <div class="spinner-grow text-warning" role="status" style="width:1.5rem !important; height:1.5rem !important">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </small> 
                `);

                var codigoTipoVeiculo = $('#tipo').val();
                var codigoMarca = $('#marca').val();
                var codigoModelo = $(this).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({

                    url: '/consultar-ano_modelos',
                    method: 'get',

                    data: {
                        codigoTabelaReferencia: 311,
                        codigoTipoVeiculo: codigoTipoVeiculo,
                        codigoMarca: codigoMarca,
                        codigoModelo: codigoModelo
                    },
                    success: function(response) {



                        if (response.length > 0) {

                            $('#ano').empty().append(

                                '<option value="" selected>Selecione o ano </option>');

                            $.each(response, function(key, value) {

                                $('#ano').append('<option value="' + value.Value +
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

            $('#ano').on('change', function() {

                var codigoTipoVeiculo = $('#tipo').val();
                var codigoMarca = $('#marca').val();
                var codigoModelo = $('#modelo').val();
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

                    url: '/consultar-todos_parametros',
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

                        $('#veiculo').val(response.Modelo);

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

        });
    </script>

@endsection
