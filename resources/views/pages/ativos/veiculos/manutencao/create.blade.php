@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span>
    </h3>

    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                Ativos
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

                <form method="post" action="{{ route('ativo.veiculo.manutencao.store') }}" enctype="multipart/form-data">

                    @csrf
                   
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="form-label" for="id_obra">Obra</label>  
                            <select class="form-select select2" id="id_obra" name="id_obra">  
                            <option value="">Selecione uma Obra</option>
                                @foreach ($obras as $obra)
                                <option value="{{ $obra->id }}">
                                    {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="fornecedor_id">Fornecedor</label>
                            <select class="form-select select2" id="fornecedor_id" name="fornecedor_id" required>
                                <option value="">Selecione</option>
                                @foreach ($fornecedores as $fornecedor)
                                <option value="{{ $fornecedor->id }}" {{ old('fornecedor_id') == $fornecedor->id ? 'selected' : '' }}>
                                    {{ $fornecedor->razao_social }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="servico_id">Serviço</label> <span class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-servicos"><i class="mdi mdi-plus"></i></span>
                            <select class="form-control select2" id="servico_id" name="servico_id" required>
                                <option value="">Selecione</option>
                                @foreach ($servicos as $servico)
                                <option value="{{ $servico->id }}" {{ old('servico_id') == $servico->id ? 'selected' : '' }}>
                                    {{ $servico->nomeServico }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">

                   
                        <div class="col-md-2">
                            <label class="form-label" for="quilometragem_atual">km atual do veículo</label>
                            @php
                            $ultima_quilometragem = $maiorValorQuilometragem;
                            @endphp
                            
                            <input class="form-control bg-light" id="quilometragem_atual" title="Campo bloqueado" name="quilometragem_atual" type="number" value="{{ $maiorValorQuilometragem ?? old('quilometragem_nova') }}" readonly>

                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label" for="data_de_execucao">Data de Execução</label>
                            <input class="form-control" id="data_de_execucao" name="data_de_execucao" type="date" value="{{ old('data_de_execucao') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" for="data_de_vencimento">Data de Vencimento</label>
                            <input class="form-control" id="data_de_vencimento" name="data_de_vencimento" type="date" value="{{ old('data_de_vencimento') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" for="situacao">Situação</label>
                            <select class="form-select select2" id="situacao" name="situacao">
                                <option value="">Selecione</option>
                                <option value="1" {{ old('situacao') == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="2" {{ old('situacao') == 'Em Execução' ? 'selected' : '' }}>Em Execução</option>
                                <option value="3" {{ old('situacao') == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                                <option value="4" {{ old('situacao') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="valor_do_servico">Valor do Serviço</label>
                            <div class="d-flex">
                                <span class="pr-2" style="margin-top: 10px; font-size:18px; margin-right: 8px">R$ </span>
                                <input class="form-control" id="valor_do_servico" name="valor_do_servico" type="text" value="{{ old('valor_do_servico') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-8">
                            <label class="form-label" for="descricao">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="6">{{ old('descricao') }}</textarea>
                        </div>

                        
                    </div>

                    <div class="col-md-8 mt-3">
                        <label class="form-label" for="imagens">arquivo</label>
                        <input type="file" id="input-file-now-custom-3" class="form-control" name="arquivo" multiple>
                    </div>

                    <div class="col-12 mt-5">

                        <input name="veiculo_id" type="hidden" value="{{ $veiculo->id }}">
                        <input name="veiculo_tipo" type="hidden" value="{{ $veiculo->tipo }}">
                        <button class="btn btn-primary btn-sm font-weight-medium" type="submit">Salvar</button>

                        <a href="{{ url('admin/ativo/veiculo') }}">
                            <button class="btn btn-warning btn-sm font-weight-medium" type="button">Cancelar</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal-servicos" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Cadastro de Serviços</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                <hr>
            </div>
            <form id="servicos-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nome do Serviço</label>
                        <input class="form-control" id="servicos_modal" name="name" type="text" placeholder="Serviço" required>
                    </div>

                    <div class="modal-footer">
                        <input id="_token_modal" name="newToken" type="hidden" value="{{ csrf_token() }}">
                        <button class="btn btn-secondary btn-sm" data-dismiss="modal" type="button">Cancelar</button>
                        <button class="btn btn-primary btn-sm" type="submit">Cadastrar</button>
                    </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha256-Kg2zTcFO9LXOc7IwcBx1YeUBJmekycsnTsq2RuFHSZU=" crossorigin="anonymous"></script>

<script>

    $(document).ready(function($) {
        $('#valor_do_servico').mask('000.000.000.000.000,00', {
            reverse: true
        });
    });
</script>

@endsection