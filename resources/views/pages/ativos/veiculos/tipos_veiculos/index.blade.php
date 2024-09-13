@extends('dashboard')
@section('title', 'Veículos & Máquinas')
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row align-items-center align-self-center">
                <div class=" col m-0 p-0 px-4 mt-xl-3 mt-sm-1">
                    <h3 class="page-title">
                        Tipos de Veículos
                    </h3>

                </div>

                <div class="col-sm-12 col-xl-2 mt-xl-4">
                    <h3 class="page-title mx-sm-auto">
                        @if (session()->get('usuario_vinculo')->id_nivel <= 2 or session()->get('usuario_vinculo')->id_nivel == 13)
                            <a href="{{ route('tipos_veiculos.create') }}" class="btn btn-success mx-3">Cacadastrar</a>
                        @endif
                    </h3>
                </div>

                @if (session('mensagem'))
                    <div class="alert alert-warning">
                        {{ session('mensagem') }}
                    </div>
                @endif

            </div>
         
            <div class="card-header">
                <h5> Lista </h5>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered" style="width:100%">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Tipo de Veículo</th>
                            <th class="text-center">Situação</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tipos_veiculos as $tipo_veiculo)
                            <tr>
                                <td class="text-center">{{ $tipo_veiculo->id }}</td>
                                <td>{{ $tipo_veiculo->nome_tipo_veiculo }}</td>
                                <td class="text-center">{{ $tipo_veiculo->situacao == 1 ? 'Ativo' : 'Inativo' }}
                                </td>

                                <td class="d-flex justify-content-center">
                                    <a href="{{ route('tipos_veiculos.edit', $tipo_veiculo->id) }}"
                                        class="btn btn-outline-warning btn-sm" title="Editar"><i
                                            class="mdi mdi-lead-pencil"></i>Editar</a>

                                    <form action="{{ route('tipos_veiculos.delete', $tipo_veiculo->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm mx-2" title="Excluir"><i
                                                class="mdi mdi-trash-can"></i>Excluir</button>
                                    </form>

                                    <a href="{{ route('tipos_veiculos.show', $tipo_veiculo->id) }}"
                                        class="btn btn-outline-info btn-sm" title="Detalhes"><i
                                            class="mdi mdi-eye "></i>Detalhes</a>

                                    <a href="{{ route('docs_legais.index', $tipo_veiculo->id) }}">
                                        <button class="btn btn-outline-success btn-sm mx-4 "><i
                                                class="mdi mdi-text-box-check-outline"></i>Docs Legais</button>
                                    </a>

                                    <a href="{{ route('docs_tecnicos.index', $tipo_veiculo->id) }}">
                                        <button class="btn btn-outline-info btn-sm"><i
                                                class="mdi mdi-text-box-check-outline"></i>Docs Técnicos</button>
                                    </a>

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row mt-2">
                    <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">
                        <div class="paginacao">
                            {{ $tipos_veiculos->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
