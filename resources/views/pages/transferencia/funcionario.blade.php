@extends('dashboard')
@section('title', 'Transferências - Funcionários')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Transferência de Funcionários
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
        <a href="{{ route('transferencia.funcionario.store') }}">
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
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Razão Social</th>
                            <th>WhatsApp</th>
                            <th>E-mail</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($funcionarios as $fornecedor)
                        <tr>
                            <td>{{ $fornecedor->id_funcionario }}</td>
                            <td>{{ $fornecedor->nome ?? '-' }}</td>
                            <td>{{ $fornecedor->cpf }}</td>
                            <td>{{ $fornecedor->razao_social ?? '-' }}</td>
                            <td>{{ $fornecedor->telefone }}</td>
                            <td>{{ $fornecedor->email }}</td>
                            <td>{{ $fornecedor->data_criacao }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection