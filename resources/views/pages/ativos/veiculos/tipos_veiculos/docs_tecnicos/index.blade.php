@extends('dashboard')
@section('title', 'Veículos & Máquinas')
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row align-items-center align-self-center">
                <div class="page-header col m-0 p-0 px-4 mt-xl-3 mt-sm-1">

                    @foreach ($tipo_veiculo as $tipo)
                        @if ($tipo->id == $tipo_veiculo_id)
                            @if($tipo_veiculo_id == 1)                     
                                <h2 class="page-title"> Docs Técnicos para os  {{ $tipo->nome_tipo_veiculo }}</h2>
                            @elseif($tipo_veiculo_id == 2)    
                                <h2 class="page-title"> Docs Técnicos para as  {{ $tipo->nome_tipo_veiculo }}</h2>
                            @elseif($tipo_veiculo_id == 3)    
                                <h2 class="page-title"> Docs Técnicos para os  {{ $tipo->nome_tipo_veiculo }}</h2>
                            @elseif($tipo_veiculo_id == 4)    
                                <h2 class="page-title"> Docs Técnicos para as  {{ $tipo->nome_tipo_veiculo }}</h2>
                            @endif

                        @endif
                    @endforeach

                </div>

                @if (session('mensagem'))
                    <div class="alert alert-warning">
                        {{ session('mensagem') }}
                    </div>
                @endif

            </div>
            <div class="row justify-content-left align-items-center">
                <div class="col-sm-12 col-xl-2 mt-xl-4">
                    <h3 class="page-title mx-sm-auto">
                        @if (session()->get('usuario_vinculo')->id_nivel <= 2 or session()->get('usuario_vinculo')->id_nivel == 13)
                            <a href="{{ route('docs_legais.create', $tipo_veiculo_id)}}"class="btn btn-success mx-3">Cacadastrar</a>
                        @endif
                    </h3>
                </div>
            </div>

            <hr class="dropdown-divider bg-dark mb-4">
            <div class="container">

                <table class="table table-bordered table-sm table-hover">
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
                                    <a href="{{ route('docs_tecnicos.edit', $doc->id) }}">
                                        <button class="btn btn-outline-primary btn-sm"> Editar</button>
                                    </a>
                                    <a href="{{ route('docs_tecnicos.show',$doc->id) }}">
                                       <button class="btn btn-outline-success btn-sm mx-2">Detalhes</button>
                                    </a>

                                    <form action="{{ route('docs_tecnicos.delete', $doc->id) }}" method="POST"
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
