@extends('dashboard')
@section('title', 'Requisições')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Ferramental
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Requisições <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h3 class="page-title">
        <a href="{{ route('ferramental.requisicao.create') }}">
            <button class="btn btn-sm btn-danger">Nova Requisição</button>
        </a>
    </h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <table class="table-hover table-striped yajra-datatable table">
                    <thead>
                        <tr>
                            <th width="8%">ID</th>
                            <th> Solicitante </th>
                            <th> Origem </th>
                            <th> Destino </th>
                            <th> Data de Solicitação </th>
                            <th> Data de Liberação </th>
                            <th> Status</th>
                            <th width="10%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($requisicoes as $requisicao)
                        <tr>
                            <td class="text-center align-middle">{{ $requisicao->id }}</td>
                            <td class="align-middle">{{ $requisicao->solicitante->name }}</td>
                            <td class="align-middle">{{ $requisicao->obraOrigem->codigo_obra }}</td>
                            <td class="align-middle">{{ $requisicao->obraDestino->codigo_obra }}</td>
                            <td class="align-middle">{{ Tratamento::datetimeBR($requisicao->created_at) }}</td>
                            <td class="align-middle">{{ Tratamento::datetimeBR($requisicao->data_liberacao) }}</td>
                            <td class="align-middle"><span class="btn btn-sm btn-{{ $requisicao->situacao->classe }}">{{ $requisicao->situacao->titulo }}</span></td>
                            <td class="d-flex gap-2 align-middle">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-danger" id="dropdownMenuButton1" data-bs-toggle="dropdown" type="button" aria-expanded="false">
                                        Selecione <i class="mdi mdi-menu-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        @if ($requisicao->status > 1 and $requisicao->status < 4) <li><a class="dropdown-item" href="{{ route('ferramental.requisicao.show', $requisicao->id) }}"><i class="mdi mdi-file-pdf-box"></i> Gerar Romaneio</a></li>
                                            @endif
                                            <li><a class="dropdown-item" href="{{ route('ferramental.requisicao.show', $requisicao->id) }}"><i class="mdi mdi-file-document-outline"></i> Detalhes</a></li>

                                    </ul>
                                </div>
                                {{--
                                        <form action="{{ route('ativo.interno.destroy', $requisicao->id) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button class="badge badge-danger" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir">
                                    <i class="mdi mdi-delete"></i> Excluir
                                </button>
                                </form> --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection