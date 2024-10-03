@extends('dashboard')
@section('title', 'Funções CBO')
@section('content')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/excelTableFilter.css') }}">

    <div class="row">
        <div class="col-2 breadcrumb-item active" aria-current="page">
            <h3 class="page-title text-center">
                <span class="page-title-icon bg-gradient-primary me-2">
                    <i class="mdi mdi-account-hard-hat  mdi-24px"></i>
                </span> Funções
            </h3>
        </div>

        <div class="col-4 active m-2">
            <h5 class="page-title text-left m-0">
                <span>Cadastros <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
            </h5>
        </div>

    </div>

    <hr>

    <div class="row my-4">
        <div class="col-2">
            <h3 class="page-title text-left">
                @if (session()->get('usuario_vinculo')->id_nivel == 1 or
                        session()->get('usuario_vinculo')->id_nivel == 10 or
                        session()->get('usuario_vinculo')->id_nivel == 15 or
                        session()->get('usuario_vinculo')->id_nivel == 14)
                    <a href="{{ route('cadastro.funcionario.funcoes.create') }}">
                        <span class="btn btn-sm btn-success">Novo Registro</span>
                    </a>
                    <span class="btn btn-primary" id="export-button">
                        Excel
                    </span>
                @endif
            </h3>
        </div>

    </div>


    <hr class="my-3">

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <table class="filter_funcoes table table-bordered table-hover table-sm" data-table-id="1">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>Código</th>
                                    <th>Função</th>
                                    <th class="text-center">Qtd Funcionários</th>
                                    <th class="text-center no-filter no-sort" width="10%">Ações</th>
                                    {{-- classes para remover o filtro no-filter no-sort --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($funcoes as $funcao)
                                    <tr>
                                        <td>{{ $funcao->id }}</td>
                                        <td>{{ $funcao->codigo ?? 'sem reg.' }}</td>
                                        <td>{{ $funcao->funcao ?? 'sem reg.' }}</td>
                                        <td> {{ $funcao->funcionarios ? count($funcao->funcionarios) : 0 }} </td>
                                        <td class="d-flex justify-content-center">
                                            @if (session()->has('usuario_vinculo') && session('usuario_vinculo')->id_nivel)
                                                @if (session()->get('usuario_vinculo')->id_nivel == 1 ||
                                                        session()->get('usuario_vinculo')->id_nivel == 10 ||
                                                        session()->get('usuario_vinculo')->id_nivel == 16)
                                                    <div class="hstack fs-15">
                                                        <a class="btn btn-success btn-sm"
                                                            href="{{ route('cadastro.funcionario.funcoes.show', $funcao->id) }}">
                                                            Ver
                                                        </a>

                                                        <a class="btn btn-warning btn-sm mx-1"
                                                            href="{{ route('cadastro.funcionario.funcoes.edit', $funcao->id) }}">
                                                            Editar
                                                        </a>

                                                        <form
                                                            action="{{ route('cadastro.funcionario.funcoes.destroy', $funcao->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('delete')

                                                            <button class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                                data-placement="top" type="submit" title="Excluir"
                                                                onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                                                Excluir
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span
                                                        class="d-inline-flex focus-ring py-1 px-2 text-decoration-none border rounded-2">
                                                        Não permitido
                                                    </span>
                                                @endif
                                            @else
                                                <span
                                                    class="d-inline-flex focus-ring py-1 px-2 text-decoration-none border rounded-2">
                                                    Não permitido
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Container para a paginação (opcional) -->
                        <div id="pagination-container" data-table-id="1" class="d-flex justify-content-end mt-3">
                            <!-- Os botões de paginação serão inseridos aqui pelo plugin -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ URL::asset('assets/js/excelTableFilter.js') }}"></script>
    <script>
        //$('.excel-filter-table').empty();
        $(document).ready(function() {

            $('.filter_funcoes').each(function() {
                const $table = $(this);
                const $paginationContainer = $table.next('#pagination-container');

                $table.excelTableFilter({
                    pagination: true,
                    rowsPerPage: 10,
                    rowsPerPageSelector: true,
                    rowsPerPageOptions: [5, 10, 20, 50, 100],
                    paginationContainer: $paginationContainer,
                    captions: {
                        prevPaginateBtn: 'Anterior',
                        nextPaginateBtn: 'Próximo'
                    }
                });

                // Obter a instância do plugin armazenada nos dados da tabela
                const filterInstance = $table.data('excelTableFilter');

                // Associar o evento de clique ao botão de exportação
                $('#export-button').on('click', function() {
                    filterInstance.exportToExcel('dados_filtrados.xlsx');
                });
            });
        });
    </script>
@endsection
