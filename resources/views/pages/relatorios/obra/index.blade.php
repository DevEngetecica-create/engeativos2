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

                    <form method="post" action="{{ route('relatorio.obra.gerar') }}">
                        @csrf
                        <div>
                            <h1>Gerar Relatório de Obras</h1>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="empresa" class="form-label">Empresa</label>
                                <select name="empresa" id="empresa" class="form-select">
                                    <option value="" selected>Todos</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id }}">{{ $empresa->razao_social }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="periodo" class="form-label">Periodo</label>
                                <select name="periodo" id="periodo" class="form-select">
                                    <option value="" selected>Todos</option>
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
                                Gerar
                            </button>
                        </div>
                    </form>

                </div>
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
