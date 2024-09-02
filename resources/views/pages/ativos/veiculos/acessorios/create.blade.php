@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span>
        @if ($veiculo->tipo == 'maquinas')
        Acessórios de Máquina
        @else
        Acessórios de Veículo
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

                <form method="post" action="{{ route('ativo.veiculo.acessorios.store', $veiculo->id) }}">
                    @csrf

                    <div class="row mt-3">
                        <div class="col-md-2">
                            <label class="form-label">
                                ID do Veiculo
                            </label>

                            <input class="form-control" id="veiculo_id" name="veiculo_id" type="text" value="{{$veiculo->id}}" readonly>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label" for="nome_acessorio"> Nome do Acessório </label>
                            <input class="form-control" id="nome_acessorio" name="nome_acessorio" type="text" value="{{ old('nome_acessorio') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="ano_aquisicao"> Ano da aquisição </label>
                            <input class="form-control" id="ano_aquisicao" name="ano_aquisicao" type="text" value="{{ old('ano_aquisicao') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="n_serie"> Nº de série </label>
                            <input class="form-control" id="n_serie" name="n_serie" type="text" value="{{ old('n_serie') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" for="valor"> Valor </label>
                            <input class="form-control" id="valor" name="valor" type="text" value="{{ old('valor') }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-7">
                            <label class="form-label" for="n_serie"> Observações </label>
                            <textarea rows="10" class="form-control" id="observacao" name="observacao" type="text" value="" style="height: 150px !important;">{{ old('observacao') }}</textarea>
                        </div>
                    </div>

                    <div class="col-12 mt-5">
                        <input name="veiculo_id" type="hidden" value="{{$veiculo->id}}">
                        <button class="btn btn-gradient-primary btn-lg font-weight-medium" type="submit">Salvar</button>

                        <a href="{{'ativo/veiculo/acessorios/index', $veiculo->id}}">
                            <button class="btn btn-gradient-danger btn-lg font-weight-medium" type="button">Cancelar</button>
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
        $('#valor').mask('000.000.000.000.000,00', {
            reverse: true
        });
    });
</script>
@endsection