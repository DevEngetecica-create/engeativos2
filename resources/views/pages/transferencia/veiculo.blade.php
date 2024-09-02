@extends('dashboard')
@section('title', 'Transferências - Veículos')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Transferência de Veículos
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Gestão de Transferências de Banco de Dados <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h3 class="page-title">
        <a href="{{ route('transferencia.veiculo.store') }}">
            <button class="btn btn-sm btn-danger" type="button">Processar Migração</button>
        </a>
    </h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <table class="table-hover table-striped table" id="lista-simples">
                    <thead>
                        <tr>
                            <th width="8%">ID</th>
                            <th>Placa/ID</th>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>KM</th>
                            <th>Hora</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($veiculos as $veiculo)
                        <tr>
                            <td>{{ $veiculo->id_ativo_veiculo }}</td>
                            <td>{{ $veiculo->veiculo_placa ?? $veiculo->id_interno_maquina }}</td>
                            <td>{{ $veiculo->tipo_veiculo }}</td>
                            <td>{{ $veiculo->marca }}</td>
                            <td>{{ $veiculo->modelo }}</td>
                            <td>{{ $veiculo->veiculo_km }}</td>
                            <td>{{ $veiculo->veiculo_horimetro }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection