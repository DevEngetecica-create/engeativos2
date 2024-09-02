@extends('dashboard')
@section('title', 'Transferências - Ativos')
@section('content')



<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Transferência dos Ativos
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
        <a href="{{ route('transferencia.ativo.store') }}">
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
                            <th>Patrimônio</th>
                            <th>Item</th>
                            <th>Situação</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($ativos as $ativo)
                        <tr>
                            <td>{{ $ativo->id_ativo_externo }}</td>
                            <td>{{ $ativo->codigo }}</td>
                            <td>{{ $ativo->nome }}</td>
                            <td>{{ $ativo->situacao }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection