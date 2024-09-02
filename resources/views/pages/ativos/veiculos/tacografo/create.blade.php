@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span>
        Tacografo
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

                <form id="formCadastroTacografo" method="POST" action="{{ route('ativo.veiculo.tacografo.store', $veiculo->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="col mt-3">
                        <div class="col-md-2">
                            <label class="form-label">
                                ID do Veiculo
                            </label>

                            <input class="form-control" id="veiculo_id" name="veiculo_id" type="text" value="{{$veiculo->id}}" readonly>
                        </div>


                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="descricao">Descrição:</label>
                                <input type="text" name="descricao" id="descricao" class="form-control" value="{{ old('descricao') }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="data_emissao">Data de Emissão:</label>
                                <input type="date" name="data_da_emissao" id="data_da_emissao" class="form-control" value="{{ old('data_da_emissao') }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="data_vencimento">Data de Vencimento:</label>
                                <input type="date" name="data_do_vencimento" id="data_do_vencimento" class="form-control" value="{{ old('data_do_vencimento') }}">
                            </div>
                        </div>

                        <small>
                            <div id="mensagem">

                            </div>
                        </small>

                        <div class="row mt-3">
                            <div class="col-md-7">
                                <label class="form-label" > Observações </label>
                                <textarea rows="10" class="form-control" id="observacao" name="observacao" type="text" value="{{ old('data_do_vencimento') }}" style="height: 150px !important;">{{ old('observacao') }}</textarea>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <input name="veiculo_id" type="hidden" value="{{$veiculo->id}}">
                            <button class="btn btn-primary btn-sm font-weight-medium" type="submit">Salvar</button>

                            <a href="{{ route('ativo/veiculo/tacografo/index', $veiculo->id) }}">
                                <button class="btn btn-warning btn-sm font-weight-medium" type="button">Cancelar</button>
                            </a>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection