@extends('dashboard')
@section('title', 'Retirada de Ferramentas')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Ampliar prazo da Retirada #{{ $itens->id }}
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Ferramental <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
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

                <form method="post" action="{{ route('ferramental.retirada.ampliar.store') }}" id="submit-prazo">
                    @csrf

                    @if (Auth::user()->user_level == 1)
                    <div class="row">
                        <div class="col-12">
                            @include('components.fields.id_obra')
                        </div>
                    </div>
                    @endif

                    @if (Auth::user()->user_level >= 2)
                    <input id="id_obra" name="id_obra" type="hidden" value="{{ session('obra')->id_obra }}">
                    @endif

                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label" for="{{ $field ?? 'id_obra' }}">{{ $title ?? 'Obra' }}</label>
                            <select class="form-select select2" disabled>
                                <option value="">Selecione uma Obra</option>
                                @foreach ($obras as $obra)
                                <option value="{{ $obra->id }}" {{ $itens->id_obra == $obra->id ? 'selected' : '' }}>
                                    {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                </option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-6">
                            <label class="form-label" for="id_funcionario">Funcionário</label>
                            <select class="form-select select2" disabled>
                                <option value="">Selecione um Funcionário</option>
                                @foreach ($funcionarios as $funcionario)
                                <option value="{{ $funcionario->id }}" {{ $itens->id_funcionario == $funcionario->id ? 'selected' : '' }}>
                                    {{ $funcionario->matricula }} - {{ $funcionario->nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-2">
                            <label class="form-label" for="data_solicitacao">Data de Solicitação</label>
                            <input class="form-control" name="data_solicitacao" type="datetime-local" value="{{ $itens->created_at }}" disabled>
                        </div>

                        <div class="col-2">
                            <label class="form-label" for="devolucao_prevista_atual">Devolução Atual</label>
                            <input class="form-control" id="devolucao_prevista_atual" name="devolucao_prevista_atual" type="datetime-local" readonly value="{{ $itens->data_devolucao_prevista }}">
                        </div>
                        <div class="col-2">
                            <label class="form-label" for="devolucao_prevista">Devolução Prevista</label>
                            <input class="form-control" id="devolucao_prevista" name="devolucao_prevista" type="datetime-local" value="{{ $itens->data_devolucao_prevista }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-3">
                            <label class="form-label" for="observacoes">Observações</label><br>
                            <span class="muted text-primary">Caso considere importante, amplie as observações descrevendo o aumento do prazo.</span>
                            <textarea class="form-control" name="observacoes" rows="3">{{ $itens->observacoes }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-3">
                            <table class="table-striped table-bordered table-hover table">
                                <thead>
                                    <tr class="">
                                        <th width="30%">Estoque na Obra</th>
                                        <th>Item</th>
                                        <th width="10%">Qual Item deseja renovar?</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($itens->itens as $item)
                                    <tr>
                                        <td><span class="badge badge-danger">{{ $itens->codigo_obra . ' - ' . $itens->razao_social }}</span>
                                        </td>
                                        <td><span class="badge badge-primary">{{ $item->item_codigo_patrimonio }}</span> {{ $item->item_nome }}</td>
                                        <td>
                                            <div class="form-switch">
                                                <input class="form-check-input" id="id_ativo_exerno" name="id_ativo_externo[]" type="checkbox" value="{{ $item->id_ativo_externo }}" role="switch">
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <input name="id_obra" type="hidden" value="{{ $itens->id_obra }}">
                    <input name="id_funcionario" type="hidden" value="{{ $itens->id_funcionario }}">
                    <input name="data_solicitacao" type="hidden" value="{{ $itens->created_at }}">
                    <input name="id_relacionamento" type="hidden" value="{{ $itens->id }}">

                    <button class="btn btn-gradient-primary font-weight-medium" type="submit">Salvar</button>

                    <a href="{{ route('ferramental.retirada') }}">
                        <button class="btn btn-gradient-danger font-weight-medium" type="button">Cancelar</button>
                    </a>

                </form>
            </div>
        </div>
    </div>
</div>


@endsection