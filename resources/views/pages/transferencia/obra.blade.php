@extends('dashboard')
@section('title', 'Transferências - Obras')
@section('content')



<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Transferência de Obra
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
        <a href="{{ route('transferencia.obra.store') }}">
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
                            <th>Código da Obra</th>
                            <th>CNPJ</th>
                            <th>Razão Social</th>
                            <th>WhatsApp</th>
                            <th>E-mail</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($obras as $obra)
                        <tr>
                            <td>{{ $obra->id_obra }}</td>
                            <td>{{ $obra->codigo_obra }}</td>
                            <td>{{ $obra->obra_cnpj }}</td>
                            <td>{{ $obra->obra_razaosocial }}</td>
                            <td>{{ $obra->responsavel_telefone }}</td>
                            <td>{{ $obra->responsavel_email }}</td>
                            <td>{{ $obra->data_criacao }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection