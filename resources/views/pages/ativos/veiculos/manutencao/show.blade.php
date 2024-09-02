@extends('dashboard')
@section('title', 'Veículo')
@section('content')


    <div class="page-header mt-5">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span>
            Destalhes da Manutenção

            <a class="mx-5" href="{{ url('veiculos.index') }}">
                <button class="btn btn-warning btn-md font-weight-medium" type="button">Voltar</button>
            </a>
        </h3>
    </div>

    <div class="d-flex justify-content-center">
        <div class="container d-flex justify-content-center row ">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header text-center">
                        <strong> {{ $manutencoes->veiculo->veiculo }}</strong>
                    </div>
                    <div class="card-body d-flex justify-content-center row px-0">

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

                        <table class="table table-bordered" style="width: 70%">
                            <tbody>
                                <tr>
                                    <th class="col-1">ID</th>
                                    <td class="col-6">{{ $manutencoes->id }}</td>
                                </tr>
                                <tr>
                                    <th class="col-1">Veiculo</th>
                                    <td class="col-6">
                                        {{ $manutencoes->veiculo->tipo == 'maquinas' ? $manutencoes->veiculo->codigo_da_maquina : $manutencoes->veiculo->placa }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="col-1">Fornecedor</th>
                                    <td class="col-6">{{ $manutencoes->fornecedor->nome_fantasia }}</td>
                                </tr>
                                <tr>
                                    <th class="col-1">Serviço</th>
                                    <td class="col-6">{{ $manutencoes->servico->nome_servico ?? 'Sem reg.' }}</td>
                                </tr>
                                <tr>
                                    <th class="col-1">Dt de execução</th>
                                    <td class="col-6">{{ $manutencoes->data_de_execucao }}</td>
                                </tr>
                                <tr>
                                    <th class="col-1">Validade</th>
                                    <td class="col-6">{{ $manutencoes->data_de_vencimento }}</td>
                                </tr>
                                <tr>
                                    <th class="col-1">Descrição</th>
                                    <td class="col-6">{{ $manutencoes->descricao }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="col-12 my-5">
                            <div class="d-flex card-footer border">
                                <div class="form-group col-12 p-1 m-0">
                                    <form class="m-0 my-2"
                                        action="{{ route('ativo.veiculo.manutencao.storeimagem', @$manutencoes->id) }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf

                                        <div class="d-flex flex-row bd-highlight ">
                                            <div class="bd-highlight col-3">
                                                <h4 class="card-title">GALERIA DE IMAGENS</h4>
                                            </div>

                                            <div class="bd-highlight col-6">
                                                <input type="file" class="form-control mx-2" name="imagens[]" multiple>
                                            </div>

                                            <input type="hidden" name="manutencao_id" value="{{ $manutencoes->id }}">

                                            <div class="bd-highlight mx-2 col-3">
                                                <button type="submit" class="btn btn-primary">Cadastrar novas
                                                    imagens</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                @foreach ($imagens as $imagem)
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <form
                                                    action="{{ route('ativo.veiculo.manutencao.deleteImagem', $imagem->id) }}"
                                                    method="POST" class="m-0 p-0">
                                                    @csrf

                                                    <button type="submit" class="btn-close text-danger float-end fs-11"
                                                        aria-label="Close" data-toggle="tooltip" data-placement="top"
                                                        type="submit" title="Excluir"
                                                        onclick="return confirm('Tem certeza que deseja exluir a imagem?')"></button>
                                                </form>
                                                <h6 class="card-title mb-0">{{ $imagem->descricao ?? 'Descrição' }}</h6>
                                            </div>
                                            <div class="card-body p-4 text-center">
                                                <div class="mx-auto avatar mb-3">
                                                    <img src="{{ asset('imagens/veiculos/manutencoes/') }}/{{ $imagem->manutencao_id }}/{{ $imagem->nome_imagem }}"
                                                        alt="" class="img-fluid">
                                                </div>
                                            </div>
                                            <div class="card-footer text-center">
                                                <div class="row">
                                                    <div class="d-flex col-6">
                                                        <button type="button" id="btn_modal_img_veiculo" class="btn btn-primary " data-id="{{ $imagem->id }}"
                                                            data-bs-toggle="modal" data-bs-target="#modal_img_veiculo">
                                                            <i class="mdi mdi-pencil mdi-18x"></i>Alterar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end col -->
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- tooltips and popovers modal -->
    <div class="modal fade" id="modal_img_veiculo" tabindex="-1" aria-labelledby="exampleModalPopoversLabel"
        aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalPopoversLabel">Alterar dados da imagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('ativo.veiculo.manutencao.updateImagem', 0) }}" method="POST" class="m-0 p-0"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        <input type="hidden" id="id_imagem" name="id_imagem">
                        <input type="hidden" name="manutencao_id" value="{{ $manutencoes->id }}">

                        <div class="col-12 my-2">
                            <label for="firstnameInput" class="form-label">Alterar imagem</label>
                            <input type="file" id="input-file-now-custom-3" class="form-control" name="imagem">
                        </div>

                        <div class="col-12 my-2">
                            <label for="firstnameInput" class="form-label">Descrição da imagem</label>
                            <input type="text" class="form-control" name="descricao">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>

                </form>
            </div>
        </div>

    @endsection
    @section('script')
        <script src="{{ URL::asset('build/js/pages/profile-setting.init.js') }}"></script>

        <script>
            $(document).ready(function() {

                // Encontre todos os botões "btn_modal_img_veiculo"
                var detalhesButtons = document.querySelectorAll('#btn_modal_img_veiculo');

                //Fazer um loop para encontrar os botões
                detalhesButtons.forEach(function(button) {
                    button.addEventListener('click', function() {

                        $("#id_imagem").val('');

                        let id_imagem = $(this).attr('data-id');

                        $("#id_imagem").val(id_imagem);

                    });
                });

            });
        </script>
    @endsection
