@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="page-header my-4">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span>
        Lista de Manutenções
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
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

@if(session('mensagem'))
<div class="alert alert-warning">
    {{ session('mensagem') }}
</div>
@endif

<div class="accordion accordion-flush" id="accordionFlushExample">
    <div class="accordion-item">
        <h2 class=" d-flex accordion-header  bg-secondary text-white" id="flush-headingOne">

            <a href="{{ route('ativo.veiculo.manutencao.adicionar', $veiculo->id) }}">
                <button class="btn btn-sm btn-danger">Novo Registro</button>
            </a>
            <button class="accordion-button collapsed  bg-secondary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Pesquisar
            </button>

        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
                <div class=" d-flex justify-content-between p-2 bd-highlight m-0 p-0 mr-5 ">

                    <form method="get" class="form" enctype="multipart/form-data" id="form_manutencao">
                        @csrf

                        <input type="hidden" value="{{$veiculo->id}}" id="veiculo_id" name="veiculo_id">

                        <div class="p-2 bd-highlight ">
                            <div class="d-flex bd-highlight">

                                <div class="p-2  bd-highlight col-3 mx-3">
                                    <div class="mb-3 row">
                                        <label for="data_inicio" class="col-sm-4 col-form-label m-0 p-0" style="text-align: right;">Data inicio:</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" id="data_inicio" name="data_inicio">
                                        </div>
                                    </div>
                                </div>

                                <div class="p-2  bd-highlight col-4">
                                    <div class="mb-3 row">
                                        <label for="data_fim" class="col-sm-3 col-form-label m-0 p-0" style="text-align: right;">Data Fim:</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" id="data_fim" name="data_fim">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-7">

                                    <label class="form-label p-0 m-0 " style="text-align: right;">Lista de Fornecedores:</label>


                                    <select class="form-select form-control" id="search" name="search">
                                        <option value='' selected="">Selecione um Fornecedor</option>
                                        @foreach ($fornecedores as $fornecedor)
                                        <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome_fantasia }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="p-2  bd-highlight col-2">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label p-0 m-0" style="text-align: right;">Listar</label>
                                        <div class="col-sm-8">
                                            <select class="form-select form-control" id="lista" name="lista" aria-label="Default select example">
                                                <option value="7" selected="">7</option>
                                                <option value="5">5</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@include('pages.ativos.veiculos.partials.header')




<div class="row">
    <div class="col-md-12 grid-margin stretch-card">

        <div class="card p-1">
            <div class="card-body p-1">

                {{--Tabela--}}

            </div>
        </div>
    </div>
</div>

    <div class="d-flex bd-highlight" style="margin-bottom: 90px; width:auto; height:500px">
        <div class="p-2 flex-fill bd-highlight " style="width:800px !important; height:500px">
            <canvas id="custoManutencao" width="800" height="500" style="width:800px !important; height:500px"></canvas>
        </div>

        <div class="d-flex m-auto ml-5" >
            <canvas id="anual" width="400" height="400" style="left:200px;width:400px !important; height:400px"></canvas>
        </div>
        <div class="">
            <form action="{{route('ativo.veiculo.manutencao.custoAno', $veiculo->id)}}" method="get" class="row g-3">
                <div class="col-auto">
                    <label for="inputPassword2" class="visually-hidden">Pesquisar o ano</label>
                    <select class="form-select" aria-label="Default select example" name="ano">
                        <option selected>Selecionar</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-3">Pesquisar</button>
                    <a class="btn btn-warning mb-3" href="{{route('ativo.veiculo.manutencao.index', $veiculo->id)}}">
                        Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="anexarArquivoAtivoManutencao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="anexarArquivoAtivoManutencaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anexarArquivoAtivoExternoLabel">Anexo Manutencao Veiculo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="lista_anexo">

            </div>

            <form action="{{ route('anexo.upload') }}" id="form" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="diretorio" value="manutencao">
                <input type="hidden" name="id_modulo" value="28">
                <input type="hidden" name="id_veiculo" id="id_veiculo" value="">

                <div class="modal-body">
                    <div class="mb-3 col-2">

                        <input type="hidden" class="form-control" id="id_item" name="id_item" readonly>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label class="form-label">Nome do arquivo</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">Data de validade do arquivo</label>
                            <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" required placeholder="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="file" accept="image/*,.pdf" class="form-control" id="arquivo" name="arquivo" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" id="detalhes" name="detalhes" placeholder="Detalhes"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Salvar Anexo</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($custoAnualManutencao as $custoAnual)
<input type="hidden" id="custoAnual" value="{{$custoAnualManutencao}}">
@endforeach


<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>

	$(document).ready(function() {
	    var jsonCustoAnual = $('#custoAnual').val();
	    const arrayCustoAnual = JSON.parse(jsonCustoAnual);
	    const mesCustoAnual = arrayCustoAnual.map(row => row.mesCustoAnoManut);
	    const totalCustoAnual = arrayCustoAnual.map(row => row.custoAnoManut);
	
	    const labelsCustoManutencao = {!! json_encode($dataSets) !!};
	    const labelsMesManutencao = {!! json_encode($mesesFormatados) !!};
	
	    new Chart(document.getElementById("anual"), {
	        type: 'doughnut',
	        data: {
	            labels: mesCustoAnual,
	            datasets: [{
	                label: 'Custo Anual',
	                backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850", "#3e95cd", "#8e5ea2", "#3cba9f"],
	                data: totalCustoAnual,
	            }]
	        },
	        options: {
	            title: {
	                display: true,
	                text: "Total de Manutenções Anual",
	            },
	            responsive: true,
	            maintainAspectRatio: false,
	            legend: {
	                position: 'right',
	            },
	            plugins: {
	                datalabels: {
	                    color: 'black',
	                    anchor: 'end',
	                    align: 'end',
	                    formatter: function(value) {
	                        return new Intl.NumberFormat('pt-BR', {
	                            style: 'currency',
	                            currency: 'BRL'
	                        }).format(value);
	                    }
	                }
	            }
	        }
	    });
	
	    new Chart(document.getElementById("custoManutencao"), {
	        type: 'bar',
	        data: {
	            labels: labelsMesManutencao,
	            datasets: labelsCustoManutencao,
	        },
	        options: {
	            title: {
	                display: true,
	                text: "Custo de Manutenção Mensal",
	            },
	            scales: {
	                yAxes: [{
	                    ticks: {
	                        beginAtZero: true,
	                        max: 15000, // Valor limite do eixo Y
	                        callback: function(value) {
	                            return new Intl.NumberFormat('pt-BR', {
	                                style: 'currency',
	                                currency: 'BRL'
	                            }).format(value);
	                        }
	                    }
	                }]
	            },
	            responsive: true,
	            maintainAspectRatio: false,
	            plugins: {
	                datalabels: {
	                    color: 'black',
	                    anchor: 'end',
	                    align: 'end',
	                    formatter: function(value) {
	                        return new Intl.NumberFormat('pt-BR', {
	                            style: 'currency',
	                            currency: 'BRL'
	                        }).format(value);
	                    }
	                }
	            }
	        }
	    });
	    
	    carregarTabela(0)
	});

    $(document).on('click', '.paginacao a', function(e) {
        e.preventDefault();
        var link = $(this).attr('aria-label');
        var pagina = $(this).attr('href').split('page=')[1];
        carregarTabela(pagina);

    });
    $(document).on('change keyup', '.form', function(e) {
        e.preventDefault();
        carregarTabela(0);
    });

    function carregarTabela(pagina) {
        
        var id_veiculo = "{{$veiculo->id}}"
        
        $('#page').val(pagina);
        var dados = $('#form_manutencao').serialize();
        $.ajax({
            url: "/admin/ativo/veiculo/manutencao/list/" + id_veiculo + "?page=" + pagina,
            method: 'GET',
            data: dados
        }).done(function(data) {
            $('.card-body').html(data);
        });

    }
</script>

<script>
    $(document).ready(function() {
        $(document).on("click", ".manutencao", function() {
            var id_ativo_externo = $(this).data('id');
            $(".modal-body #id_item").val(id_ativo_externo);
            var dados = $('#form').serialize();
            $.ajax({
                    url: "{{ url('admin/ativo/veiculo/manutencao/anexo') }}/" + id_ativo_externo,
                    method: 'GET',
                    data: dados
                })
                .done(function(result) {
                    $("#lista_anexo").html(result)
                })
                .fail(function(jqXHR, textStatus, result) {

                });
        });

    });
</script>
@endsection