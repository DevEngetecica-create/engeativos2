@extends('dashboard')
@section('title', 'Veículo')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Seguro do Veículo
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a class="btn btn-success" href="{{ route('ativo.veiculo.seguro.index', $store->veiculo_id) }}">
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

                    <form method="post" enctype="multipart/form-data" action="{{ $btn == 'add' ? route('ativo.veiculo.seguro.store', $store->veiculo_id) : route('ativo.veiculo.seguro.update', $store->id) }}">
                        @csrf

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="carencia_inicial">Carência Inicial</label>
                                <input class="form-control" id="carencia_inicial" name="carencia_inicial" type="date" value="{{ $btn == 'add' ? '' : @$store->carencia_inicial }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="carencia_final">Carência Final</label>
                                <input class="form-control" id="carencia_final" name="carencia_final" type="date" value="{{ $btn == 'add' ? '' : @$store->carencia_final }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="valor">Valor</label>
                                <input class="form-control" id="valor" name="valor" type="text" value="{{ $btn == 'add' ? '' : @$store->valor }}" step="any">
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
