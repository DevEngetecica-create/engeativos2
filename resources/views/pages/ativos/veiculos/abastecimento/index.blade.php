@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        
    
    <div class="row">
        <div class="col-6 breadcrumb-item active" aria-current="page">
            <h3 class="page-title text-left">
                
                @if ($veiculo->tipo == 'maquinas')
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-gas-station mdi-24px"></i>
                    </span> 
                    
                    Abastecimento da Máquina
                @else
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-gas-station mdi-24px"></i>
                    </span> 
                
                    Abastecimento do Veículo
                    
                @endif
            </h3>
        </div>

        <div class="col-4 active m-2">
            <h5 class="page-title text-left m-0">
                <span>Veículos <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
            </h5>
        </div>
    </div>

    <hr>
    
    <div class="page-header">
        <h3 class="page-title">
            <a class="btn btn-md btn-success" href="{{ route('ativo.veiculo.abastecimento.adicionar', $veiculo->id) }}">
                Adicionar
            </a>
        </h3>
    </div>

        
        
        @if(session('mensagem'))
        <div class="alert alert-warning">
            {{ session('mensagem') }}
        </div>
        @endif
        
        <div class="row mt-4">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body p-3">
        
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
        
                        {{-- DADOS DO VEÍCULO/MÁQUINA --}}
        
                        @include('pages.ativos.veiculos.partials.header')
        
                        <div class="d-flex justify-content-start">
                            <span class="text-capitalize col-2 bg-primary p-1 rounded shadow text-white text-center my-2">
                                Qtde Media: {{$media_quantidade}} litros
                            </span>
        
                            <span class="text-capitalize col-2 bg-primary p-1 rounded shadow text-white text-center my-2 mx-3">
                                Media valor/ litro: R$ {{Tratamento::FormatBrMoeda($media_valor_do_litro)}}
                            </span>
        
                            <span class="text-capitalize col-3 bg-primary p-1 rounded shadow text-white text-center my-2">
                                Media valor/ abastecimento: R$ {{Tratamento::FormatBrMoeda($media_valor_total)}}
                            </span>
        
                        </div>
        
        
        
                        <hr>
        
                        <table class="table-hover table-striped table mt-3">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Combustível</th>
        
                                    @if ($veiculo->tipo == 'maquinas')
                                    <th class="text-center">Últ. reg.</th>
                                    <th class="text-center">Horas traba.</th>
                                    <th class="text-center">Qtde. (litros)</th>
                                    <th class="text-center">Hr/ litro</th>
                                    @else
                                    <th class="text-center">km</th>
                                    <th class="text-center">km's rodados</th>
                                    <th class="text-center">Qtde. (litros)</th>
                                    <th class="text-center">km's/ l</th>
                                    @endif
        
                                    <th class="text-center">Qtde. Média(litros)</th>
                                    <th class="text-center">Custo</th>
                                    <th class="text-center">Média Abast.</th>
                                    <th class="text-center">Data</th>
                                    <th width="10%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
        
                                @foreach ($abastecimentos->reverse() as $index => $abastecimento)
                                <tr>
                                    <td class="text-center"><span class="badge badge-dark">{{ $abastecimento->id }}</span></td>
        
                                    <!-- tipo do combustível -->
                                    <td class="text-center">{{ $abastecimento->combustivel }}</td>
        
                                    {{-- Verifica se não é o último registro e efetua os cálculos --}}
        
                                    @if ($index >0)
                                    @php
        
                                    $registroAtual = $abastecimento;
        
        
                                    $registroPosterior = $abastecimentos[$index - 1];
        
                                    $valorMedio = ($registroAtual->valor_total + $registroPosterior->valor_total) / 2;
                                    $qtdeMedia = ($registroAtual->quantidade + $registroPosterior->quantidade) / 2;
                                    $kmRodados = $registroAtual->quilometragem - $registroPosterior->quilometragem;
                                    $hrTraba = $registroAtual->horimetro - $registroPosterior->horimetro;
        
                                    if($veiculo->tipo == "maquinas"){
                                        
                                        $consumoMedio = $hrTraba / $abastecimento->quantidade;
        
                                    }else{
        
                                        $consumoMedio = $kmRodados / $abastecimento->quantidade;
                                    }
        
        
                                    $consumoMedio_arredondado = number_format(round($consumoMedio, 2), 2, '.', '');
        
                                    @endphp
                                    {{--dd($kmRodados)--}}
                                    @if ($veiculo->tipo == 'maquinas')
        
                                    <!-- registro do último horimetro -->
                                    <td class="text-center">{{ $abastecimento->horimetro ?? 0}}</td>
                                    <!-- qtde de litros de combustível -->
                                    <td class="text-center">
                                        <span class="bg-primary p-1 rounded shadow-sm text-white text-center my-2">{{ $hrTraba}}</span>
                                    </td>
                                    
        
                                    @else
                                    <!-- registro do último km -->
                                    <td class="text-center">{{ $abastecimento->quilometragem ?? 0}}</td>
        
                                    <!-- resultado do calculo do km atual com o novo -->
                                    <td class="text-center"><span class="bg-primary p-1 rounded shadow-sm text-white text-center my-2">{{ $kmRodados}}</span></td>
        
                                    @endif
        
        
                                    
        
                                    <!-- qtde de litros de combustível -->
                                    <td class="text-center">{{ $abastecimento->quantidade }}</td>
        
                                    <!-- qtde de litros de combustível -->
                                    <td class="text-center"><span class="bg-warning p-1 rounded shadow-sm text-white text-center my-2">{{ $consumoMedio_arredondado }} km's</span></td>
        
                                    <!-- média de consumo dos litros -->
                                    <td class="text-center">
                                        {{ $qtdeMedia }}
                                    </td>
        
                                    <!-- valor por litro de combustivel -->
                                    <td class="text-center">R$ {{Tratamento::FormatBrMoeda($abastecimento->valor_total) }}</td>
        
                                    <!-- valor medio por litro de combustivel -->
                                    <td class="text-center">
                                        R$ {{ Tratamento::FormatBrMoeda($valorMedio) }}
                                    </td>
        
                                    <!-- data do abastecimento -->
                                    <td class="text-center">{{ Tratamento::dateBr($abastecimento->data_cadastro) }}</td>
        
                                    <td class="d-flex">
                                        <a data-bs-toggle="modal" data-bs-target="#anexarArquivoAtivoAbastecimento" class="abastecimento" href="javascript:void(0)" data-id="{{ $abastecimento->id}}">
                                            <span class='btn btn-success btn-sm ml-1' title="Editar">
                                                <i class="mdi mdi-upload"></i>
                                            </span>
                                        </a>
        
                                        <a href="{{ route('ativo.veiculo.abastecimento.editar', $abastecimento->id) }}">
                                            <button class="btn btn-info btn-sm mx-2" data-toggle="tooltip" data-placement="top" title="Editar">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                        </a>
        
                                        @if ($loop->first)
                                        <form action="{{ route('ativo.veiculo.abastecimento.delete', $abastecimento->id) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <a class="excluir-padrao" data-id="{{ $abastecimento->id }}" data-table="empresas" data-module="cadastro/empresa">
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                                    <i class="mdi mdi-delete"></i></button>
                                            </a>
                                        </form>
                                        @endif
                                    </td>
        
        
        
                                    @else
        
        
                                    @if ($veiculo->tipo == 'maquinas')
        
                                        <!-- registro do último horimetro -->
                                        <td class="text-center">{{ $abastecimento->horimetro }}</td>
                                        <td class="text-center">Sem reg.</td>
                                    
                                    @else
                                    
                                        <!-- registro do último km -->
                                        <td class="text-center">{{ $abastecimento->quilometragem }}</td>
                                        
                                        <!-- resultado do calculo do km atual com o novo -->
                                        <td class="text-center">Sem reg.</td>
                                    
                                    @endif
                                    
                                   
        
                                    <td class="text-center">{{ $abastecimento->quantidade }}</td>
        
                                    <td class="text-center"><span class="bg-warning p-1 rounded shadow text-white text-center my-2">Sem reg.</span></td>
        
                                    <td class="text-center">Não há média</td>
        
                                    <td class="text-center">R$ {{ Tratamento::FormatBrMoeda($abastecimento->valor_total) }}</td>
                                    <td class="text-center">Sem reg.</td>
        
        
                                    <td class="text-center">{{ Tratamento::dateBr($abastecimento->data_cadastro) }}</td>
        
                                    <td class="d-flex">
                                        <a data-bs-toggle="modal" data-bs-target="#anexarArquivoAtivoAbastecimento" class="abastecimento" href="javascript:void(0)" data-id="{{ $abastecimento->id}}">
                                            <span class='btn btn-success btn-sm ml-1' title="Editar">
                                                <i class="mdi mdi-upload"></i>
                                            </span>
                                        </a>
        
                                        <a href="{{ route('ativo.veiculo.abastecimento.editar', $abastecimento->id) }}">
                                            <button class="btn btn-info btn-sm mx-2" data-toggle="tooltip" data-placement="top" title="Editar">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                        </a>
        
                                        @if ($loop->first)
                                        <form action="{{ route('ativo.veiculo.abastecimento.delete', $abastecimento->id) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <a class="excluir-padrao" data-id="{{ $abastecimento->id }}" data-table="empresas" data-module="cadastro/empresa">
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                                    <i class="mdi mdi-delete"></i></button>
                                            </a>
                                        </form>
                                        @endif
                                    </td>
                                    @endif
        
        
                                </tr>
                                @endforeach
        
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="anexarArquivoAtivoAbastecimento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="anexarArquivoAtivoAbastecimentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anexarArquivoAtivoExternoLabel">Anexo de Abastecimento do Veiculo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="lista_anexo">

            </div>

            <form action="{{ route('anexo.upload') }}" id="form" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="diretorio" value="abastecimento">
                <input type="hidden" name="id_modulo" value="29">
                <input type="hidden" name="id_veiculo" id="id_veiculo" value="{{$veiculo->id}}">
                <div class="modal-body">
                    <div class="mb-3 col-2">

                        <input type="hidden" class="form-control" id="id_item" name="id_item" readonly>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-12">
                            <label class="form-label">Empresa</label>
                            <input type="text" class="form-control" id="nome_empresa" name="nome_empresa" required>
                        </div>

                        <div class="mb-3 col-6">
                            <label class="form-label">Nome do arquivo</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">Data do arquivo</label>
                            <input type="date" class="form-control" id="data_cadastro" name="data_cadastro" required placeholder="">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(function() {
        $(document).on("click", ".abastecimento", function() {
            var id_ativo_externo = $(this).data('id');
            var dados = $('#form').serialize();

            $(".modal-body #id_item").val(id_ativo_externo);

            $.ajax({
                    url: "{{ url('admin/ativo/veiculo/abastecimento/anexo/') }}/" + id_ativo_externo,
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