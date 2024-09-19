@extends('dashboard')
@section('title', 'Veículo')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Manutenção do Veículo
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a class="btn btn-success" href="{{ route('ativo.veiculo.manutencao.index', $store->veiculo_id) }}">
                        <i class="mdi mdi-arrow-left icon-sm align-middle text-white"></i> Voltar
                    </a>
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

                    <form method="post" enctype="multipart/form-data" action="{{ $btn == 'add' ? route('ativo.veiculo.manutencao.store', $store->veiculo_id) : route('ativo.veiculo.manutencao.update', $store->id) }}">
                        @csrf

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="tipo">Tipo </label>
                                <select class="form-select" id="tipo" name="tipo">
                                    <option value="">Selecione</option>
                                    <option value="corretiva" {{ ($btn == 'add' ? '' : $store->tipo == 'corretiva') ? 'selected' : '' }}>Corretiva</option>
                                    <option value="preventiva" {{ ($btn == 'add' ? '' : $store->tipo == 'preventiva') ? 'selected' : '' }}>Preventiva</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="fornecedor">Fornecedor</label>
                                <select class="form-select" id="fornecedor" name="fornecedor">
                                    <option value="" selected>Selecione</option>
                                    @foreach ($fornecedores as $fornecedor)
                                        <option value="{{ $fornecedor->id }}" {{ ($btn == 'add' ? '' : $store->fornecedor->id == $fornecedor->id) ? 'selected' : '' }}>
                                            {{ $fornecedor->razao_social }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="servico">Serviço</label>
                                <select class="form-select" id="servico" name="servico">
                                    <option value="">Selecione</option>
                                    @foreach ($servicos as $servico)
                                        <option value="{{ $servico->id }}" {{ ($btn == 'add' ? '' : $store->servico->id == $servico->id) ? 'selected' : '' }}>
                                            {{ $servico->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if (@$store->tipo == 'maquina')
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="horimetro_atual">Horímetro Atual</label>
                                    <input class="form-control" id="horimetro_atual" name="horimetro_atual" type="time" value="{{ $btn == 'add' ? '' : @$store->horimetro_atual }}" step="60">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="horimetro_proximo">Horímetro Próximo</label>
                                    <input class="form-control" id="horimetro_proximo" name="horimetro_proximo" type="time" value="{{ $btn == 'add' ? '' : @$store->horimetro_proximo }}" step="60">
                                </div>
                            </div>
                        @else
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="quilometragem_atual">Quilometragem Atual</label>
                                    <input class="form-control" id="quilometragem_atual" name="quilometragem_atual" type="number" value="{{ $btn == 'add' ? '' : @$store->quilometragem_atual }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="quilometragem_proxima">Quilometragem Nova</label>
                                    <input class="form-control" id="quilometragem_proxima" name="quilometragem_proxima" type="number" value="{{ $btn == 'add' ? '' : @$store->quilometragem_proxima }}">
                                </div>
                            </div>
                        @endif

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="data_de_execucao">Data de Execução</label>
                                <input class="form-control" id="data_de_execucao" name="data_de_execucao" type="date" value="{{ $btn == 'add' ? '' : $store->data_de_execucao }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="data_de_vencimento">Data de Vencimento</label>
                                <input class="form-control" id="data_de_vencimento" name="data_de_vencimento" type="date" value="{{ $btn == 'add' ? '' : $store->data_de_vencimento }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-8">
                                <label class="form-label" for="descricao">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" cols="30" rows="6">{{ $btn == 'add' ? '' : $store->descricao }}</textarea>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="valor_do_servico">Valor do Serviço</label>
                                <input class="form-control" id="valor_do_servico" name="valor_do_servico" type="text" value="{{ $btn == 'add' ? '' : $store->valor_do_servico }}" step="any">
                            </div>
                        </div>

                        <div class="col-12 mt-5">
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
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var valorDoLitroInput = document.getElementById('valor_do_litro');
        var quantidadeInput = document.getElementById('quantidade');
        var valorTotalInput = document.getElementById('valor_total');

        valorDoLitroInput.addEventListener('change', updateValorTotal);
        quantidadeInput.addEventListener('change', updateValorTotal);

        function updateValorTotal() {
            var valorDoLitro = parseFloat(valorDoLitroInput.value);
            var quantidade = parseFloat(quantidadeInput.value);

            var valorTotal = valorDoLitro * quantidade;

            valorTotalInput.value = valorTotal.toFixed(2);
        }
    });
</script>
