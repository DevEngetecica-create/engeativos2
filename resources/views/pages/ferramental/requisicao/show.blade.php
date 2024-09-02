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
        if ($ferramentalRequisicao->status == 1) {
            $action = 'update';
            $method = 'put';
        } elseif ($ferramentalRequisicao->status == 2 || $ferramentalRequisicao->status == 3) {
            $action = 'romaneio';
            $method = 'post';
        } elseif ($ferramentalRequisicao->status == 5) {
            $action = 'recept';
            $method = 'patch';
        } else {
            $action = 'update';
            $method = 'put';
        }
        
    @endphp
    <form action="{{ route('ferramental.requisicao.' . $action . '', $ferramentalRequisicao->id) }}" method="post" {{ $ferramentalRequisicao->status > 1 && $ferramentalRequisicao->status < 4 ? 'target=_blank' : '' }}>

        @csrf

        @method('' . $method . '')

        <input name="id_requisicao" type="hidden" value="{{ $ferramentalRequisicao->id }}">
        <input name="id_obra_destino" type="hidden" value="{{ $ferramentalRequisicao->obraDestino->id }}">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <table class="table-striped table-bordered table">
                            <tr>
                                <th width="15%">ID Requisição</th>
                                <th>Solicitação</th>
                                <th>Liberação</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                            <tr>
                                <td>{{ $ferramentalRequisicao->id }}</td>
                                <td>{{ Tratamento::datetimeBR($ferramentalRequisicao->created_at) }}</td>
                                <td>{{ Tratamento::datetimeBR($ferramentalRequisicao->data_liberacao) }}</td>
                                <td><span class="btn btn-sm btn-{{ $ferramentalRequisicao->situacao->classe }}">{{ $ferramentalRequisicao->situacao->titulo }}</span></td>
                                <td><button class="btn btn-sm btn-{{ $ferramentalRequisicao->status != 1 ? 'success' : 'danger' }}" type="submit" {{ $ferramentalRequisicao->status != 1 ? 'disabled' : '' }} onclick="return confirm('Tem certeza que deseja submeter a Requisição?')">{{ $ferramentalRequisicao->status != 1 ? 'REQUISIÇÃO LIBERADA' : 'SALVAR REQUISIÇÃO' }}</button></td>
                            </tr>
                        </table>

                        <table class="table-striped table-bordered mt-5 table">
                            <tr>
                                <th width="15%">Solicitante</th>
                                <th>Despachante</th>
                                <th>Obra de Origem</th>
                                <th>Obra de destino</th>
                            </tr>
                            <tr>
                                <td>{{ $ferramentalRequisicao->solicitante->name }}</td>
                                <td>{{ $ferramentalRequisicao->despachante->name ?? null }}</td>
                                <td>{{$ferramentalRequisicao->obraOrigem->nome_fantasia}}</td>
                                <td>{{ $ferramentalRequisicao->obraDestino->razao_social }}</td>
                            </tr>
                        </table>

                        <table class="table-striped table-bordered mt-5 table">
                            <tr>
                                <th width="15%">Solicitado</th>
                                <th width="25%">Liberado</th>
                                <th width="15%">Transferido</th>
                                <th>Recebido</th>
                            </tr>
                            <tr>
                                <td>{{ Tratamento::datetimeBR($ferramentalRequisicao->created_at) }}</td>
                                <td>{{ Tratamento::datetimeBR($ferramentalRequisicao->data_liberacao) ?? null }}</td>
                                <td>{{ Tratamento::datetimeBR($ferramentalRequisicao->update_at) ?? null }}</td>
                                <td>{{ Tratamento::datetimeBR($ferramentalRequisicao->data_recebimento) ?? null }}</td>
                            </tr>
                        </table>
                        @if ($ferramentalRequisicao->status == 1)
                            <table class="table-striped table-bordered mt-5 table">
                                <tr>
                                    <th width="15%">Item</th>
                                    <th width="25%">Estoque</th>
                                    <th>Qtde. Solicitada</th>
                                    @if ($ferramentalRequisicao->status > 1)
                                        <th>Qtde. Liberada</th>
                                        <th>Defeito?</th>
                                    @endif
                                    <th style="width:10.7%">Situação</th>
                                    @if ($ferramentalRequisicao->status > 1)
                                        <th>Opções</th>
                                    @endif
                                </tr>

                                @foreach ($itens as $item)
                                    <tr>
                                        <td class="text-uppercase font-weight-bold">
                                            {{ $item->ativo_externo->titulo }}
                                        </td>
                                        <td class="text-uppercase font-weight-bold text-center">{{ count($item->ativo_externo->estoque) }}</td>
                                        <td class="text-uppercase font-weight-bold text-center">{{ $item->quantidade_solicitada }}</td>
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
                                    <tr>
                                        <td class="text-center" {{ $ferramentalRequisicao->status == 1 ? 'colspan=5' : 'colspan=7' }}>
                                            {{-- ESTADO PENDENTE --}}
                                            @if ($ferramentalRequisicao->status == 1)
                                                @foreach ($item->ativo_externo->estoque->load('obra')->groupBy('id_obra') as $estoque => $estoqueAgrupado)
                                                    <table class="table">
                                                        <tr>
                                                            <td class="font-weight-bold text-uppercase text-left" colspan="2">

                                                                @php
                                                                    $primeiro = $estoqueAgrupado->first();
                                                                @endphp
                                                                <span class="badge badge-danger">{{ $primeiro->obra->codigo_obra }}</span>

                                                            </td>
                                                        </tr>
                                                        @foreach ($estoqueAgrupado as $agrupado)
                                                            <tr>
                                                                <td class="text-left"><span class="badge badge-secondary"> {{ $agrupado->patrimonio }} </span> {{ $agrupado->ativo_externo->titulo }}</td>

                                                                <td class="pr-5 text-right" style="width:10%">
                                                                    <div class="form-switch">
                                                                        <input class="form-check-input" name="id_item[]" type="checkbox" value="[{{ $item->id }}, {{ $agrupado->id }}]" role="switch" {{ $ferramentalRequisicao->status != 1 ? 'disabled' : '' }}>
                                                                    </div>
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @endforeach
                                                <input name="id_item_requisicao[]" type="hidden" value="{{ $item->id }}">
                                                <input name="id_ativo[]" type="hidden" value="{{ $item->ativo_externo->id }}">
                                                <input name="quantidade_solicitada[]" type="hidden" value="{{ $item->quantidade_solicitada }}">
                                            @endif

                                            {{-- ESTADO LIBERADO / LIBERADO PARCIALMENTE --}}

                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                        @if ($ferramentalRequisicao->status > 1)
                            <table class="table-striped table">
                                <tr>
                                    <th>Origem</th>
                                    <th>Destino</th>
                                    <th>Ativo</th>
                                    @if ($ferramentalRequisicao->status == 2 || $ferramentalRequisicao->status == 3)
                                    @else
                                        <th>Observações</th>
                                        <th>Ações</th>
                                    @endif
                                </tr>
                                @foreach ($transferencias as $transferencia)
                                    <tr>
                                        <td>
                                            <span class="btn btn-sm btn-danger">{{ $transferencia->obraOrigem->codigo_obra }}</span>
                                        </td>
                                        <td>
                                            <span class="btn btn-sm btn-warning">{{ $transferencia->obraDestino->codigo_obra }}</span>
                                        </td>
                                        <td {{ $ferramentalRequisicao->status == 2 || $ferramentalRequisicao->status == 3 ? 'colspan=3' : '' }}>
                                            @php
                                                $item = $transferencia->ativo->load('ativo_externo');
                                            @endphp
                                            <span class="badge badge-secondary">{{ $transferencia->ativo->patrimonio }}</span> {{ $item->ativo_externo->titulo }}
                                        </td>
                                        @if ($ferramentalRequisicao->status == 2 || $ferramentalRequisicao->status == 3)
                                        @else
                                            <td>
                                                <input name="id_estoque[]" type="hidden" value="{{ $transferencia->ativo->id_ativo_externo ?? old('id_estoque') }}">
                                                <input name="id_ativo[]" type="hidden" value="{{ $transferencia->ativo->id ?? old('id_ativo') }}">
                                                <input name="id_item_transferencia[]" type="hidden" value="{{ $transferencia->id ?? old('id_item_transferencia') }}">
                                                <input name="observacao_recebimento[]" type="text" value="{{ $transferencia->observacao_recebimento ?? old('observacao_recebimento') }}" {{ $transferencia->status >= 6 ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <select class="form-select" name="status_recebimento[]" {{ $transferencia->status >= 6 ? 'disabled' : '' }}>
                                                    <option value="6" {{ $transferencia->status == 6 ? 'selected' : '' }}>Recebido</option>
                                                    <option value="7" {{ $transferencia->status == 7 ? 'selected' : '' }}>Recebido com Defeito</option>
                                                </select>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                        <input name="id_obra_destino" type="hidden" value="{{ $ferramentalRequisicao->obraDestino->id }}">
                        <div class="text-right">
                            @if ($ferramentalRequisicao->status > 1 && $ferramentalRequisicao->status < 4)
                                <button class="btn btn-xs btn-warning text-black" type="submit" onclick="return confirm('Tem certeza que deseja gerar o romaneio?')">GERAR ROMANEIO</button>
                            @elseif ($ferramentalRequisicao->status == 5)
                                <button class="btn btn-xs btn-{{ $ferramentalRequisicao->status != 1 ? 'success' : 'danger' }}" type="submit" {{ $ferramentalRequisicao->status != 1 ? '' : 'disabled' }} onclick="return confirm('Tem certeza que deseja submeter os Recebimentos?')">{{ $ferramentalRequisicao->status != 1 ? 'SALVAR RECEBIMENTO' : 'RECEBIMENTO RESOLVIDO' }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
