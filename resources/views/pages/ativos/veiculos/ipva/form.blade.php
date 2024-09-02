@extends('dashboard')
@section('title', 'Veículo')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> IPVA do veículo
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a class="btn btn-success" href="{{ route('ativo.veiculo.ipva.index', $store->veiculo_id) }}">
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

                    <form method="post" enctype="multipart/form-data" action="{{ $btn == 'add' ? route('ativo.veiculo.ipva.store', $store->veiculo_id) : route('ativo.veiculo.ipva.update', $store->id) }}">
                        @csrf

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="referencia_ano">Ano de Referência</label>
                                <input class="form-control" id="referencia_ano" name="referencia_ano" type="number" value="{{ $btn == 'add' ? '' : @$store->referencia_ano }}" step="any">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="valor">Valor</label>
                                <input class="form-control" id="valor" name="valor" type="text" value="{{ $btn == 'add' ? '' : @$store->valor }}" step="any">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="data_de_vencimento">Data de Vencimento</label>
                                <input class="form-control" id="data_de_vencimento" name="data_de_vencimento" type="date" value="{{ $btn == 'add' ? '' : @$store->data_de_vencimento }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="data_de_pagamento">Data de Pagamento</label>
                                <input class="form-control" id="data_de_pagamento" name="data_de_pagamento" type="date" value="{{ $btn == 'add' ? '' : @$store->data_de_pagamento }}">
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <button class="btn btn-primary btn-sm font-weight-medium" type="submit">Salvar</button>

                            <a href="{{url('admin/ativo/veiculo') }}">
                                <button class="btn btn-danger btn-sm font-weight-medium" type="button">Cancelar</button>
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
