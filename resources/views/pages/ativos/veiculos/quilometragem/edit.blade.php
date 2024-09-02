@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row mt-4">
            <div class="col-6 breadcrumb-item active" aria-current="page">
                <h3 class="page-title text-left">
                    
                    @if ($tipo == 'maquinas')
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
                    <span>Edição <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
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
    
                        {{-- @dd($store) --}}
    
                       <form method="post" action="{{ route('ativo.veiculo.quilometragem.update', $quilometragem->id) }}">
                            @csrf
                            @method('put')
                            <div class="jumbotron p-3">
                                <span class="font-weight-bold">{{ $quilometragem->veiculo->marca }} | {{ $quilometragem->veiculo->modelo }} | {{ $quilometragem->veiculo->veiculo }}</span>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="quilometragem_atual">
                                        @if ($tipo == 'maquinas')
                                            Holímetro atual
                                        @else
                                            Quilometragem anterior
                                        @endif
                                    </label>
                                    <input class="form-control" id="quilometragem_atual" name="quilometragem_atual" type="number" value="{{ $quilometragem->quilometragem_atual ?? $quilometragem->horimetro_atual }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="quilometragem_nova">
                                        @if ($tipo == 'maquinas')
                                            Holímetro novo
                                        @else
                                             Quilometragem Atual
                                        @endif
                                    </label>
                                    <input class="form-control" id="quilometragem_nova" name="quilometragem_nova" type="number" value="{{ $quilometragem->quilometragem_nova ?? $quilometragem->horimetro_novo}}" >
                                </div>
                            </div>
    
                            <div class="col-12 mt-5">
                                <input name="veiculo_id" type="hidden" value="{{ $quilometragem->veiculo_id }}">
                                <input name="veiculo_tipo" type="hidden" value="{{ $tipo }}">
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
