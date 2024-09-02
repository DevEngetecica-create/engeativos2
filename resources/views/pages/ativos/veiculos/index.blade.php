@extends('dashboard')
@section('title', 'Veículos & Máquinas')

@section('css')
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="card">
        <div class="card-body pt-1">
            <div class="row align-items-center align-self-center">
                <div class="col-sm-12 col-xl-3 mt-xl-3">
                    <h3 class="page-title">
                        Veículos & Máquinas
                    </h3>
                </div>

                <div class="col-sm-12 col-xl-2 mt-xl-2">
                    <h3 class="page-title mx-sm-auto">
                        @if (session()->get('usuario_vinculo')->id_nivel <= 2 or session()->get('usuario_vinculo')->id_nivel == 13)
                            <a href="{{ route('veiculo.create') }}" class="btn btn-success mx-3">Cacadastrar</a>
                        @endif
                    </h3>
                </div>
                <div class="col-7 mt-2">
                    <nav class="navbar navbar-light bg-light">
                        <form class="container-fluid justify-content-evenly">                            
                           
                                <button class="btn btn-sm btn-secondary py-3" type="button">Acessos rápidos</button>
                           
                            <a href="{{route('tipos_veiculos.index')}}" title="Acessar">
                                <button class="btn btn-sm btn-outline-secondary" type="button">Tipos de Veículos</button>
                            </a>
                            <a href="/admin/ativo/veiculo/categoria" title="Acessar">
                                <button class="btn btn-sm btn-outline-secondary m-2" type="button">Categorias</button>
                            </a>
                            <a href="/admin/ativo/veiculo/subCategoria" title="Acessar">
                                <button class="btn btn-sm btn-outline-secondary" type="button">Subcategorias</button>
                            </a>
                            <a href="{{route('veiculo.manut_preventiva.index')}}" title="Acessar">
                                <button class="btn btn-sm btn-outline-secondary mx-2" type="button">Preventivas</button>
                            </a>
                            <a href="/admin/ativo/veiculo/locacaoVeiculos" title="Acessar">
                                <button class="btn btn-sm btn-outline-secondary" type="button">Locações de Veiculos</button>
                            </a>
                        </form>
                    </nav>
                </div>
            </div>

            @if (session('mensagem'))
                <div class="alert alert-warning">
                    {{ session('mensagem') }}
                </div>
            @endif


            {{-- Deixar em standby --}}
            {{-- <div class="row justify-content-center align-items-center">
                

                
                {{-- <div class="col-sm-12 col-xl-10 my-sm-4 my-xl-0">

                    <form method="get" action="{{ route('veiculos.index') }}" class="form row g-4 mt-sm-4 mt-xl-0"
                        enctype="multipart/form-data">


                        <div class="col-sm-6 col-xl-7 mt-sm-3 m-xl-0">
                            <label class="form-label">Pesquisar</label>
                            <div class="input-group ml-1">
                                <input type="text" id="search" name="search" value="{{ request()->input('search') }}"
                                    class="form-control">
                                <div>
                                    <button type="submit" class="input-group-text p-1 px-2 bg-warning" title="Pesquisar"><i
                                            class="mdi mdi-magnify mdi-18px"></i></button>
                                </div>
                                <div>
                                    <a href="{{ route('veiculos.index') }}" title="Limpar pesquisa">
                                        <span class="input-group-text p-1 px-2 bg-primary mx-1"><i
                                                class="mdi mdi-broom mdi-18px text-white"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex col-sm-3 col-xl-1 mt-xl-4  mt-2" readonly>
                            <span id="print" class="btn btn-success ml-2 btn-md mx-3" readonly><i
                                    class="mdi mdi-file-excel-box "></i></span>
                            <a href="" class="btn btn-info btn-md " readonly> <i class="mdi mdi-download"></i></a>
                        </div>
                    </form>
                </div>
                
            </div> --}}

            <div class="card">
                <div class="card-header">
                    <h5> Lista </h5>
                </div>

                <div class="table-responsive">
                    <div class="card-body">
                        <table id="tabela-veiculos" class="table table-hover table-sm table-bordered" style="width:100%">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="text-center" scope="col">ID</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Marca</th>
                                    <th scope="col">Modelo</th>
                                    <th class="text-center" scope="col">Ano</th>
                                    <th scope="col">Placa</th>
                                    <th scope="col">Valor FIPE</th>
                                    <th class="text-center" scope="col">Ações</th>
                                </tr><!-- end tr -->
                            </thead><!-- thead -->

                            <tbody>
                                @foreach ($veiculos as $veiculo)
                                    <tr>
                                        <td class="text-center">{{ $veiculo->id }}</td>
                                        <td>{{ $veiculo->tipos->nome_tipo_veiculo ?? 'Não cadastrado' }}</td>
                                        <td>{{ $veiculo->marca }}</td>
                                        <td>{{ $veiculo->modelo }}</td>
                                        <td class="text-center">{{ $veiculo->ano }}</td>
                                        <td>{{ $veiculo->placa }}</td>
                                        <td>R$ {{ number_format($veiculo->valor_fipe, 2, ',', '.') }}</td>

                                        <td class="d-flex justify-content-center">

                                            <a href="/admin/ativo/veiculo/show/{{ $veiculo->id }}"
                                                class="btn btn-outline-primary btn-sm" title="Detalhes"><i
                                                    class="mdi mdi-eye"></i></a>

                                            <a href="{{ route('veiculo.edit', $veiculo->id) }}"class="btn btn-outline-secondary btn-sm mx-2"
                                                title="Editar"><i class="mdi mdi-pencil"></i></a>

                                            <form action="{{ route('veiculo.delete', $veiculo->id) }}" method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja excluir este registro?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"><i
                                                        class="mdi mdi-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody><!-- end tbody -->
                        </table><!-- end table -->
                    </div>
                </div><!-- end card body -->
            </div>

            <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js">
            </script>
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

        @endsection
        @section('script')


            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

            <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

        @endsection
