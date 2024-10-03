@extends('dashboard')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body p-5">
             
                    <h1>  Setores <i class="far fa-hand-point-right text-primary"></i></h1>   

                <div class="my-3">
                    <a href="{{route('cadastro.funcionario.setores.create')}}">
                        <button class="btn btn-primary">Castrar</button>
                    </a>
                </div>
                <table class="excel-filter-table table table-bordered table-hover table-sm align-middle table-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Setores</th>
                            <th>Usuário cad</th>
                            <th>Usuário edit</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($setores as $setor)
                            <tr>
                                <td>{{ $setor->id }}</td>
                                <td>{{ $setor->nome_setor }}</td>
                                <td>{{ $setor->user_create }}</td>                               
                                <td>{{ $setor->user_edit ?? "" }}</td>                               

                                <td class="d-flex justify-content-center">
                                    <a href="{{ route('cadastro.funcionario.setores.edit', $setor->id) }}">
                                        <button class="btn btn-outline-primary btn-sm"> Editar</button>
                                    </a>
                                    <a href="{{ route('cadastro.funcionario.setores.show',$setor->id) }}">
                                       <button class="btn btn-outline-success btn-sm mx-2">Detalhes</button>
                                    </a>

                                    <form action="{{ route('cadastro.funcionario.setores.delete', $setor->id) }}" method="POST"
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
                <!-- Container para a paginação (opcional) -->
                <div id="meu-container-paginacao" class="d-flex justify-content-end mt-3">
                    <!-- Os botões de paginação serão inseridos aqui pelo plugin -->
                </div>
            </div>
        </div>
    </div>
@endsection
