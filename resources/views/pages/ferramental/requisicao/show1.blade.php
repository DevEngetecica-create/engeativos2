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
                    <a href="{{ route('ferramental.requisicao.index') }}">
                        <button class="btn btn-sm btn-danger">Todos</button>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="page-header">
        <h3 class="page-title">
            Detalhes da Requisição
        </h3>
    </div>
    @php
        if ($ferramentalRequisicao->status != 1) {
            $action = 'recept';
            $method = 'patch';
        } else {
            $action = 'update';
            $method = 'put';
        }
    @endphp
    <form action="{{ route('ferramental.requisicao.' . $action . '', $ferramentalRequisicao->id) }}" method="post">

        @csrf

        @method('' . $method . '')

        <input name="id_requisicao" type="hidden" value="{{ $ferramentalRequisicao->id }}">
        <input name="id_obra_destino" type="hidden" value="{{ $ferramentalRequisicao->obraDestino->id }}">

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <table class="table-striped table">
                            <tr>
                                <th>ID Requisição</th>
                                <th>Solicitação</th>
                                <th>Status</th>
                                <th style="width: 100px">Ações</th>
                            </tr>
                            <tr>
                                <td>{{ $ferramentalRequisicao->id }}</td>
                                <td>{{ Tratamento::datetimeBR($ferramentalRequisicao->created_at) }}</td>
                                <td><span class="badge badge-{{ $ferramentalRequisicao->situacao->classe }}">{{ $ferramentalRequisicao->situacao->titulo }}</span></td>
                                <td><button class="btn btn-xs btn-{{ $ferramentalRequisicao->status != 1 ? 'danger' : 'success' }}" type="submit" {{ $ferramentalRequisicao->status != 1 ? 'disabled' : '' }} onclick="return confirm('Tem certeza que deseja submeter a Requisição?')">{{ $ferramentalRequisicao->status != 1 ? 'REQUISIÇÃO FINALIZADA' : 'SALVAR REQUISIÇÃO' }}</button></td>
                            </tr>
                        </table>

                        <table class="table-striped mt-5 table">
                            <tr>
                                <th>Despachante</th>
                                <th>Solicitante</th>
                                <th>Destino</th>
                            </tr>
                            <tr>
                                <td>{{ $ferramentalRequisicao->despachante->name ?? null }}</td>
                                <td>{{ $ferramentalRequisicao->solicitante->name }}</td>
                                <td>{{ $ferramentalRequisicao->obraDestino->razao_social }}</td>
                            </tr>
                        </table>

                        <table class="table-striped mt-5 table">
                            <tr>
                                <th>Solicitado</th>
                                <th>Liberado</th>
                                <th>Transferido</th>
                                <th>Recebido</th>
                            </tr>
                            <tr>
                                <td>{{ Tratamento::datetimeBR($ferramentalRequisicao->created_at) }}</td>
                                <td>{{ Tratamento::datetimeBR($ferramentalRequisicao->data_liberacao) ?? null }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>

                        <table class="table-striped mt-5 table">
                            <tr>
                                <th>ID</th>
                                <th>Item</th>
                                <th>Estoque</th>
                                <th>Qtde. Solicitada</th>
                                @if ($ferramentalRequisicao->status == 1)
                                    <th>Liberar</th>
                                    {{-- <th>Qtde. Liberada</th> --}}
                                @endif
                                @if ($ferramentalRequisicao->status > 1)
                                    <th>Qtde. Liberada</th>
                                    <th>Defeito?</th>
                                @endif
                                <th>Situação</th>
                                @if ($ferramentalRequisicao->status > 1)
                                    <th>Opções</th>
                                @endif
                            </tr>

                            @foreach ($itens as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <span class="badge badge-danger">{{ $item->ativo_externo_estoque->patrimonio }}</span>
                                        {{ $item->ativo_externo_estoque->ativo->titulo }}
                                    </td>
                                    <td class="text-center">{{ count($item->ativo_externo_estoque->ativo->estoque_requisicao) }}</td>
                                    <td class="text-center">1</td>
                                    @if ($ferramentalRequisicao->status == 1)
                                        <td>

                                            <input name="id_item[]" type="hidden" value="{{ $item->id }}">
                                            <input name="id_ativo[]" type="hidden" value="{{ $item->ativo_externo_estoque->id }}">
                                            <input name="id_ativo_estoque[]" type="hidden" value="{{ $item->ativo_externo_estoque->id_ativo_externo }}">
                                            <input name="quantidade_solicitada[]" type="hidden" value="{{ $item->quantidade_solicitada }}">

                                            <div class="form-switch">
                                                <input class="form-check-input" name="quantidade_liberada[]" type="checkbox" value="1" role="switch" {{ $ferramentalRequisicao->status != 1 ? 'disabled' : '' }}>
                                            </div>
                                            <input name="quantidade_liberada[]" type="hidden" value="0">
                                        </td>
                                        {{-- <td>{{ $item->quantidade_liberada }}</td> --}}
                                    @endif
                                    @if ($ferramentalRequisicao->status > 1)
                                        <td>{{ $item->quantidade_liberada }}</td>
                                        <td>
                                            <input name="id_item[]" type="hidden" value="{{ $item->id }}">
                                            <input name="id_ativo[]" type="hidden" value="{{ $item->ativo_externo_estoque->id }}">
                                            <input name="id_ativo_estoque[]" type="hidden" value="{{ $item->ativo_externo_estoque->id_ativo_externo }}">
                                            <input name="observacao_recebido[]" type="text" value="" {{ $ferramentalRequisicao->status == 4 ? 'disabled' : '' }}>
                                        </td>
                                    @endif
                                    <td>
                                        <span class="badge badge-{{ $item->situacao->classe }}">{{ $item->situacao->titulo }}</span>
                                    </td>
                                    @if ($ferramentalRequisicao->status > 1)
                                        <td>
                                            <select name="status_recebido[]" {{ $ferramentalRequisicao->status == 4 ? 'disabled' : '' }}>
                                                <option value="6">Recebido</option>
                                                <option value="7">Recebido com defeito</option>
                                            </select><br>
                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                        </table>
                        <div class="text-right">
                            @if ($ferramentalRequisicao->status > 1 and $ferramentalRequisicao->status < 4)
                                <button class="btn btn-xs btn-{{ $ferramentalRequisicao->status != 1 ? 'success' : 'danger' }}" type="submit" {{ $ferramentalRequisicao->status != 1 ? '' : 'disabled' }} onclick="return confirm('Tem certeza que deseja submeter os Recebimentos?')">{{ $ferramentalRequisicao->status != 1 ? 'SALVAR RECEBIMENTO' : 'RECEBIMENTO RESOLVIDO' }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
