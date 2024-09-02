@extends('dashboard')
@section('title', 'Veículo')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span>
            @if ($depreciacao->veiculo->tipo == 'maquinas')
                Depreciação da Máquina
            @else
                Depreciação do Veículo
            @endif
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
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

                    <form method="post" action="{{ route('ativo.veiculo.depreciacao.update', $depreciacao->id) }}">
                        @csrf
                        @method('put')
                        <div class="jumbotron p-3">
                            <span class="font-weight-bold">{{ $depreciacao->veiculo->marca }} | {{ $depreciacao->veiculo->modelo }} | {{ $depreciacao->veiculo->veiculo }}</span>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="valor_atual">Valor Atual</label>
                                <input class="form-control" id="valor_atual" name="valor_atual" type="text" value="{{ $depreciacao->valor_atual }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label" for="referencia_mes">Mês de referência</label>
                                <select class="form-control form-select" id="referencia_mes" name="referencia_mes">
                                    @php
                                        $meses = ['janeiro', 'fevereiro', 'marco', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
                                    @endphp

                                    <option value="">Selecione</option>

                                    @foreach ($meses as $mes)
                                        <option value="{{ $mes }}" {{ $depreciacao->referencia_mes == $mes ? 'selected' : '' }}>
                                            {{ ucfirst($mes) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label" for="referencia_ano">Ano de referência</label>
                                <input class="form-control" id="referencia_ano" name="referencia_ano" type="number" value="{{ $depreciacao->referencia_ano }}">
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <input name="veiculo_id" type="hidden" value="{{ $depreciacao->veiculo_id }}">
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
