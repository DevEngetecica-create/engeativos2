@extends('dashboard')

@section('content')   

    <div class="container">
        <div class="card">
            <div class="card-body">

                <h1>Setor</h1>


                <div class="list-group mt-5">
                    <a href="#" class="list-group-item list-group-item-action" aria-current="true">
                        <strong>Nome do setor: </strong> {{$setor->nome_setor}}
                    </a>
                   
                    <a href="#" class="list-group-item list-group-item-action"><strong>Cad. por: </strong>{{$setor->user_create}}</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Cad. em: </strong>{{$setor->created_at ? \Carbon\Carbon::parse($setor->created_at)->format('d/m/Y H:i:s') : '' }}</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Edit. por: </strong>{{$setor->user_edit}}</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Edit. em: </strong>{{ $setor->updated_at ? \Carbon\Carbon::parse($setor->updated_at)->format('d/m/Y H:i:s') : '' }}</a>                 
                </div>

            </div>
        </div>
    </div>

@endsection
