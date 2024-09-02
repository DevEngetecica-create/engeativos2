@extends('dashboard')
@section('title', 'Ativos Externos')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Editar Ativo Externo
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

@if(session('mensagem'))
<div class="alert alert-warning">
    {{ session('mensagem') }}
</div>
@endif
@if (session('successo'))

    <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
      <div>
        {{ session('successo') }}
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

@endif

@foreach ($estoques as $estoque)
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



                <form method="post" action="{{ route('ativo.externo.update', $estoque->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Categoria:</label>
                            {{ $estoque->ativo_externo->titulo }}
                            <select class="form-control" name="id_ativo_configuracao" readonly>
                                <option value="">Selecione uma Categoria</option>
                                @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ $estoque->ativo_externo->id == $categoria->id ? 'selected' : '' }}>{{ $categoria->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Obra</label> <button class="badge badge-primary" data-toggle="modal" data-target="#modal-add" type="button"><i class="mdi mdi-plus"></i></button>
                            <select class="form-select select2" name="id_obra">
                                <option value="">Selecione uma Obra</option>

                                @foreach ($obras as $obra)
                                <option value="{{ $obra->id }}" {{ $estoque->obra->id == $obra->id ? 'selected' : '' }}>
                                    {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                </option>
                                @endforeach

                            </select>
                        </div>

                    </div>


                    <div class="row mt-3">
                        <div class="col-md-2">
                            <label class="form-label" for="patrimonio">Patrimônio</label>
                            <input class="form-control" id="patrimonio" name="patrimonio" type="text" value="{{ $estoque->patrimonio ?? old('patrimonio') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="titulo">Título</label>
                            <input class="form-control" id="titulo" name="titulo" type="text" value="{{ $estoque->ativo_externo->titulo ?? old('titulo') }}">
                        </div>
                        
                    </div>
                    
                    <div class="row my-4 pt-3 pb-5" id="div_calibracao" style="display: none;">
                        <div class="col-3">
                            <label class="form-label">Marca</label>
                            <input class="form-control" id="marcaCalibra" name="marcaCalibra" type="text" value="{{ $estoque->ativo_externo->marcaCalibra ?? old('marcaCalibra') }}">
                        </div>
                    
                        <div class="col-3">
                            <label class="form-label">Modelo</label>
                            <input class="form-control " id="modeloCalibra" name="modeloCalibra" type="text" value="{{ $estoque->ativo_externo->modeloCalibra ?? old('modeloCalibra') }}">
                        </div>
                    
                        <div class="col-md-6">
                            <label class="form-label">Nº de Série</label>
                            <input class="form-control " id="n_serie" name="n_serie" type="text" value="{{ $estoque->ativo_externo->n_serie ?? old('n_serie') }}">
                        </div>
                    
                    </div>
                    
                    <div class="row mt-3">

                        <div class="col-md-2">
                            <label class="form-label" for="status">Valor</label>
                            <input class="form-control" id="valor" name="valor" type="text" value="{{ $estoque->valor ?? old('valor') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" for="calibracao">Precisa Calibrar?</label>
                            <select class="form-select select2" id="calibracao" name="calibracao">
                                <option value="Sim">Sim</option>
                                <option value="Não" {{ $estoque->calibracao == "Não" ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                        
                        
                        <div class="col-md-5 col-sm-6 col-12" id="menssagem_alert" style="display: none;">
                            <div class="card mb-3" style="max-width: 540px;">
                                <div class="row g-0 ">
                                    <div class="d-flex justify-content-center align-items-center col-md-2 px-5 bg-warning">
                                        <i class="mdi mdi-alert mdi-48px"></i>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body p-1 text-center">
                                            <h5 class="card-title">ATENÇÃO</h5>
                                            <p class="card-text">Os campos destacados deves estar preenchidos</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex  row ">
                        <div class="col-md-2 mt-3 mb-5">
                            <label class="form-label" for="status">Situação</label>
                            <select class="form-select select2" name="status" id="status">
                                @foreach ($situacoes as $situacao)
                                <option value="{{ $situacao->id }}" {{ $estoque->status == $situacao->id ? 'selected' : '' }}>{{ $situacao->titulo }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <hr class="dropdown-divider">

                    <div class="d-flex row mt-3">
                        <div class="form-group col-md-6">
                            <div>
                                <label for="formFile" class="form-label"><h5> Imagem (400 x 300 px)</h5></label>
                                <input class="form-control" type="file" name="imagem" id="imagem"  value="{{ $estoque->imagem ??  old('imagem') }}" onChange="carregarImg()">
                                <span class="text-danger">Extensões de imagens permitidas = 'png', 'jpg', 'jpeg', 'gif'</span>
                            </div>
                            
                        </div>
                        <div class="form-group col-md-6 my-3">
                            <!-- rota imagem padrão-->
                            @if($estoque->imagem)
                            <img src="{{url('storage/imagem_ativo')}}/{{$estoque->imagem}}" id="target"  class="img-thumbnail" style="width:500px; height: 300px;">
                            @else
                            <img src="{{url('storage/imagem_ativo/nao-ha-fotos.png')}}" id="target"  class="img-thumbnail" style="width: 500px; height: 300px;">
                            @endif
                            
                        </div>
                    </div>



                   <div class="col-12 mt-4">
                        <input name="id_ativo_externo" id="id_ativo_externo" type="hidden" value="{{ $estoque->id_ativo_externo }}">
                        <input name="id_patrimonio" id="id_patrimonio" type="hidden" value="{{ $estoque->id}}">
                        <button class="btn btn-primary btn-md font-weight-medium" type="submit">Salvar</button>
                         <a href="{{ url('admin/ativo/externo') }}">
                            <button class="btn btn-warning btn-md font-weight-medium" type="button">Cancelar</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal -->
<div class="modal fade" id="anexarArquivoAtivoExternoForaOpe" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="anexarArquivoAtivoExternoForaOpeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anexarArquivoAtivoExternoForaOpeLabel">Relatório de Descarte da Ferramenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="lista_anexo">

            </div>

            <hr>

            <div class="modal-body modal-dialog-scrollable">
                <form action="{{ route('anexo.upload') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="diretorio" value="externo">
                    <input type="hidden" name="id_modulo" value="13">



                    <div class="mb-3 col-2">

                        <input type="hidden" class="form-control" id="id_item" name="id_item" required readonly>

                    </div>

                    <div class="row">
                        <div class="mb-3 col-6">
                            <label class="form-label">Nome do arquivo</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">Data do Descarte</label>
                            <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" required placeholder="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="file" accept="image/*,.pdf" class="form-control" id="arquivo" name="arquivo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descreva o motivo do descarte</label>
                        <textarea class="form-control" id="detalhes" name="detalhes" rows="20" style="height: 250px !important;"></textarea>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Salvar Anexo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- MODAL INCLUSAO RAPIDA DE OBRAS --}}
@include('pages.cadastros.obra.partials.inclusao-rapida')
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha256-Kg2zTcFO9LXOc7IwcBx1YeUBJmekycsnTsq2RuFHSZU=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function($) {
        $('#valor').mask('000.000.000.000.000,00', {
            reverse: true
        });
    });
</script>


<!--SCRIPT PARA CARREGAR IMAGEM PRINCIPAL -->
<script type="text/javascript">

  function carregarImg() {

    var target = document.getElementById('target');
    var file = document.querySelector("input[type=file]").files[0];
    var reader = new FileReader();

    reader.onloadend = function () {
      target.src = reader.result;
    };

    if (file) {
      reader.readAsDataURL(file);


    } else {
      target.src = "";
    }
  }

</script>

<!--validar a calibração-->

<script>
    $(document).ready(function() {
        
   
        console.log($('#calibracao').val())
        
        if($('#calibracao').val() == "Não"){
            
            document.getElementById("div_calibracao").style.display = 'none';
            document.getElementById("menssagem_alert").style.display = ' none'
            
        }else if ($('#calibracao').val() == "Sim"){
           
            document.getElementById("div_calibracao").style.display = 'flex';
            document.getElementById("menssagem_alert").style.display = ' flex';
            document.getElementById("modeloCalibra").style.borderColor= "red"
            document.getElementById("marcaCalibra").style.borderColor= "red"
            document.getElementById("n_serie").style.borderColor= "red"
        }

        /*$('#calibracao').change(function() {
            var selectValue = $(this).val(); // Obtém o valor selecionado

            // Verifica o valor selecionado
            if (selectValue == "Sim") {
                
                document.getElementById("div_calibracao").style.display = 'flex';
                document.getElementById("menssagem_alert").style.display = ' flex'
               

                var valorQtde = $('#quantidade').val();

                if (selectValue === "Sim" && valorQtde > 1) {

                    alert("Só é possivel cadastrar um item calibrado por vez");

                    document.getElementById("quantidade").value = 1;

                    var x = document.getElementById("quantidade").value = 1;

                    console.log(x)
                }

            } else {
                document.getElementById("div_calibracao").style.display = 'none';
                document.getElementById("menssagem_alert").style.display = ' none'
            }
        });
        */
    });

    function valorQuantidade(inputElement) {

        // Obtenha o valor do input quantidade
        var valorQtde = inputElement.value;

        if ($('#calibracao').val() == "Sim" && valorQtde > 1) {

            alert("Só é possivel cadastrar um item calibrado por vez");

            inputElement.value = 1;
        }

    }
</script>

<script>
    $(document).ready(function() {

        //Carrega o ID da ferramenta para popular a Lista da Modal
        var id_ativo_externo = $('#id_patrimonio').val();
        $(".modal-body #id_item").val(id_ativo_externo);

        //Rota para popular a modal

        $.ajax({
                url: "{{ url('admin/ativo/externo/anexoRelatorioDescarte') }}/" + id_ativo_externo,
                type: 'get',
                data: {}
            })
            .done(function(result) {
                $("#lista_anexo").html(result)
            })
            .fail(function(jqXHR, textStatus, result) {

            });

        //console.log(url)
        //condição para chamar a modal de anexo dos relatórios

        $('#status').change(function() {
            var selectValue = $(this).val(); // Obtém o valor selecionado

            // Verifica se o valor selecionado é igual a 9
            if (selectValue == '9') {
                $('#anexarArquivoAtivoExternoForaOpe').modal('show'); // Exibe o modal
            }
        });
    });
</script>