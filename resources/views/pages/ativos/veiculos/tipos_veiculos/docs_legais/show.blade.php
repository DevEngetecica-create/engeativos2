@extends('dashboard')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">

                <h1>Detalhes Documento Legal do Tipo de Veículo</h1>

                <div class="list-group mt-5">
                    <h5>
                        <a href="#" class="list-group-item list-group-item-action active" aria-current="true"> {{$doc->nomeTipo_veiculo->nome_tipo_veiculo}} </a>
                    </h5>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Documento:</strong> {{$doc->nome_documento}}</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Cad. por: </strong>{{$doc->user_create}}</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Cad. em: </strong>{{$doc->created_at ? \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y H:i:s') : '' }}</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Edit. por: </strong>{{$doc->user_edit}}</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Edit. em: </strong>{{ $doc->updated_at ? \Carbon\Carbon::parse($doc->updated_at)->format('d/m/Y H:i:s') : '' }}</a>                 
                </div>

            </div>
        </div>
    </div>
@endsection
