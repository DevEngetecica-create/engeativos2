@extends('dashboard')
@section('title', 'Transferências - Configurações dos Ativos')
@section('content')



<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Transferência de Configurações dos Ativos
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
        <a href="{{ route('transferencia.ativo_configuracao.store') }}">
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
                            <th>Vínculo</th>
                            <th>Título</th>
                            <th>Slug</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($configuracoes as $configuracao)
                        <tr>
                            <td>{{ $configuracao->id_ativo_configuracao }}</td>
                            <td>{{ $configuracao->id_ativo_configuracao_vinculo }}</td>
                            <td>{{ $configuracao->titulo }}</td>
                            <td>{{ $configuracao->slug }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection