@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row mt-4">
            <div class="col-6 breadcrumb-item active" aria-current="page">
                <h3 class="page-title text-left">
                    
                    @if ($veiculo->tipo == 'maquinas')
                        <span class="page-title-icon bg-gradient-primary me-2">
                            <i class="mdi mdi-gas-station mdi-24px"></i>
                        </span> 
                        
                        Horímetro da máquina
                    @else
                        <span class="page-title-icon bg-gradient-primary me-2">
                            <i class="mdi mdi-road-variant mdi-24px"></i>
                        </span> 
                    
                       Quilometragem do veículo
                        
                    @endif
                </h3>
            </div>
    
            <div class="col-4 active m-2">
                <h5 class="page-title text-left m-0">
                    <span>Cadastro <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
                </h5>
            </div>
        </div>
    
        <hr>
        

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
        
                        @php
        
                        // Identifica o último registro da manutenção
                        $ultimaQuilometragemManutencao = $veiculo->manutencaos->last();
        
                        // Identifica o último registro do horimetro
                        $ultimoHorimetro = $veiculo->horimetro->last();
        
                        // Identifica o último registro da quilometragem
                        $ultimaQuilometragem = $veiculo->quilometragens->last();
        
                        @endphp
        
        
                        <form method="post" action="{{ route('ativo.veiculo.quilometragem.store', $veiculo->id) }}">
                            @csrf
                            <div class="jumbotron p-3">
                                <span class="font-weight-bold">{{ $veiculo->marca }} | {{ $veiculo->modelo }} | {{ $veiculo->veiculo }}</span>
                            </div>
                            <div class="row mt-3">
        
                                <div class="col-md-3">
                                    <div class="d-flex flex-column bd-highlight mb-3">
                                        <div class="p-1 bd-highlight">
                                            @if ($veiculo->tipo == "maquinas")
        
                                            <label class="form-label" for="horimetro_atual">Horímetro atual</label>
        
                                            <input class="form-control" id="horimetro_atual" name="horimetro_atual" type="number" value="{{($ultimaQuilometragem->horimetro_atual ?? 0 !== null AND $veiculo->tipo != 'maquinas') ? $ultimaQuilometragem->horimetro_atual ?? 0:   $ultimoHorimetro->horimetro_novo ?? 0}}" readonly>
        
        
                                            @else
        
                                            <label class="form-label" for="quilometragem_atual">Quilometragem Atual </label>
        
        
        
                                            <input class="form-control" id="quilometragem_atual" name="quilometragem_atual" type="number" value="{{($ultimaQuilometragem->quilometragem_atual !== null AND $veiculo->tipo != 'maquinas') ? $ultimaQuilometragem->quilometragem_nova:   $ultimoHorimetro->horimetro_novo}}" readonly>
        
                                            @endif
        
                                        </div>
        
                                        <div class="p-1 bd-highlight">
                                            <div class=" mb-0 alert alert-{{($maiorValorTblManutencao - $maiorValorQuilometragem > 0)? 'success': 'warning'}}" role="alert">
                                                A próx. revisão será com {{$maiorValorTblManutencao}}
                                                @if ($veiculo->tipo == 'maquinas')
                                                horas
                                                @else
                                                km's
                                                @endif
                                            </div>
                                        </div>
                                        <div class="p-1 bd-highlight ">
                                            <div class="alert alert-{{($maiorValorTblManutencao - $maiorValorQuilometragem > 0)? 'success': 'warning'}}" role="alert">
        
                                                @if($maiorValorTblManutencao - $maiorValorQuilometragem <= 0) É necessário refazer a revisão' @elseif($maiorValorTblManutencao - $maiorValorQuilometragem> 0)
        
                                                    Restam {{$maiorValorTblManutencao - $maiorValorQuilometragem}}
                                                    @if ($veiculo->tipo == 'maquinas')
                                                    horas
                                                    @else
                                                    km's
                                                    @endif
        
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
        
                                </div>
        
                                <div class="col-md-3">
        
                                    @if ($veiculo->tipo == 'maquinas')
        
                                    <label class="form-label" for="horimetro_novo">Horímetro novo (não pode ser menor que o atual) <span class="text-danger"> *</span> </label> 
        
                                    <input class="form-control" id="horimetro_novo" name="horimetro_novo" type="number" value="{{ $ultimoHorimetro->horimetro_novo ?? 0 }}" min="{{-- $ultimoHorimetro->horimetro_novo ?? 0 --}}" value="{{ $ultimoHorimetro->horimetro_novo ?? 0 }}" required>
        
                                    @else
        
                                    <label class="form-label" for="quilometragem_nova">Quilometragem Nova (não pode ser menor que o atual)</label>
        
                                    <input class="form-control" id="quilometragem_nova" name="quilometragem_nova" type="number" value="{{ $ultimaQuilometragem->quilometragem_nova ?? 0 }}" required min="{{ $ultimaQuilometragem->quilometragem_nova ?? 0 }}" {{--($ultimaQuilometragemManutencao->horimetro_proximo - $ultimaQuilometragem->quilometragem_nova < 0) ? 'readonly' :' ' --}}>
        
        
                                    @endif
        
                                </div>
        
                                <div class="col-md-2">
                                    <label class="form-label" for="horimetro_novo">Data do horimetro<span class="text-danger"> *</span></label>
                                    <input type="date" class="form-control" name="data_cadastro" value="{{old('data_cadastro')}}" required>
                                </div>
        
                                <div class="col-md-4">
                                    <label class="form-label" for="horimetro_novo">Nome do funcionário <span class="text-danger"> *</span></label>
                                    <select class="form-select select2" id="id_funcionario" name="id_funcionario" required>
                                        <option value="">Selecione um Motorista</option>
                                        @foreach ($funcionarios as $funcionario)
                                        <option value="{{ $funcionario->id }}" @php if(old('id_funcionario', @$store->id_funcionario) == $funcionario->id) echo "selected"; @endphp>
                                            {{ $funcionario->matricula }} - {{ $funcionario->nome }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
        
                            <div class="col-12 mt-5">
        
                                <input name="veiculo_tipo" type="hidden" value="{{$veiculo->tipo}}">
                                <input name="veiculo_id" type="hidden" value="{{ $veiculo->id }}">
                                
                                <button class="btn btn-primary btn-md font-weight-medium" type="submit">Salvar</button>
        
                                <a href="{{url('admin/ativo/veiculo')}}">
                                    <button class="btn btn-warning btn-md font-weight-medium" type="button">Cancelar</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
@endsection