@extends('dashboard')
@section('title', 'Veículo')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span>
            @if ($manutencao->veiculo->tipo == 'maquinas')
                Manutenção da Máquina
            @else
                Manutenção do Veículo
            @endif
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    Ativos
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

                    <form method="post" action="{{ route('ativo.veiculo.manutencao.update', $manutencao->id) }}">
                        @csrf
                        @method('put')
                        <div class="jumbotron p-3">
                            <span class="font-weight-bold">{{ $manutencao->veiculo->marca }} | {{ $manutencao->veiculo->modelo }} | {{ $manutencao->veiculo->veiculo }}</span>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="fornecedor_id">Fornecedor</label>
                                <select class="form-select select2" id="fornecedor_id" name="fornecedor_id" required>
                                    <option value="">Selecione</option>
                                    @foreach ($fornecedores as $fornecedor)
                                        <option value="{{ $fornecedor->id }}" {{ $manutencao->fornecedor_id == $fornecedor->id ? 'selected' : '' }}>
                                            {{ $fornecedor->razao_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="servico_id">Serviço</label>
                                <select class="form-control select2" id="servico_id" name="servico_id" required>
                                    <option value="">Selecione</option>
                                    @foreach ($servicos as $servico)
                                        <option value="{{ $servico->id }}" {{ $manutencao->servico_id == $servico->id ? 'selected' : '' }}>
                                            {{ $servico->nomeServico }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="tipo">Método da Preventiva</label>
                                <select class="form-select select2" id="tipo" name="tipo" required>
                                    @if ($manutencao->veiculo->tipo == 'maquinas')
                                        <option value="horas" {{ $manutencao->tipo == 'horas' ? 'selected' : '' }}>Horas</option>
                                    @else
                                        <option value="">Selecione</option>
                                        <option value="quilometragem" {{ $manutencao->tipo == 'quilometragem' ? 'selected' : '' }}>Quilometragem</option>
                                        <option value="tempo" {{ $manutencao->tipo == 'tempo' ? 'selected' : '' }}>Tempo</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        @if ($manutencao->veiculo->tipo == 'maquinas')
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="horimetro_atual">Horímetro atual da máquina</label>
                                    @php
                                        $ultima_quilometragem = $manutencao->veiculo->quilometragens->last();
                                    @endphp
                                    <input class="form-control" id="horimetro_atual" name="horimetro_atual" type="number" value="{{$manutencao->horimetro_atual}}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="horimetro_proximo">Próximo Horímetro (Preventiva)</label>
                                    <input class="form-control" id="horimetro_proximo" name="horimetro_proximo" type="number" value="{{ $manutencao->horimetro_proximo }}">
                                </div>
                            </div>
                         @else
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="quilometragem_atual">Quilometragem atual do veículo</label>
                                    @php
                                        $ultima_quilometragem = $manutencao->veiculo->quilometragens->last();
                                    @endphp
                                    <input class="form-control" id="quilometragem_atual" name="quilometragem_atual" type="number" value="{{ $manutencao->quilometragem_atual }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="quilometragem_nova">Próxima Quilometragem (Preventiva)</label>
                                    <input class="form-control" id="quilometragem_nova" name="quilometragem_nova" type="number" value="{{ $manutencao->quilometragem_nova }}">
                                </div>
                            </div>
                        @endif


                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="data_de_execucao">Data de Execução</label>
                                <input class="form-control" id="data_de_execucao" name="data_de_execucao" type="date" value="{{ $manutencao->data_de_execucao }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="data_de_vencimento">Data de Vencimento</label>
                                <input class="form-control" id="data_de_vencimento" name="data_de_vencimento" type="date" value="{{ $manutencao->data_de_vencimento }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="situacao">Situação</label>
                                <select class="form-select select2" id="situacao" name="situacao">
                                    <option value="">Selecione</option>
                                    <option value="1" {{ $manutencao->situacao == 1 ? 'selected' : '' }}>Pendente</option>
                                    <option value="2" {{ $manutencao->situacao == 2 ? 'selected' : '' }}>Em Execução</option>
                                    <option value="3" {{ $manutencao->situacao == 3 ? 'selected' : '' }}>Concluído</option>
                                    <option value="4" {{ $manutencao->situacao == 4 ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="valor_do_servico">Valor do Serviço</label>
                                <div class="d-flex">
                                    <span class="pr-2" style="margin-top: 10px; font-size:18px; margin-right: 8px">R$ </span>
                                    <input class="form-control" id="valor_do_servico" name="valor_do_servico" type="text" value="{{$manutencao->valor_do_servico }}">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-8">
                                <label class="form-label" for="descricao">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" cols="30" rows="6">{{ $manutencao->descricao }}</textarea>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <input name="veiculo_id" type="hidden" value="{{ $manutencao->veiculo_id }}">
                            <input name="veiculo_tipo" type="hidden" value="{{ $manutencao->veiculo->tipo }}">
                            <button class="btn btn-primary btn-sm font-weight-medium" type="submit">Salvar</button>

                            <a href="{{ url('admin/ativo/veiculo') }}">
                                <button class="btn btn-warning btn-sm font-weight-medium" type="button">Cancelar</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha256-Kg2zTcFO9LXOc7IwcBx1YeUBJmekycsnTsq2RuFHSZU=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function($) {
        $('#valor_do_servico').mask('000.000.000.000.000,00', {
            reverse: true
        });
    });
</script>
@endsection