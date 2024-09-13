@extends('dashboard')
@section('title', 'Veículo')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Depreciação do veículo
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a class="btn btn-success" href="{{ route('ativo.veiculo.depreciacao.index', $store->veiculo_id) }}">
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

                    <form method="post" enctype="multipart/form-data" action="{{ $btn == 'add' ? route('ativo.veiculo.depreciacao.store', $store->veiculo_id) : route('ativo.veiculo.depreciacao.update', $store->id) }}">
                        @csrf

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="valor_atual">Valor Atual</label>
                                <input class="form-control" id="valor_atual" name="valor_atual" type="text" value="{{ $btn == 'add' ? '' : @$store->valor_atual }}" placeholder="R$ 0,00">

                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-2">
                                <label class="form-label" for="referencia_mes">Mês de referência</label>
                                <select class="form-control form-select" id="referencia_mes" name="referencia_mes">
                                    @php
                                        $meses = ['janeiro', 'fevereiro', 'marco', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
                                    @endphp

                                    <option value="">Selecione</option>

                                    @foreach ($meses as $mes)
                                        <option value="{{ $mes }}" {{ ($btn == 'add' ? '' : $store->referencia_mes == $mes) ? 'selected' : '' }}>
                                            {{ ucfirst($mes) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-2">
                                <label class="form-label" for="referencia_ano">Ano de referência</label>
                                <input class="form-control" id="referencia_ano" name="referencia_ano" type="number" value="{{ $btn == 'add' ? '' : @$store->referencia_ano }}">
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <button class="btn btn-gradient-primary btn-lg font-weight-medium" type="submit">Salvar</button>

                            <a href="{{url('admin/ativo/veiculo') }}">
                                <button class="btn btn-gradient-danger btn-lg font-weight-medium" type="button">Cancelar</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#valor_atual').mask('000.000.000.000.000,00', {
            reverse: true
        });
    });
</script> --}}
{{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
    integrity="sha256-Kg2zTcFO9LXOc7IwcBx1YeUBJmekycsnTsq2RuFHSZU=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function($) {
        $('#valor_atual').mask('000.000.000.000.000,00', {
            reverse: true
        });
    });
</script> --}}
