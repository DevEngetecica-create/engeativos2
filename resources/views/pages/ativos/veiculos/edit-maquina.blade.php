@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Editar Máquina
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Cadastros <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

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
                <!--CM-005 - (SEG-6G42)-->
                <form method="post" action="{{ route('ativo.veiculo.update', $veiculo->id) }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label" for="obra">Obra</label>
                            <a href="{{ route('cadastro.obra.adicionar') }}">
                                <button class="btn btn-primary btn-sm"><i class="mdi mdi-plus"></i></button>
                            </a>

                            <select class="form-select form-control select2" id="obra" name="obra">
                                <option value="">Selecione uma Obra</option>

                                @foreach ($obras as $obra)
                                <option value="{{ $obra->id }}" {{ $veiculo->obra_id == $obra->id ? 'selected' : '' }}>
                                    {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Categorias</label>
                            <span id="mensagem">
                                <!--  <div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div> -->
                            </span>
                            <select class="form-select form-control select2" id="idCategoria" name="idCategoria">
                                @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ $categoria->id == $veiculo->idCategoria ? 'selected' : '' }}>{{ $categoria->nomeCategoria }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="tipo">Sub Categorias</label>
                            <select class="form-select form-control select2" id="idSubCategoria" name="idSubCategoria">

                                @foreach ($subCategorias as $subCategoria)

                                <option value="{{ $subCategoria->id }}" {{ $subCategoria->id == $veiculo->idSubCategoria ? 'selected' : '' }}>{{$subCategoria->nomeSubCategoria}}</option>

                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label" for="periodo_inicial">Período do Veículo Alocado - Inicial</label>
                            <input class="form-control" id="periodo_inicial" name="periodo_inicial" type="date" value="{{ old('periodo_inicial') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="periodo_final">Período do Veículo Alocado - Final</label>
                            <input class="form-control" id="periodo_final" name="periodo_final" type="date" value="{{ old('periodo_final') }}">
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="form-label" for="tipo">Tipo</label>
                            <select class="form-select" id="tipo" name="tipo">
                                <option value="maquinas">Máquinas</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="marcaMaquinas">
                            <label class="form-label" for="marca">Marca</label> <button class="badge badge-primary" data-toggle="modal" data-target="#addMarcaModal" type="button"><i class="mdi mdi-plus"></i></button>
                            <select class="form-select" id="marca_da_maquina" name="marca_da_maquina" required>
                                @foreach ($marcas as $marca)
                                <option value="{{ $marca->marca }}" {{ $marca->marca == $veiculo->marca ? 'selected' : '' }}>{{ $marca->marca }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" id="modeloMaquinas">
                            <label class="form-label" for="modelo_da_maquina">Descrição</label>
                            <select class="form-select" id="modelo_da_maquina" name="modelo_da_maquina" required>
                                @foreach ($modelos as $modelo)
                                <option value="{{ $modelo->modelo }}" {{ $modelo->modelo == $veiculo->modelo ? 'selected' : '' }}>{{ $modelo->modelo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4" id="anoMaquinas">
                            <label class="form-label" for="ano_da_maquina">Ano</label>
                            <select class="form-select" id="ano_da_maquina" name="ano_da_maquina">
                                <option value="">Selecione</option>
                                <option value="1987" {{ $veiculo->ano == '1987' ? 'selected' : '' }}>1987</option>
                                <option value="1988" {{ $veiculo->ano == '1988' ? 'selected' : '' }}>1988</option>
                                <option value="1989" {{ $veiculo->ano == '1989' ? 'selected' : '' }}>1989</option>
                                <option value="1990" {{ $veiculo->ano == '1990' ? 'selected' : '' }}>1990</option>
                                <option value="1991" {{ $veiculo->ano == '1991' ? 'selected' : '' }}>1991</option>
                                <option value="1992" {{ $veiculo->ano == '1992' ? 'selected' : '' }}>1992</option>
                                <option value="1993" {{ $veiculo->ano == '1993' ? 'selected' : '' }}>1993</option>
                                <option value="1994" {{ $veiculo->ano == '1994' ? 'selected' : '' }}>1994</option>
                                <option value="1995" {{ $veiculo->ano == '1995' ? 'selected' : '' }}>1995</option>
                                <option value="1996" {{ $veiculo->ano == '1996' ? 'selected' : '' }}>1996</option>
                                <option value="1997" {{ $veiculo->ano == '1997' ? 'selected' : '' }}>1997</option>
                                <option value="1998" {{ $veiculo->ano == '1998' ? 'selected' : '' }}>1998</option>
                                <option value="1999" {{ $veiculo->ano == '1999' ? 'selected' : '' }}>1999</option>
                                <option value="2000" {{ $veiculo->ano == '2000' ? 'selected' : '' }}>2000</option>
                                <option value="2001" {{ $veiculo->ano == '2001' ? 'selected' : '' }}>2001</option>
                                <option value="2002" {{ $veiculo->ano == '2002' ? 'selected' : '' }}>2002</option>
                                <option value="2003" {{ $veiculo->ano == '2003' ? 'selected' : '' }}>2003</option>
                                <option value="2004" {{ $veiculo->ano == '2004' ? 'selected' : '' }}>2004</option>
                                <option value="2005" {{ $veiculo->ano == '2005' ? 'selected' : '' }}>2005</option>
                                <option value="2006" {{ $veiculo->ano == '2006' ? 'selected' : '' }}>2006</option>
                                <option value="2007" {{ $veiculo->ano == '2007' ? 'selected' : '' }}>2007</option>
                                <option value="2008" {{ $veiculo->ano == '2008' ? 'selected' : '' }}>2008</option>
                                <option value="2009" {{ $veiculo->ano == '2009' ? 'selected' : '' }}>2009</option>
                                <option value="2010" {{ $veiculo->ano == '2010' ? 'selected' : '' }}>2010</option>
                                <option value="2011" {{ $veiculo->ano == '2011' ? 'selected' : '' }}>2011</option>
                                <option value="2012" {{ $veiculo->ano == '2012' ? 'selected' : '' }}>2012</option>
                                <option value="2013" {{ $veiculo->ano == '2013' ? 'selected' : '' }}>2013</option>
                                <option value="2014" {{ $veiculo->ano == '2014' ? 'selected' : '' }}>2014</option>
                                <option value="2015" {{ $veiculo->ano == '2015' ? 'selected' : '' }}>2015</option>
                                <option value="2016" {{ $veiculo->ano == '2016' ? 'selected' : '' }}>2016</option>
                                <option value="2017" {{ $veiculo->ano == '2017' ? 'selected' : '' }}>2017</option>
                                <option value="2018" {{ $veiculo->ano == '2018' ? 'selected' : '' }}>2018</option>
                                <option value="2019" {{ $veiculo->ano == '2019' ? 'selected' : '' }}>2019</option>
                                <option value="2020" {{ $veiculo->ano == '2020' ? 'selected' : '' }}>2020</option>
                                <option value="2021" {{ $veiculo->ano == '2021' ? 'selected' : '' }}>2021</option>
                                <option value="2022" {{ $veiculo->ano == '2022' ? 'selected' : '' }}>2022</option>
                                <option value="2023" {{ $veiculo->ano == '2023' ? 'selected' : '' }}>2023</option>
                                <option value="2024" {{ $veiculo->ano == '2024' ? 'selected' : '' }}>2024</option>
                            </select>
                        </div>
                        <div class="col-md-8" id="nomeMaquinas">
                            <label class="form-label" for="veiculo_maquina">Modelo/Ano</label>
                            <input class="form-control" id="veiculo_maquina" name="veiculo_maquina" type="veiculo_maquina" value="{{ old('veiculo_maquina', $veiculo->veiculo) }}" placeholder="Preenchimento Automático">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="form-label" for="valor_fipe">Valor</label>
                            <div class="d-flex">
                                <span class="pr-2" style="margin-top: 10px; font-size:18px; margin-right: 8px">R$ </span>
                                <input class="form-control" id="valor_fipe" name="valor_fipe" type="text" value="{{ old('valor_fipe', $veiculo->valor_fipe) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="codigo_fipe">Código</label>
                            <input class="form-control" id="codigo_fipe" name="codigo_fipe" type="text" value="{{ old('codigo_fipe', $veiculo->codigo_fipe) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="fipe_mes_referencia">Mês de referência</label>
                            <input class="form-control" id="fipe_mes_referencia" name="fipe_mes_referencia" type="text" value="{{ old('fipe_mes_referencia', $veiculo->fipe_mes_referencia) }}">
                        </div>
                    </div>

                    <div class="row mt-3" id="divHorimetro">
                        <div class="col-md-2">
                            <label class="form-label" for="horimetro_inicial">Horímetro inicial</label>
                            <input class="form-control" id="horimetro_inicial" name="horimetro_inicial" type="number" value="{{ old('horimetro_inicial', $veiculo->horimetro_inicial) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="codigo_da_maquina">Código da Máquina</label>
                            <input class="form-control" id="codigo_da_maquina" name="codigo_da_maquina" type="text" value="{{ old('codigo_da_maquina', $veiculo->codigo_da_maquina) }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-8">
                            <label class="form-label" for="observacao">Observação</label>
                            <textarea class="form-control" id="observacao" name="observacao" cols="30" rows="6">{{ $veiculo->observacao }}</textarea>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-2">
                            <label class="form-label" for="situacao">Situação</label>
                            <select class="form-select" id="situacao" name="situacao">
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo" {{ $veiculo->situacao == 'Inativo' ? 'selected' : '' }}>Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mt-5">
                        <button class="btn btn-primary btn-sm font-weight-medium" type="submit">Salvar</button>

                        <a href="{{url('admin/ativo/veiculo')}}">
                            <button class="btn btn-warning btn-sm font-weight-medium" type="button">Cancelar</button>
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- MODAL INCLUSAO RAPIDA DE OBRAS --}}
@include('pages.cadastros.obra.partials.inclusao-rapida')
@include('pages.ativos.veiculos.marca-inclusao-rapida')


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha256-Kg2zTcFO9LXOc7IwcBx1YeUBJmekycsnTsq2RuFHSZU=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function($) {
        $('#valor_fipe').mask('000.000.000.000.000,00', {
            reverse: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#tipo').on('change', function() {
            var tipo = $(this).val();
            if (tipo == 'motos' || tipo == 'carros' || tipo == 'caminhoes') {
                $.ajax({
                    url: 'https://parallelum.com.br/fipe/api/v1/' + tipo + '/marcas',
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#marca').empty().append(
                            '<option value="" selected>Selecione a marca</option>');
                        $.each(data, function(key, value) {
                            $('#marca').append('<option value="' + value.codigo +
                                '">' + value.nome + '</option>');
                        });
                    }
                });
            } else {
                $('#marca').empty().append('<option value="" selected>Selecione o tipo</option>');
                $('#valor_fipe').val('R$ ');
            }
        });

        $('#marca').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            $('#marca_nome').val(selectedOption.text());
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#marca').on('change', function() {
            var tipo = $('#tipo').val();
            var marcaId = $(this).val();
            if (marcaId) {
                $.ajax({
                    url: 'https://parallelum.com.br/fipe/api/v1/' + tipo + '/marcas/' +
                        marcaId + '/modelos',
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#modelo').empty().append(
                            '<option value="" selected>Selecione o modelo</option>');
                        $.each(data.modelos, function(key, value) {
                            $('#modelo').append('<option value="' + value.codigo +
                                '">' + value.nome + '</option>');
                            $('#modelo_nome').val(value.nome);
                        });
                    }
                });
            } else {
                $('#modelo').empty().append('<option value="" selected>Selecione o modelo</option>');
            }
        });

        $('#modelo').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            $('#modelo_nome').val(selectedOption.text());
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#modelo').on('change', function() {
            var tipo = $('#tipo').val();
            var marcaId = $('#marca').val();
            var modeloId = $(this).val();
            if (modeloId) {
                $.ajax({
                    url: 'https://parallelum.com.br/fipe/api/v1/' + tipo + '/marcas/' +
                        marcaId + '/modelos/' + modeloId + '/anos',
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#ano').empty().append(
                            '<option value="" selected>Selecione o ano</option>');
                        $.each(data, function(key, value) {
                            $('#ano').append('<option value="' + value.codigo +
                                '">' + value.nome + '</option>');

                        });
                    }
                });
            } else {
                $('#ano').empty().append('<option value="" selected>Selecione o ano</option>');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#ano').on('change', function() {
            var tipo = $('#tipo').val();
            var marcaId = $('#marca').val();
            var modeloId = $('#modelo').val();
            var anoId = $(this).val();
            if (anoId) {
                $.ajax({
                    url: 'https://parallelum.com.br/fipe/api/v1/' + tipo + '/marcas/' +
                        marcaId + '/modelos/' + modeloId + '/anos/' + anoId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#veiculo').val(data.Modelo);
                        $('#valor_fipe').val(data.Valor.replace(/[^\d\,]/g, '').replace(',', '.'));
                        $('#valor_fipe').val(data.Valor);
                        $('#codigo_fipe').val(data.CodigoFipe);
                        $('#fipe_mes_referencia').val(data.MesReferencia);
                    }
                });
            } else {
                $('#veiculo').val('');
                $('#valor_fipe').val('R$ ');
                $('#codigo_fipe').val('');
                $('#fipe_mes_referencia').val('');
            }
        });
    });
</script>
<script>
    function mostrarEsconderInputs() {
        var tipo = document.getElementById("tipo").value;
        if (tipo == "motos" || tipo == "carros" || tipo == "caminhoes") {
            document.getElementById("divPlacaRenavam").style.display = "";
            document.getElementById("divHorimetro").style.display = "none";
        } else if (tipo == "maquinas") {
            document.getElementById("divPlacaRenavam").style.display = "none";
            document.getElementById("divHorimetro").style.display = "";
        } else {
            document.getElementById("divPlacaRenavam").style.display = "none";
            document.getElementById("divHorimetro").style.display = "none";
        }
        var tipo = document.getElementById("tipo").value;
        if (tipo === "maquinas") {
            document.getElementById("marcaMaquinas").style.display = "block";
            document.getElementById("marcaVeiculos").style.display = "none";
        } else {
            document.getElementById("marcaMaquinas").style.display = "none";
            document.getElementById("marcaVeiculos").style.display = "block";
        }
        var tipo = document.getElementById("tipo").value;
        if (tipo === "maquinas") {
            document.getElementById("modeloMaquinas").style.display = "block";
            document.getElementById("modeloVeiculos").style.display = "none";
        } else {
            document.getElementById("modeloMaquinas").style.display = "none";
            document.getElementById("modeloVeiculos").style.display = "block";
        }
        var tipo = document.getElementById("tipo").value;
        if (tipo === "maquinas") {
            document.getElementById("anoMaquinas").style.display = "block";
            document.getElementById("anoVeiculos").style.display = "none";
        } else {
            document.getElementById("anoMaquinas").style.display = "none";
            document.getElementById("anoVeiculos").style.display = "block";
        }
        var tipo = document.getElementById("tipo").value;
        if (tipo === "maquinas") {
            document.getElementById("nomeMaquinas").style.display = "block";
            document.getElementById("nomeVeiculos").style.display = "none";
        } else {
            document.getElementById("nomeMaquinas").style.display = "none";
            document.getElementById("nomeVeiculos").style.display = "block";
        }
    }
</script>

<script>
    $(document).ready(function() {
        $('#addMarcaModal').on('hidden.bs.modal', function(e) {
            $('#add_marca_da_maquina').val('');
        });
    });
</script>
<script>
    $(document).ready(function() {
        var modeloSelect = $("#modelo_da_maquina");
        var anoSelect = $("#ano_da_maquina");
        var veiculoInput = $("#veiculo_maquina");

        modeloSelect.on("change", updateVeiculo);
        anoSelect.on("change", updateVeiculo);

        function updateVeiculo() {
            var modeloValue = modeloSelect.val();
            var anoValue = anoSelect.val();
            veiculoInput.val(modeloValue + '/' + anoValue);
        }
    });
</script>

@endsection