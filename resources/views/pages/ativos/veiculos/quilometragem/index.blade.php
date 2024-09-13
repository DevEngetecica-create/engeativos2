@extends('dashboard')
@section('title', 'Veículo')
@section('content')

    <div class="row ">
        <div class="col-6 breadcrumb-item active" aria-current="page">
            <h3 class="page-title text-left">
                
                @if ($veiculo->tipo == 'maquinas')
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-car-clock mdi-24px"></i>
                    </span> 
                    
                    Horímetro da Máquina
                @else
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-road-variant mdi-24px"></i>
                    </span> 
                
                    Quilometragem do Veículo
                    
                @endif
            </h3>
        </div>

        <div class="col-4 active m-2">
            <h5 class="page-title text-left m-0">
                <span>Veículos <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
            </h5>
        </div>
    </div>

    <hr>
    
<div class="page-header">
    <h3 class="page-title">
        <a class="btn btn-md btn-success" href="{{ route('ativo.veiculo.quilometragem.adicionar', $veiculo->id) }}">
            Adicionar
        </a>
    </h3>
</div>

<div class="row mt-4">
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

                {{-- DADOS DO VEÍCULO/MÁQUINA --}}
                @include('pages.ativos.veiculos.partials.header')

                {{--
                <div class="d-flex bd-highlight">                    

                    <div class="p-1 bd-highlight">

                        <div class=" mb-0 alert alert-{{($calculTempoTrabalho > 0)? 'success': 'warning'}}" role="alert">
                        
                        @if($calculTempoTrabalho > 0) 
                        
                            <span style='font-size:30px;'>&#128077;</span>

                            @else
                            
                            <span style="font-size:30px;">&#128552;</span>

                        @endif
                        
                        {{$mensagemAviso}} 

                        </div>
                    </div>

                    <div class="p-1 bd-highlight" style="display: {{($calculTempoTrabalho <= 0) ? 'none' : 'show' }}">                               
                        <div class="alert alert-{{($calculTempoTrabalho > 0) ? 'success': 'warning'}}" role="alert">                       

                         {{$mensagemAvisoAlert}}
                         
                        </div>
                    </div>
                </div>
                --}}
                
                 <table class="table table-bordered table-hover table-sm align-middle table-nowrap mb-0 mt-3">
                    <thead>
                        <tr>
                            <th  class="text-center" width="8%">ID</th>
                            @if ($veiculo->tipo === 'maquinas')
                            <th  class="text-center">horímetro anterio</th>
                            @else
                            <th  class="text-center">km Anterior</th>
                            @endif
                            
                            @if ($veiculo->tipo === 'maquinas')
                            <th  class="text-center">horímetro Atual</th>
                            @else
                            <th  class="text-center">km Atual</th>
                            @endif

                            <th>Cadastrado por?</th>

                            <th  class="text-center">Data</th>
                            <th  class="text-center" width="10%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($quilometragens as $quilometragem)
                        <tr @if ($loop->first) style="background-color:#98FB98" @endif>
                            <td class="text-center">{{ $quilometragem->id }}</td>

                            <td class="text-center">{{ $quilometragem->quilometragem_atual ?? $quilometragem->horimetro_atual }} {{ $veiculo->tipo == 'maquinas' ? ' hr' : ' km' }}</td>
                            
                            <td class="text-center">{{ $quilometragem->quilometragem_nova ?? $quilometragem->horimetro_novo }} {{ $veiculo->tipo == 'maquinas' ? ' hr' : ' km' }}</td>

                            <td >{{$quilometragem->usuario ?? 'Sem reg.'}}</td>
                            
                            @if ($veiculo->tipo === 'maquinas')
                                <td class="text-center">{{ Tratamento::dateBr($quilometragem->data_horimetro) }}</td>
                            @else
                                <td class="text-center">{{ Tratamento::dateBr($quilometragem->created_at ) }}</td>
                            @endif


                            <td class="text-center d-flex gap-2 ">

                                <a href="{{ route('ativo.veiculo.quilometragem.editar', [$quilometragem->id, 'edit']) }}&tipo={{$veiculo->tipo}}">
                                    <button class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                </a>

                                @if ($loop->first)
                                <form action="{{ route('ativo.veiculo.quilometragem.delete', $quilometragem->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <a class="excluir-padrao" data-id="{{ $quilometragem->id }}" data-table="empresas" data-module="cadastro/empresa">
                                        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </a>
                                </form>
                                @endif
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