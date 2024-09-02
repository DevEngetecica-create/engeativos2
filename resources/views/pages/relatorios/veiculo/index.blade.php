@extends('dashboard')
@section('title', 'Veículo')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Relatórios
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

                    <form method="post" action="{{ route('relatorio.veiculo.gerar') }}">
                        @csrf
                        <div>
                            <h1>Gerar Relatório de Veículos</h1>
                        </div>

                        <div class="row  mt-3">
                            <div class="col-md-4">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select name="tipo" id="tipo" class="form-select" required>
                                    @php
                                        $tiposVeiculos = [
                                            'motos' => 'Motos',
                                            'caminhoes' => 'Caminhões',
                                            'carros' => 'Carros',
                                            'maquinas' => 'Máquinas',
                                        ];
                                    @endphp
                                    @isset($tipo_veiculo)
                                        <option value="{{ $tipo_veiculo }}" selected>
                                            {{ $tiposVeiculos[$tipo_veiculo] }}
                                        </option>
                                    @endisset
                                    <option value="">Selecione</option>
                                    <option value="motos">Motos</option>
                                    <option value="carros">Carros</option>
                                    <option value="caminhoes">Caminhões</option>
                                    <option value="maquinas">Máquinas</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="periodo" class="form-label">Periodo</label>
                                <select name="periodo" id="periodo" class="form-select">
                                    @php
                                        $tiposPeriodos = [
                                            'hoje' => 'Hoje',
                                            'ontem' => 'Ontem',
                                            '7dias' => 'Últimos 7 dias',
                                            '30dias' => 'Últimos 30 dias',
                                            '60dias' => 'Últimos 60 dias',
                                            '90dias' => 'Últimos 90 dias',
                                            '180dias' => 'Últimos 6 meses',
                                            '365dias' => 'Último ano',
                                            '730dias' => 'Últimos 2 anos',
                                            'outro' => 'Outro',
                                        ];
                                    @endphp
                                    @isset($periodo)
                                        <option value="{{ $periodo }}" selected>
                                            {{ $tiposPeriodos[$periodo] }}
                                        </option>
                                    @endisset
                                    <option value="">Todos</option>
                                    <option value="hoje">Hoje</option>
                                    <option value="ontem">Ontem</option>
                                    <option value="7dias">Últimos 7 dias</option>
                                    <option value="30dias">Últimos 30 dias</option>
                                    <option value="60dias">Últimos 60 dias</option>
                                    <option value="90dias">Últimos 90 dias</option>
                                    <option value="180dias">Últimos 6 meses</option>
                                    <option value="365dias">Último ano</option>
                                    <option value="730dias">Últimos 2 anos</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </div>
                            <div class="row col-md-6" id="outro-periodo" style="display: none;">
                                <div class="col-md-6">
                                    <label for="inicio" class="form-label">Início</label>
                                    <input type="date" class="form-control" id="inicio" name="inicio">
                                </div>
                                <div class="col-md-6">
                                    <label for="fim" class="form-label">Fim</label>
                                    <input type="date" class="form-control" id="fim" name="fim">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-gradient-primary btn-lg font-weight-medium">
                                Gerar
                            </button>
                        </div>
                    </form>
                </div>
                @isset($veiculos)
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="">
                                <div class="card-body">
                                    <table class="table table-hover table-striped" id="lista-simples">
                                        <thead>
                                            <tr>
                                                <th width="8%">ID</th>
                                                <th>TIPO</th>
                                                <th>Marca</th>
                                                <th>Veículo</th>
                                                <th>Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($veiculos as $veiculo)
                                                <tr>
                                                    <td><span class="badge badge-dark">{{ $veiculo->id }}</span></td>

                                                    @php
                                                        $tiposVeiculos = [
                                                            'motos' => 'Moto',
                                                            'caminhoes' => 'Caminhão',
                                                            'carros' => 'Carro',
                                                            'maquinas' => 'Máquina',
                                                        ];
                                                    @endphp

                                                    <td>{{ @$tiposVeiculos[$veiculo->tipo] }}</td>
                                                    <td>
                                                        @isset($veiculo->marca)
                                                            {{ $veiculo->marca }}
                                                        @endisset
                                                    </td>
                                                    <td>
                                                        @isset($veiculo->veiculo)
                                                            {{ $veiculo->veiculo }}
                                                        @endisset
                                                    </td>
                                                    <td>
                                                        @isset($veiculo->created_at)
                                                            {{ $veiculo->created_at }}
                                                        @endisset
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="post" action="{{ route('relatorio.veiculo.gerar') }}">
                        @csrf
                        <input type="hidden" name="tipo" value="{{ $tipo_veiculo }}">
                        <input type="hidden" name="periodo" value="{{ $periodo }}">
                        <div class="card-body">
                            <div class="row  mt-3">
                                <div class="col-md-4">
                                    <label for="tipo_arquivo" class="form-label">Tipo de Arquivo</label>
                                    <select name="tipo_arquivo" id="tipo_arquivo" class="form-select" required>
                                        <option value="" selected>Selecione</option>
                                        <option value="pdf">PDF</option>
                                        <option value="xls">XLS (Excel)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-gradient-primary btn-lg font-weight-medium">
                                    Baixar
                                </button>
                            </div>
                        </div>
                    </form>
                @endisset
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.querySelector('#periodo');
        const outroPeriodoDiv = document.querySelector('#outro-periodo');

        select.addEventListener('change', () => {
            if (select.value === 'outro') {
                outroPeriodoDiv.style.display = 'flex';
            } else {
                outroPeriodoDiv.style.display = 'none';
            }
        });
    });
</script>
