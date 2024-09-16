@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                @foreach($tipo_nome as $nome_tipo)
                   
                    @if($nome_tipo->id == $tipo_veiculo_id)
                    <h1>  Documento Legais <i class="far fa-hand-point-right text-primary"></i> {{$nome_tipo->nome_tipo_veiculo}} </h1>
                    @endif
                    
                @endforeach

                <div class="my-3">
                    <a href="{{route('docs_legais.create', $tipo_veiculo_id)}}">
                        <button class="btn btn-primary">Castrar</button>
                    </a>
                </div>
                <table class="table table-border">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo de Veículo</th>
                            <th>Nome Documento</th>
                            <th>Validade</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($docs as $doc)
                            <tr>
                                <td>{{ $doc->id }}</td>
                                <td>{{ $doc->nomeTipo_veiculo->nome_tipo_veiculo }}</td>
                                <td>{{ $doc->nome_documento }}</td>
                                <td>{{ $doc->validade }} meses</td>

                                <td class="d-flex justify-content-center">
                                    <a href="{{ route('docs_legais.edit', $doc->id) }}">
                                        <button class="btn btn-outline-primary btn-sm"> Editar</button>
                                    </a>
                                    <a href="{{ route('docs_legais.show',$doc->id) }}">
                                       <button class="btn btn-outline-success btn-sm mx-2">Detalhes</button>
                                    </a>

                                    <form action="{{ route('docs_legais.delete', $doc->id) }}" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este registro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Exluir</button>
                                    </form>

                                    
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
