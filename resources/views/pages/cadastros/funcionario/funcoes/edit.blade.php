@extends('dashboard')
@section('title', 'Funções CBO')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Editar Função
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


    <form method="post" action="{{ route('cadastro.funcionario.funcoes.update', $funcao->id) }}">
        @csrf

        <div class="row">

            <div class="col-xl-8 mb-0">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Dados da Função</h4>

                    </div><!-- end card header -->

                    <div class="card-body">

                        <div class="live-preview">


                            <div class="row mt-3">

                                <div class="col-md-5">
                                    <label class="form-label" for="funcao">Setor</label>
                                    <select class="form-select form-control-sm" id="id_setor" name="id_setor">
                                    
                                    <option value="">Selecione um setor</option>
                                        @foreach($setores as $setor)
                                            <option value="{{$setor->id}}" {{($funcao->id_setor == $setor->id ? 'selected' : '')}}>{{$setor->nome_setor}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label" for="funcao">Função</label>
                                    <input class="form-control @error('funcao') is-invalid @enderror" id="funcao"
                                        name="funcao" type="text" value="{{ $funcao->funcao ?? old('funcao') }}"
                                        placeholder="Nome da função" required>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label" for="codigo">Código CBO</label>
                                    <input class="form-control @error('codigo') is-invalid @enderror" id="codigo"
                                        name="codigo" type="text" value="{{ $funcao->codigo ?? old('codigo') }}"
                                        placeholder="Código CBO" required>
                                </div>

                            </div>

                            <hr class="text-warning">

                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1 text-muted mb-0 mt-2 ">Qualificações obriatórias</h4>

                                <label class="form-label mx-4" id="botoes">Ações</label>
                                <div id="botoes">
                                    <button class="btn btn-warning listar-ativos-adicionar" type="button"><i
                                            class="mdi mdi-plus"></i></button>
                                    <button class="btn btn-primary listar-ativos-remover" type="button"><i
                                            class="mdi mdi-minus"></i></button>
                                </div>


                            </div><!-- end card header -->


                            @foreach ($qualificacoes as $qualificacao)
                                <div class="row mt-3">
                                    <div class="col-sm-12 col-xl-6">
                                        <div class="form-group">
                                            <label for="id_ativo_externo">Documento</label>
                                            <input class="form-control" id="nome_qualificacao" name="nome_qualificacao[]"
                                                type="text"
                                                value="{{ $qualificacao->nome_qualificacao ?? old('nome_qualificacao') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-8 col-xl-4">
                                        <div class="form-group">
                                            <label for="quantidade">Tempo de validade (em meses)</label>
                                            <input class="form-control" id="tempo_validade" name="tempo_validade[]"
                                                type="text"
                                                value="{{ $qualificacao->tempo_validade ?? old('tempo_validade') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-xl-2 mt-sm-0 pt-sm-0 mt-xl-4 pt-xl-2">
                                        @if (session()->get('usuario_vinculo')->id_nivel == 1 or
                                                session()->get('usuario_vinculo')->id_nivel == 15 or
                                                session()->get('usuario_vinculo')->id_nivel == 10)
                                            <div class="form-group m-auto">
                                                <button data-id="{{ $qualificacao->id }}"
                                                    class="btn btn-danger btn-sm" id="delete-funcao" title="Excluir"> <i
                                                        class="mdi mdi-delete"></i>Excluir
                                                </button>
                                            </div>
                                        @else
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div id="listar-ativos-linha"></div>

                            <template id="listar-ativos-template">
                                <div class="row template-row mt-4">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="id_ativo_externo">Documento</label>
                                            <input type="text" class="form-control" name="nome_qualificacao[]">
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="quantidade">Tempo de validade (em meses)</label>
                                            <input class="form-control" type="text" id="tempo_validade"
                                                name="tempo_validade[]">
                                        </div>
                                    </div>

                                </div>
                            </template>

                        </div>


                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>
            <!-- end col -->

            <div class="col-xl-4 mb-0">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2 ">
                        <h4 class="card-title mb-0 flex-grow-1 mb-0 mt-2 mx-4">EPIs' Obrigatórios</h4>

                        <label class="form-label mx-4" id="botoes">Ações</label>
                        <div id="botoes">
                            <button class="btn btn-warning listar-epis-adicionar" type="button"><i
                                    class="mdi mdi-plus"></i></button>
                            <button class="btn btn-primary listar-epis-remover" type="button"><i
                                    class="mdi mdi-minus"></i></button>
                        </div>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row mt-3">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="id_obra">EPI</label>
                                    <select class="form-select form-control select2" id="epi" name="epi[]">
                                        <option value="">Selecione um EPI</option>
                                        <option value="1">EPI 1</option>
                                        <option value="2">EPI 2</option>
                                        <option value="3">EPI 3</option>
                                        <option value="4">EPI 4</option>
                                    </select>
                                </div>

                                <div class="row" id="listar-epis-linha"></div>
                                <template id="listar-epis-template">

                                    <div class="col-md-6 mb-3 template-row-epis">
                                        <label class="form-label" for="id_obra">Novo Epi</label>
                                        <select class="form-select form-control select2" id="epi" name="epi[]">
                                            <option value="">Selecione um EPI</option>
                                            <option value="1">EPI 1</option>
                                            <option value="2">EPI 2</option>
                                            <option value="3">EPI 3</option>
                                            <option value="4">EPI 4</option>
                                        </select>
                                    </div>

                                </template>

                                <hr class="text-warning my-3">

                                @foreach ($epis_funcao as $epi_funcao)
                                    {{-- dd($epis_funcao) --}}

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">EPIs cadastrado</label>

                                        <a href="{{ route('cadastro.funcionario.funcoes.delete_epi', ['id' => $epi_funcao->id]) }}"
                                            title="Excluir EPI">
                                            <i class="mdi mdi-alpha-x-box-outline text-danger mdi-18px"></i>
                                        </a>


                                        <select class="form-select form-control select2"
                                            id="epi{{ $epi_funcao->id_estoque }}" name="epi[]">
                                            @foreach ($epis as $epi)
                                                <option value="{{ $epi->id }} "
                                                    {{ $epi->id == $epi_funcao->id_estoque ? 'selected' : '' }}>
                                                    {{ $epi->nome_produto }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach

                            </div>


                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <div class="col-12 mb-3 bg-white text-center">
            <button class="btn btn-primary btn-md font-weight-medium my-3" type="submit">Salvar</button>

            <a href="{{ route('cadastro.funcionario.funcoes.index') }}">
                <button class="btn btn-danger btn-md font-weight-medium my-3 mx-3" type="button">Cancelar</button>
            </a>
        </div>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <script>
        $(document).ready(function() {

            $(document).on('click', '#delete-funcao', function(e) {
                e.preventDefault();

                var qualificacaoId = $(this).data('id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                let timerInterval;
                Swal.fire({
                    title: 'Excluindo a função',
                    html: '<p><b></b> segundos.</p> Aguarde o término',
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const b = Swal.getHtmlContainer().querySelector('b');
                        timerInterval = setInterval(() => {
                            b.textContent = Math.round(Swal.getTimerLeft() / 1000);
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                }).then((result) => {
                    $.ajax({
                        url: "/admin/cadastro/funcionario/funcoes/delete_funcao/" +
                            qualificacaoId,
                        method: 'post',
                        success: function(response) {

                            console.log(response)

                            Swal.fire({
                                title: response.type === 'success' ?
                                    'Sucesso!' : 'Atenção!',
                                text: response.message,
                                icon: response.type,
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed && response.type ===
                                    'success') {
                                        window.location.reload();
                                        
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Erro!',
                                text: 'Ocorreu um erro ao excluir a função.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }
                    });
                });
            });
        });
    </script>


@endsection
