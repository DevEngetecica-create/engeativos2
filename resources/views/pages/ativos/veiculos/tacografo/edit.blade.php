@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span>
        @if ($tacografo->veiculo->tipo == 'maquinas')
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

                <form method="post" action="{{ route('ativo/veiculo/tacografo/editar', $tacografo->id) }}">
                    @csrf
                    @method('post')

                    <div class="row mt-3">
                        <div class="col-md-2">
                            <label class="form-label">
                                ID do Veiculo
                            </label>

                            <input class="form-control" id="descricao" name="descricao" type="text" value="{{ $tacografo->veiculo_id }}" readonly>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label" for="nome_acessorio"> Descrição </label>
                            <input class="form-control" id="descricao" name="descricao" type="text" value="{{ $tacografo->descricao }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="ano_aquisicao"> Data de Emissão </label>
                            <input class="form-control" type="date" name="data_da_emissao" id="data_da_emissao" value="{{ $tacografo->data_da_emissao }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="n_serie"> Data de vencimento </label>
                            <input class="form-control" type="date" name="data_do_vencimento" id="data_do_vencimento" value="{{ $tacografo->data_do_vencimento }}">
                        </div>


                    </div>

                    <div class="row mt-3">

                        <div class="col-md-7">
                            <label class="form-label" for="n_serie"> Observações </label>
                            <textarea rows="10" class="form-control" id="observacao" name="observacao" type="text" value="" style="height: 150px !important;">{{ $tacografo->observacao }}</textarea>
                        </div>
                    </div>

                    <div class="col-12 mt-5">
                        <input name="veiculo_id" type="hidden" value="{{$tacografo->veiculo_id}}">
                        <button class="btn btn-gradient-primary btn-lg font-weight-medium" type="submit">Salvar</button>

                        <a href="{{'ativo/veiculo/tacografo/index', $tacografo->veiculo_id}}">
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