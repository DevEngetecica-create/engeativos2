@extends('dashboard')
@section('title', 'Veículos & Máquinas')
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row align-items-center align-self-center">
                <div class="page-header col m-0 p-0 px-4 mt-xl-3 mt-sm-1">
                    <h3 class="page-title">
                        Manutenções Preventivas - Máquinas e Veículos
                    </h3>
                </div>

                @if (session('mensagem'))
                    <div class="alert alert-warning">
                        {{ session('mensagem') }}
                    </div>
                @endif

            </div>
            <div class="row justify-content-center align-items-center">
                <div class="col-sm-12 col-xl-2 mt-xl-4">
                    <h3 class="page-title mx-sm-auto">
                        @if (session()->get('usuario_vinculo')->id_nivel <= 2 or session()->get('usuario_vinculo')->id_nivel == 13)
                            <a href="{{ route('veiculo.manut_preventiva.create') }}" class="btn btn-success mx-3">Cacadastrar</a>
                        @endif
                    </h3>
                </div>

                <div class="col-sm-12 col-xl-10 my-sm-4 my-xl-0">
                    <form method="get" action="{{ route('veiculo.manut_preventiva.index') }}" class="form row g-4 mt-sm-4 mt-xl-0" enctype="multipart/form-data">

                        <div class="col-sm-6 col-xl-7 mt-sm-3 m-xl-0">
                            <label class="form-label">Pesquisar</label>
                            <div class="input-group ml-1">
                                <input type="text" id="search" name="search" value="{{ request()->input('search') }}" class="form-control">
                                <div>
                                    <button type="submit" class="input-group-text p-1 px-2 bg-warning" title="Pesquisar"><i class="mdi mdi-magnify mdi-18px"></i></button>
                                </div>
                                <div>
                                    <a href="{{ route('veiculo.manut_preventiva.index') }}" title="Limpar pesquisa">
                                        <span class="input-group-text p-1 px-2 bg-primary mx-1"><i class="mdi mdi-broom mdi-18px text-white"></i></span>                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <hr class="dropdown-divider bg-dark mb-4">

            <div class="card table-responsive">
                <div class="">
                    <div class="card-body p-1" id="tabela_veiculos">
                        <table class="table table-bordered table-sm table-hover table-midle">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>Nome da Preventiva</th>
                                    <th>Tipo de Veículo</th>
                                    <th class="text-center">Serviços</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($preventivas as $preventiva)
                                    <tr>
                                        <td class="text-center">{{ $preventiva->id }}</td>
                                        <td>{{ $preventiva->nome_preventiva }}</td>
                                        <td>{{ $preventiva->tipo_veiculos->nome_tipo_veiculo }}</td>
                                        <td class="text-center">
                                            @php
                                                $situacoes = json_decode($preventiva->situacao, true);
                                                $nomeServicos = json_decode($preventiva->nome_servico, true);
                                                $servicosAgrupados = [];
                                                foreach ($situacoes as $index => $situacao) {
                                                    if ($situacao == 1) {
                                                        $servicosAgrupados['Executar'][] = $nomeServicos[$index];
                                                    } elseif ($situacao == 2) {
                                                        $servicosAgrupados['Executar conforme condição'][] =
                                                            $nomeServicos[$index];
                                                    } elseif ($situacao == 3) {
                                                        $servicosAgrupados['Conferir/ verificar'][] =
                                                            $nomeServicos[$index];
                                                    }
                                                }
                                            @endphp
                                            @foreach ($servicosAgrupados as $situacao => $servicos)
                                                <small class="badge bg-primary">{{ $situacao }}</small>
                                            @endforeach
                                        </td>

                                        <td class="d-flex justify-content-center">
                                            <a href="{{ route('veiculo.manut_preventiva.edit', $preventiva->id) }}"
                                                class="btn btn-outline-warning btn-sm" title="Editar"><i class="mdi mdi-lead-pencil"></i></a>
                                            <form action="{{ route('veiculo.manut_preventiva.delete', $preventiva->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm mx-2" title="Excluir"><i class="mdi mdi-trash-can"></i></button>
                                            </form>

                                            <a href="{{ route('veiculo.manut_preventiva.show', $preventiva->id) }}"
                                                class="btn btn-outline-info btn-sm" title="Detalhes"><i class="mdi mdi-eye"></i></a>

                                            <a href="" class="btn btn-outline-success btn-sm mx-2" title="Checklist"><i class="mdi mdi-format-list-checks"></i></a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row mt-2">
                            <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">
                                <div class="paginacao">
                                    {{ $preventivas->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
