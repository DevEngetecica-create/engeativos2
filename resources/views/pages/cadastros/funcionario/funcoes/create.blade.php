@extends('dashboard')
@section('title', 'Funções CBO')
@section('content')

<div class="page-header my-4">
    <h3 class="page-title my-4">
        <span class="page-title-icon bg-gradient-primary text-white">
        </span> Cadastrar Função
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

<div class="row mt-5">
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Ops!</strong><br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

<form method="post" action="{{ route('cadastro.funcionario.funcoes.store') }}" enctype="multipart/form-data">
    @csrf


    <div class="row">

        <div class="col-xl-6 mb-0">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Dados da Função</h4>

                </div><!-- end card header -->

                <div class="card-body">

                    <div class="live-preview">


                        <div class="row mt-3">
                            <div class="col-md-8">
                                <label class="form-label" for="funcao">Função</label>
                                <input class="form-control" id="funcao" name="funcao" type="text" placeholder="Nome da função">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="codigo">Código CBO</label>
                                <input class="form-control" id="codigo" name="codigo" type="text">
                            </div>

                        </div>

                        <hr class="text-warning">

                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1 text-muted mb-0 mt-2 ">Qualificações obriatórias</h4>

                            <label class="form-label mx-4" id="botoes">Ações</label>
                            <div id="botoes">
                                <button class="btn btn-warning listar-ativos-adicionar" type="button"><i class="mdi mdi-plus"></i></button>
                                <button class="btn btn-primary listar-ativos-remover" type="button"><i class="mdi mdi-minus"></i></button>
                            </div>


                        </div><!-- end card header -->

                        <div class="row mt-3">
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="id_ativo_externo">Documento</label>
                                    <input class="form-control" id="nome_qualificacao" name="nome_qualificacao[]" type="text">
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="quantidade">Tempo de validade (em meses)</label>
                                    <input class="form-control" id="tempo_validade" name="tempo_validade[]" type="text">
                                </div>
                            </div>
                        </div>

                        <div id="listar-ativos-linha"></div>

                        <template id="listar-ativos-template">
                            <div class="row template-row mt-4">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="id_ativo_externo">Documento</label>
                                        <input type="text" class="form-control" id="nome_qualificacao" name="nome_qualificacao[]">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="quantidade">Tempo de validade (em meses)</label>
                                        <input class="form-control" type="text" id="tempo_validade" name="tempo_validade[]">
                                    </div>
                                </div>

                            </div>
                        </template>

                    </div>


                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-xl-6 mb-0">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2 ">
                    <h4 class="card-title mb-0 flex-grow-1 mb-0 mt-2 mx-4">EPIs' Obrigatórios</h4>

                    <label class="form-label mx-4" id="botoes">Ações</label>
                    <div id="botoes">
                        <button class="btn btn-warning listar-epis-adicionar" type="button"><i class="mdi mdi-plus"></i></button>
                        <button class="btn btn-primary listar-epis-remover" type="button"><i class="mdi mdi-minus"></i></button>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">

                    <div class="live-preview">

                        <div class="row mt-3">

                            <div class="col-md-8">
                                <label class="form-label" for="id_obra">EPI</label> <a class="badge badge-success ml-3 text-white" data-toggle="modal" data-target="#modal-add" style="cursor: pointer;">Inclusão rápida de Obra</a>
                                <select class="form-select select2" id="epi" name="epi[]">
                                    <option value="">Selecione um EPI</option>
                                    <option value="1">EPI 1</option>
                                    <option value="2">EPI 2</option>
                                    <option value="3">EPI 3</option>
                                    <option value="4">EPI 4</option>
                                </select>
                            </div>

                            <!-- <div class="col-md-4">
                                <label class="form-label" for="cert_auto">Nº CA (cert. de autorização)</label>
                                <input class="form-control" id="cert_aut" name="cert_aut[]" type="text">
                            </div>
 -->
                        </div>

                        <hr class="text-warning">

                        <div id="listar-epis-linha"></div>

                        <template id="listar-epis-template">
                            <div class="row template-row-epis mt-4">
                                <div class="col-md-8">
                                    <label class="form-label" for="id_obra">EPI</label> <a class="badge badge-success ml-3 text-white" data-toggle="modal" data-target="#modal-add" style="cursor: pointer;">Inclusão rápida de Obra</a>
                                    <select class="form-select select2" id="epi" name="epi[]">
                                        <option value="">Selecione um EPI</option>
                                        <option value="1">EPI 1</option>
                                        <option value="2">EPI 2</option>
                                        <option value="3">EPI 3</option>
                                        <option value="4">EPI 4</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="cert_auto">Nº CA (cert. de autorização)</label>
                                        <input class="form-control" type="text" id="cert_aut" name="cert_aut[]">
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-12 mt-5">
            <button class="btn btn-primary btn-md font-weight-medium" type="submit">Salvar</button>

            <a href="{{ route('cadastro.funcionario.funcoes.index') }}">
                <button class="btn btn-danger btn-md font-weight-medium" type="button">Cancelar</button>
            </a>
        </div>
    </div>
</form>




@endsection