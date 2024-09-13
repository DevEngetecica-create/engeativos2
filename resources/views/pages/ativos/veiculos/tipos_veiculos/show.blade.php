@extends('dashboard')

@section('content')
    <div class="card m-5">
        <div class="card-body m-3">
            <div class="card-header mb-3">
                <h1>Detalhes do Tipo de Veículo</h1>
            </div>

            <div class="container">
                <div class="list-group mt-5">
                    <a href="#" class="list-group-item list-group-item-action active" aria-current="true"> {{ $tipo_veiculo->nome_tipo_veiculo }} </a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Tag:</strong> {{ $tipo_veiculo->tipo_veiculo }}</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Cad. por: </strong>{{ $tipo_veiculo->user_create }}</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Cad. em: </strong>{{ $tipo_veiculo->created_at ? \Carbon\Carbon::parse($tipo_veiculo->created_at)->format('d/m/Y H:i:s') : '' }}</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Edit. por: </strong>{{ $tipo_veiculo->user_edit }}</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Edit. em: </strong>{{ $tipo_veiculo->updated_at ? \Carbon\Carbon::parse($tipo_veiculo->updated_at)->format('d/m/Y H:i:s') : '' }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
