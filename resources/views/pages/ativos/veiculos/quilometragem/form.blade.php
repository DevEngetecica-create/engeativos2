@extends('dashboard')
@section('title', 'Veículo')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span>
            @if ($store->veiculo->tipo == 'maquinas')
                Holímetro da máquina
            @else
                Quilometragem do veículo
            @endif

        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
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

                    {{-- @dd($store) --}}

                    <form method="post" action="{{ $btn == 'add' ? route('ativo.veiculo.quilometragem.store', $store->veiculo_id) : route('ativo.veiculo.quilometragem.update', $store->id) }}">
                        @csrf
                        <div class="jumbotron p-3">
                            <span class="font-weight-bold">{{ $store->veiculo->marca }} | {{ $store->veiculo->modelo }} | {{ $store->veiculo->veiculo }}</span>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="quilometragem_atual">
                                    @if ($store->veiculo->tipo == 'maquinas')
                                        Holímetro atual
                                    @else
                                        Quilometragem Atual
                                    @endif
                                </label>
                                <input class="form-control" id="quilometragem_atual" name="quilometragem_atual" type="number" value="{{ $btn == 'add' ? $store->quilometragem_nova : $store->quilometragem_atual }}" {{ $btn == 'add' ? 'readonly' : 'readonly' }}>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="quilometragem_nova">
                                    @if ($store->veiculo->tipo == 'maquinas')
                                        Holímetro novo
                                    @else
                                        Quilometragem Nova
                                    @endif
                                </label>
                                <input class="form-control" id="quilometragem_nova" name="quilometragem_nova" type="number" value="{{ $btn == 'add' ? '' : $store->quilometragem_nova ?? 0}}">
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <button class="btn btn-primary btn-sm font-weight-medium" type="submit">Salvar</button>

                            <a href="{{url('admin/ativo/veiculo')}}">
                                <button class="btn btn-warning btn-sm font-weight-medium" type="button">Cancelar</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
