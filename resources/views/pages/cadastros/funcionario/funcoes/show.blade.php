@extends('dashboard')
@section('title', 'Funções CBO')
@section('content')

    <div class="page-header my-4">
        <h3 class="page-title my-4">
            <span class="page-title-icon bg-gradient-primary text-white">
            </span> Dados da Função
        </h3>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a class="btn btn-success" href="{{ route('cadastro.funcionario.funcoes.index') }}">
                        <i class="mdi mdi-arrow-left icon-sm align-middle text-white"></i> Voltar
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <hr class=" my-4">

    <div class="row">
        <div class="col-xl-6 col-sm-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Funcionários</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview ">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">ID</th>
                                        <th scope="col">Nome </th>
                                        <th class="text-center" scope="col">Obra</th>
                                        <th class="text-center" scope="col">Acessar</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($funcionarios as $funcionario)
                                        <tr>
                                            <th class="text-center" scope="row"><span
                                                    class="fw-medium">{{ $funcionario->id }}</span></th>
                                            <td>{{ $funcionario->nome }}</td>
                                            <td>{{ $funcionario->obra->codigo_obra }}</td>
                                            <td class=" text-center {{ session()->get('usuario_vinculo')->id_nivel <= 2 or (session()->get('usuario_vinculo')->id_nivel == 14 ? 'd-block' : 'd-none') }}">
                                                <a class="btn btn-info btn-sm mx-2" href="{{ route('cadastro.funcionario.show', $funcionario->id) }}" title="Visualizar">
                                                    Visualizar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>

        <!-- end col -->



        <div class="col-xl-5 col-sm-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Qualificações Necessárias para a função</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nome documento</th>
                                        <th class="text-center" scope="col">Tempo validade</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($qualificacoes as $itens)
                                        <tr>
                                            <td>{{ $itens->id }}</td>
                                            <td>{{ $itens->nome_qualificacao }}</td>
                                            <td class="text-center">{{ $itens->tempo_validade }} meses</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
        <!-- end col -->



        {{-- <div class="col-xl-4 col-sm-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Lista de Epis Obrigatórios</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <div class="table-responsive">
                        <table class="table table-striped table-nowrap align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">EPI's</th>                                                                 

                                </tr>
                            </thead>
                            <tbody>

                            @foreach ($lista_epis as $epi)
                                <tr>
                                    <td >{{$epi->id}}</td>
                                    <td>EPI Teste</td>                                                                
                                </tr>
                          
                            @endforeach        
                            </tbody>
                        </table>
                    </div>

                </div>


            </div><!-- end card-body -->

        </div><!-- end card -->

    </div> --}}

        <!-- end col -->

    </div>



@endsection
